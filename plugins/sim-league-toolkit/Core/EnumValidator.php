<?php

  namespace SLTK\Core;

  use SLTK\Core\Enums\EventType;
  use SLTK\Core\Enums\EventSessionType;

  class EnumValidator {
    /**
     * Validates that database enum/varchar values match PHP enum definitions
     *
     * @return array List of validation errors, empty if all valid
     */
    public static function validate(): array {
      $errors = [];

      $errors = array_merge($errors, self::validateEnum(
        'sltk_event_refs',
        'eventType',
        EventType::class
      ));

      return array_merge($errors, self::validateEnum(
        'sltk_event_sessions',
        'sessionType',
        EventSessionType::class
      ));
    }

    private static function validateEnum(string $table, string $column, string $enumClass): array {
      $errors = [];
      $phpValues = array_map(fn($case) => $case->value, $enumClass::cases());

      // Check if column is ENUM type
      $dbValues = self::getEnumValuesFromSchema($table, $column);

      if ($dbValues !== null) {
        // Column is ENUM type - validate against schema
        $missing = array_diff($phpValues, $dbValues);
        $extra = array_diff($dbValues, $phpValues);

        if (!empty($missing)) {
          $errors[] = "{$table}.{$column} missing enum values: " . implode(', ', $missing);
        }

        if (!empty($extra)) {
          $errors[] = "{$table}.{$column} has extra enum values: " . implode(', ', $extra);
        }
      } else {
        // Column is VARCHAR - validate actual data
        $invalidValues = self::getInvalidValues($table, $column, $phpValues);

        if (!empty($invalidValues)) {
          $errors[] = "{$table}.{$column} contains invalid values: " . implode(', ', $invalidValues);
        }
      }

      return $errors;
    }

    private static function getEnumValuesFromSchema(string $table, string $column): ?array {
      global $wpdb;

      $tableName = $wpdb->prefix . $table;

      $row = $wpdb->get_row($wpdb->prepare(
        'SELECT COLUMN_TYPE 
             FROM INFORMATION_SCHEMA.COLUMNS 
             WHERE TABLE_SCHEMA = %s 
             AND TABLE_NAME = %s 
             AND COLUMN_NAME = %s',
        DB_NAME,
        $tableName,
        $column
      ));

      if (!$row || !preg_match('/^enum$(.+)$$/i', $row->COLUMN_TYPE, $matches)) {
        return null;
      }

      return array_map(
        fn($v) => trim($v, "'"),
        str_getcsv($matches[1], ',', "'")
      );
    }

    private static function getInvalidValues(string $table, string $column, array $validValues): array {
      global $wpdb;

      $tableName = $wpdb->prefix . $table;
      $placeholders = implode(',', array_fill(0, count($validValues), '%s'));

      $query = $wpdb->prepare(
        "SELECT DISTINCT {$column} 
             FROM {$tableName} 
             WHERE {$column} NOT IN ({$placeholders})",
        ...$validValues
      );

      $results = $wpdb->get_col($query);

      return $results ?: [];
    }
  }