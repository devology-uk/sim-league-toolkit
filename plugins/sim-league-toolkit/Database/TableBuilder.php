<?php

  namespace SLTK\Database;

  abstract class TableBuilder {
    abstract public function addConstraints(string $tablePrefix): void;

    abstract public function applyAdjustments(string $tablePrefix): void;

    abstract public function definitionSql(string $tablePrefix, string $charsetCollate): string;

    abstract public function initialData(string $tablePrefix): void;

    abstract public function tableName(string $tablePrefix): string;

    protected function addSimpleForeignKey(string $tablePrefix, string $parentTableName, string $columnName, string $parentColumn = 'id'): void {
      global $wpdb;
      $tableName = $this->tableName($tablePrefix);
      $prefixedParentTableName = $tablePrefix . $parentTableName;
      $constraintName = 'fk_' . $tableName . '_' . $columnName;

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
  }
