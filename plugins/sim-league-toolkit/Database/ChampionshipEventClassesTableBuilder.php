<?php

  namespace SLTK\Database;

  use SLTK\Database\TableBuilder;

  class ChampionshipEventClassesTableBuilder extends TableBuilder {

    public function addConstraints(string $tablePrefix): void {
      $this->addSimpleForeignKey($tablePrefix, TableNames::CHAMPIONSHIPS, 'fk_championship_event_classes_championshipId', 'championshipId');
      $this->addSimpleForeignKey($tablePrefix, TableNames::EVENT_CLASSES, 'fk_championship_event_classes_eventClassId', 'eventClassId');
    }

    public function applyAdjustments(string $tablePrefix): void {

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