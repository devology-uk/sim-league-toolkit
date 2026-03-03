<?php

  namespace SLTK\Database;

  class StandaloneEventEventClassesTableBuilder extends TableBuilder {

    public function addConstraints(string $tablePrefix): void {
      $this->addSimpleForeignKey($tablePrefix, TableNames::STANDALONE_EVENTS, 'standaloneEventId');
      $this->addSimpleForeignKey($tablePrefix, TableNames::EVENT_CLASSES, 'eventClassId');
    }

    public function applyAdjustments(string $tablePrefix): void {
      global $wpdb;
      $tableName = $this->tableName($tablePrefix);

      if (empty($wpdb->get_results("SHOW COLUMNS FROM {$tableName} LIKE 'max_entrants'"))) {
        $wpdb->query("ALTER TABLE {$tableName} ADD COLUMN max_entrants INT UNSIGNED NULL");
      }
    }

    public function definitionSql(string $tablePrefix, string $charsetCollate): string {
      $tableName = $this->tableName($tablePrefix);

      return "CREATE TABLE {$tableName} (
        standaloneEventId bigint NOT NULL,
        eventClassId bigint NOT NULL,
        PRIMARY KEY  (standaloneEventId, eventClassId)
      ) {$charsetCollate};";
    }

    public function initialData(string $tablePrefix): void {

    }

    public function tableName(string $tablePrefix): string {
      return $tablePrefix . TableNames::STANDALONE_EVENT_CLASSES;
    }
  }
