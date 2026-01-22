<?php

  namespace SLTK\Database\Repositories;

  use Exception;
  use SLTK\Database\TableNames;
  use stdClass;

  class EventSessionAttributesRepository extends RepositoryBase {
    /**
     * @throws Exception
     */
    public static function add(int $eventSessionId, string $key, mixed $value): int {
      return self::insert(TableNames::EVENT_SESSION_ATTRIBUTES, [
        'eventSessionId' => $eventSessionId,
        'attributeKey' => $key,
        'attributeValue' => self::serializeValue($value),
      ]);
    }

    /**
     * @throws Exception
     */
    public static function delete(int $id): void {
      self::deleteById(TableNames::EVENT_SESSION_ATTRIBUTES, $id);
    }

    /**
     * @throws Exception
     */
    public static function deleteBySessionId(int $eventSessionId): void {
      $tableName = self::prefixedTableName(TableNames::EVENT_SESSION_ATTRIBUTES);

      $sql = "DELETE FROM {$tableName} WHERE eventSessionId = '{$eventSessionId}';";

      self::execute($sql);
    }

    public static function deserializeValue(string $value, string $type = 'string'): mixed {
      return match ($type) {
        'boolean' => $value === '1' || $value === 'true',
        'number' => is_numeric($value) ? (int)$value : 0,
        'array', 'object' => json_decode($value, true),
        default => $value,
      };
    }

    /**
     * @return stdClass[]
     * @throws Exception
     */
    public static function getBySessionId(int $eventSessionId): array {
      $tableName = self::prefixedTableName(TableNames::EVENT_SESSION_ATTRIBUTES);

      $query = "SELECT * FROM {$tableName} WHERE eventSessionId = '{$eventSessionId}';";

      return self::getResults($query);
    }

    /**
     * @throws Exception
     */
    public static function setAttributes(int $eventSessionId, array $attributes): void {
      // Delete existing attributes
      self::deleteBySessionId($eventSessionId);

      // Insert new attributes
      foreach ($attributes as $key => $value) {
        if ($value !== null) {
          self::add($eventSessionId, $key, $value);
        }
      }
    }

    /**
     * @throws Exception
     */
    public static function upsert(int $eventSessionId, string $key, mixed $value): void {
      $tableName = self::prefixedTableName(TableNames::EVENT_SESSION_ATTRIBUTES);
      $serializedValue = self::serializeValue($value);

      $sql = self::db()->prepare(
        "INSERT INTO {$tableName} (eventSessionId, attributeKey, attributeValue)
             VALUES (%d, %s, %s)
             ON DUPLICATE KEY UPDATE attributeValue = %s",
        $eventSessionId,
        $key,
        $serializedValue,
        $serializedValue
      );

      self::execute($sql);
    }

    private static function serializeValue(mixed $value): string {
      if (is_bool($value)) {
        return $value ? '1' : '0';
      }

      if (is_array($value) || is_object($value)) {
        return json_encode($value);
      }

      return (string)$value;
    }
  }