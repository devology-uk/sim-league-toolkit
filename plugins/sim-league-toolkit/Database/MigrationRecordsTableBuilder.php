<?php

  namespace SLTK\Database;

  class MigrationRecordsTableBuilder extends TableBuilder {

    public function addConstraints(string $tablePrefix): void {
    }

    public function applyAdjustments(string $tablePrefix): void {
    }

    public function definitionSql(string $tablePrefix, string $charsetCollate): string {
      $tableName = $this->tableName($tablePrefix);

      return "CREATE TABLE {$tableName} (
            id BIGINT NOT NULL AUTO_INCREMENT,
            sourceEntity VARCHAR(50) NOT NULL,
            sourceId BIGINT NOT NULL,
            targetId BIGINT NOT NULL,
            migratedAt DATETIME NOT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY uniq_source (sourceEntity, sourceId)
        ) {$charsetCollate};";
    }

    public function initialData(string $tablePrefix): void {
    }

    public function tableName(string $tablePrefix): string {
      return $tablePrefix . TableNames::MIGRATION_RECORDS;
    }
  }
