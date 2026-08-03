<?php

  namespace SLTK\Database;

  abstract class TableBuilder {
    abstract public function addConstraints(string $tablePrefix): void;

    abstract public function applyAdjustments(string $tablePrefix): void;

    abstract public function definitionSql(string $tablePrefix, string $charsetCollate): string;

    abstract public function initialData(string $tablePrefix): void;

    abstract public function tableName(string $tablePrefix): string;

    protected function addSimpleForeignKey(string $tablePrefix, string $parentTableName, string $columnName, string $parentColumn = 'id', ?string $constraintName = null): void {
      global $wpdb;
      $tableName = $this->tableName($tablePrefix);
      $prefixedParentTableName = $tablePrefix . $parentTableName;
      $constraintName ??= 'fk_' . $tableName . '_' . $columnName;

      $constraintExistsCheckSql = "SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS
                        WHERE TABLE_NAME = '$tableName'
                        AND CONSTRAINT_NAME = '$constraintName';";

      $constraintExists = (int)$wpdb->get_var($constraintExistsCheckSql);
      if (!$constraintExists) {
        $fkSql = "ALTER TABLE {$tableName}
                    ADD CONSTRAINT $constraintName
                        FOREIGN KEY ($columnName) REFERENCES $prefixedParentTableName($parentColumn);";
        $wpdb->query($fkSql);
      }
    }

    /**
     * Inserts a seed row, or updates it in place if a row already matches on $matchColumns.
     * Keeps CSV-seeded reference tables (cars, tracks, track layouts) in sync on re-seed
     * rather than only ever inserting rows that are new since the last run.
     */
    protected function upsertSeedRow(string $tableName, array $matchColumns, array $data): void {
      global $wpdb;

      $whereClauses = [];
      foreach ($matchColumns as $column => $value) {
        $whereClauses[] = $wpdb->prepare("{$column} = %s", $value);
      }

      $existingId = $wpdb->get_var("SELECT id FROM {$tableName} WHERE " . implode(' AND ', $whereClauses) . ';');

      if ($existingId === null) {
        $wpdb->insert($tableName, array_merge($matchColumns, $data));
      } else {
        $wpdb->update($tableName, $data, ['id' => $existingId]);
      }
    }
  }
