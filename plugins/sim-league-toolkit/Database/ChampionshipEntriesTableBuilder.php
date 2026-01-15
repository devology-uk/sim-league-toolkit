<?php

  namespace SLTK\Database;

  class ChampionshipEntriesTableBuilder extends TableBuilder {

    public function addConstraints(string $tablePrefix): void {
      $this->addSimpleForeignKey($tablePrefix, TableNames::CHAMPIONSHIPS, 'fk_championship_entries_championshipId', 'championshipId');
      $this->addSimpleForeignKey($tablePrefix, TableNames::EVENT_CLASSES, 'fk_championship_entries_eventClassId', 'eventClassId');
      $this->addSimpleForeignKey($tablePrefix, TableNames::CARS, 'fk_championship_entries_carId', 'carId');
      $this->addSimpleForeignKey($tablePrefix, TableNames::USERS, 'fk_championship_entries_userId', 'userId');

    }

    public function applyAdjustments(string $tablePrefix): void {

    }

    public function definitionSql(string $tablePrefix, string $charsetCollate): string {
      $tableName = $this->tableName($tablePrefix);

      return "CREATE TABLE {$tableName} (
          id bigint NOT NULL AUTO_INCREMENT,
          championshipId bigint NOT NULL,
          eventClassId bigint NOT NULL,
          carId bigint NOT NULL,
          userId bigint unsigned NOT NULL,
          PRIMARY KEY  (id)
        ) {$charsetCollate};";
    }

    public function initialData(string $tablePrefix): void {

    }

    public function tableName(string $tablePrefix): string {
      return $tablePrefix . TableNames::CHAMPIONSHIP_ENTRIES;
    }
  }