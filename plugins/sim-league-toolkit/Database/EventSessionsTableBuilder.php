<?php

  namespace SLTK\Database;

  class EventSessionsTableBuilder extends TableBuilder
  {
    public function tableName(string $tablePrefix): string
    {
      return $tablePrefix . TableNames::EVENT_SESSIONS;
    }

    public function definitionSql(string $tablePrefix, string $charsetCollate): string
    {
      $tableName = $this->tableName($tablePrefix);

      return "CREATE TABLE {$tableName} (
            id BIGINT NOT NULL AUTO_INCREMENT,
            eventRefId BIGINT NOT NULL,
            name VARCHAR(255) NOT NULL,
            sessionType VARCHAR(50) NOT NULL,
            startTime TIME NOT NULL,
            duration INT NOT NULL,
            sortOrder INT NOT NULL DEFAULT 0,
            PRIMARY KEY (id),
            INDEX idx_event_ref (eventRefId),
            INDEX idx_sort (eventRefId, sortOrder)
        ) {$charsetCollate};";
    }

    public function addConstraints(string $tablePrefix): void
    {
      $this->addSimpleForeignKey($tablePrefix, TableNames::EVENT_REFS, 'eventRefId');
    }

    public function initialData(string $tablePrefix): void
    {
    }

    public function applyAdjustments(string $tablePrefix): void
    {
    }
  }
