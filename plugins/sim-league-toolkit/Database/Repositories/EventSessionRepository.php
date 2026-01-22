<?php

  namespace SLTK\Database\Repositories;

  use Exception;
  use SLTK\Database\TableNames;
  use stdClass;

  class EventSessionRepository extends RepositoryBase {
    /**
     * @throws Exception
     */
    public static function add(array $data): int {
      $attributes = $data['attributes'] ?? [];
      unset($data['attributes']);

      $id = self::insert(TableNames::EVENT_SESSIONS, $data);

      if (!empty($attributes)) {
        EventSessionAttributesRepository::setAttributes($id, $attributes);
      }

      return $id;
    }

    /**
     * @throws Exception
     */
    public static function delete(int $id): void {
      // Attributes are deleted via CASCADE, but explicit delete if preferred
      EventSessionAttributesRepository::deleteBySessionId($id);
      self::deleteById(TableNames::EVENT_SESSIONS, $id);
    }

    /**
     * @throws Exception
     */
    public static function getById(int $id): ?stdClass {
      $sessions = TableNames::prefixed(TableNames::EVENT_SESSIONS);
      $eventRefs = TableNames::prefixed(TableNames::EVENT_REFS);

      $query = "SELECT 
                s.*,
                er.eventType
            FROM {$sessions} s
            INNER JOIN {$eventRefs} er ON s.eventRefId = er.id
            WHERE s.id = '{$id}';";

      $session = self::getRow($query);

      if ($session !== null) {
        $session->attributes = self::loadAttributes($id);
      }

      return $session;
    }

    /**
     * @return stdClass[]
     * @throws Exception
     */
    public static function listAll(): array {
      $sessions = TableNames::prefixed(TableNames::EVENT_SESSIONS);
      $eventRefs = TableNames::prefixed(TableNames::EVENT_REFS);

      $query = "SELECT 
                s.*,
                er.eventType
            FROM {$sessions} s
            INNER JOIN {$eventRefs} er ON s.eventRefId = er.id
            ORDER BY s.sortOrder;";

      $results = self::getResults($query);

      foreach ($results as $session) {
        $session->attributes = self::loadAttributes($session->id);
      }

      return $results;
    }

    /**
     * @return stdClass[]
     * @throws Exception
     */
    public static function listByEventRefId(int $eventRefId): array {
      $sessions = TableNames::prefixed(TableNames::EVENT_SESSIONS);
      $eventRefs = TableNames::prefixed(TableNames::EVENT_REFS);

      $query = "SELECT 
                s.*,
                er.eventType
            FROM {$sessions} s
            INNER JOIN {$eventRefs} er ON s.eventRefId = er.id
            WHERE s.eventRefId = '{$eventRefId}'
            ORDER BY s.sortOrder;";

      $results = self::getResults($query);

      foreach ($results as $session) {
        $session->attributes = self::loadAttributes($session->id);
      }

      return $results;
    }

    /**
     * @throws Exception
     */
    public static function update(int $id, array $data): void {
      $attributes = $data['attributes'] ?? null;
      unset($data['attributes']);

      self::updateById(TableNames::EVENT_SESSIONS, $id, $data);

      if ($attributes !== null) {
        EventSessionAttributesRepository::setAttributes($id, $attributes);
      }
    }

    /**
     * @throws Exception
     */
    public static function updateSortOrder(int $id, int $sortOrder): void {
      self::updateById(TableNames::EVENT_SESSIONS, $id, ['sortOrder' => $sortOrder]);
    }

    /**
     * @throws Exception
     */
    private static function loadAttributes(int $sessionId): array {
      $rows = EventSessionAttributesRepository::getBySessionId($sessionId);
      $attributes = [];

      foreach ($rows as $row) {
        $attributes[$row->attributeKey] = $row->attributeValue;
      }

      return $attributes;
    }
  }