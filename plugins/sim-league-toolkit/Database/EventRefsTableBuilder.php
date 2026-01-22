<?php

  namespace SLTK\Database;

  class EventRefsTableBuilder extends TableBuilder {
    public function addConstraints(string $tablePrefix): void {
    }

    public function applyAdjustments(string $tablePrefix): void {
    }

    public function definitionSql(string $tablePrefix, string $charsetCollate): string {
      $tableName = $this->tableName($tablePrefix);

      return "CREATE TABLE {$tableName} (
            id BIGINT NOT NULL AUTO_INCREMENT,
            eventType VARCHAR(50) NOT NULL
            PRIMARY KEY (id),
            INDEX idx_event_type (eventType)
        ) {$charsetCollate};";
    }

    public function initialData(string $tablePrefix): void {
    }

    public function tableName(string $tablePrefix): string {
      return $tablePrefix . TableNames::EVENT_REFS;
    }
  }
