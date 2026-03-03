<?php

  namespace SLTK\Database;

  class ChampionshipEventClassesTableBuilder extends TableBuilder {

    public function addConstraints(string $tablePrefix): void {
      $this->addSimpleForeignKey($tablePrefix, TableNames::CHAMPIONSHIPS, 'championshipId');
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
        championshipId bigint NOT NULL,
        eventClassId bigint NOT NULL,
        PRIMARY KEY  (championshipId, eventClassId)
      ) {$charsetCollate};";
    }

    public function initialData(string $tablePrefix): void {

    }

    public function tableName(string $tablePrefix): string {
      return $tablePrefix . TableNames::CHAMPIONSHIP_EVENT_CLASSES;
    }
  }