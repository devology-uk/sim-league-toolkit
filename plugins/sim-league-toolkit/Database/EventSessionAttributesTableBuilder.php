<?php

  namespace SLTK\Database;

  class EventSessionAttributesTableBuilder extends TableBuilder {

    public function addConstraints(string $tablePrefix): void {
      $this->addSimpleForeignKey($tablePrefix, TableNames::EVENT_SESSIONS, 'eventSessionId', 'id', true);
    }

    public function applyAdjustments(string $tablePrefix): void {
    }

    public function definitionSql(string $tablePrefix, string $charsetCollate): string {
      $tableName = $this->tableName($tablePrefix);

      return "CREATE TABLE {$tableName} (
            id BIGINT NOT NULL AUTO_INCREMENT,
            eventSessionId BIGINT NOT NULL,
            attributeKey VARCHAR(100) NOT NULL,
            attributeValue TEXT,
            PRIMARY KEY (id),
            UNIQUE INDEX idx_session_key (eventSessionId, attributeKey),
            INDEX idx_session (eventSessionId)
        ) {$charsetCollate};";
    }

    public function initialData(string $tablePrefix): void {
    }

    public function tableName(string $tablePrefix): string {
      return $tablePrefix . TableNames::EVENT_SESSION_ATTRIBUTES;
    }
  }