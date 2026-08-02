<?php

  namespace SLTK\Database;

  class TrophiesTableBuilder extends TableBuilder {

    public function addConstraints(string $tablePrefix): void {
      $this->addSimpleForeignKey($tablePrefix, TableNames::USERS, 'memberId');
      $this->addSimpleForeignKey($tablePrefix, TableNames::EVENT_SESSIONS, 'eventSessionId');
      $this->addSimpleForeignKey($tablePrefix, TableNames::EVENT_CLASSES, 'eventClassId');
    }

    public function applyAdjustments(string $tablePrefix): void {
    }

    public function definitionSql(string $tablePrefix, string $charsetCollate): string {
      $tableName = $this->tableName($tablePrefix);

      return "CREATE TABLE {$tableName} (
            id BIGINT NOT NULL AUTO_INCREMENT,
            memberId BIGINT UNSIGNED NOT NULL,
            scope VARCHAR(20) NOT NULL,
            scopeId BIGINT NOT NULL,
            eventSessionId BIGINT NULL,
            eventClassId BIGINT NULL,
            awardType VARCHAR(20) NOT NULL,
            awardedDate DATETIME NOT NULL,
            PRIMARY KEY (id),
            INDEX idx_scope (scope, scopeId)
        ) {$charsetCollate};";
    }

    public function initialData(string $tablePrefix): void {
    }

    public function tableName(string $tablePrefix): string {
      return $tablePrefix . TableNames::TROPHIES;
    }
  }
