<?php

  namespace SLTK\Core;

  use SLTK\Core\Enums\ChampionshipType;
  use SLTK\Core\Enums\EventType;
  use SLTK\Core\Enums\EventSessionType;
  use SLTK\Database\TableNames;

  class EnumValidator
  {
    /**
     * Validates that database values match PHP enum definitions
     *
     * @return array List of validation errors, empty if all valid
     */
    public static function validate(): array
    {
      $errors = [];

      $errors = array_merge($errors, self::validateColumn(
        TableNames::CHAMPIONSHIPS,
        'championshipType',
        ChampionshipType::class
      ));

      $errors = array_merge($errors, self::validateColumn(
        TableNames::EVENT_REFS,
        'eventType',
        EventType::class
      ));

      return array_merge($errors, self::validateColumn(
        TableNames::EVENT_SESSIONS,
        'sessionType',
        EventSessionType::class
      ));
    }

    private static function validateColumn(string $table, string $column, string $enumClass): array
    {
      $errors = [];
      $phpValues = array_map(fn($case) => $case->value, $enumClass::cases());

      $invalidValues = self::getInvalidValues($table, $column, $phpValues);

      if (!empty($invalidValues))
      {
        $errors[] = sprintf(
          '%s.%s contains invalid values: %s',
          $table,
          $column,
          implode(', ', $invalidValues)
        );
      }

      return $errors;
    }

    private static function getInvalidValues(string $table, string $column, array $validValues): array
    {
      global $wpdb;

      $tableName = $wpdb->prefix . $table;
      $placeholders = implode(',', array_fill(0, count($validValues), '%s'));

      $query = $wpdb->prepare(
        "SELECT DISTINCT {$column} 
             FROM {$tableName} 
             WHERE {$column} IS NOT NULL 
             AND {$column} NOT IN ({$placeholders})",
        ...$validValues
      );

      $results = $wpdb->get_col($query);

      return $results ?: [];
    }
  }