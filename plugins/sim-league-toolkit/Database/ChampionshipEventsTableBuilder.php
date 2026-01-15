<?php

  namespace SLTK\Database;

  class ChampionshipEventsTableBuilder extends TableBuilder {

    public function addConstraints(string $tablePrefix): void {

    }

    public function applyAdjustments(string $tablePrefix): void {

    }

    public function definitionSql(string $tablePrefix, string $charsetCollate): string {
      $tableName = $this->tableName($tablePrefix);

      return "CREATE TABLE {$tableName}(
                id bigint NOT NULL AUTO_INCREMENT,
                championshipId bigint NOT NULL,
                trackId bigint NOT NULL,
                trackLayoutId bigint NULL,
                name tinytext NOT NULL,
                description text NULL,
                startDate date NOT NULL,
                startTime time NOT NULL DEFAULT '14:00',
                isActive boolean NOT NULL DEFAULT true,
                bannerImageUrl tinytext NULL,
                ruleSetId bigint NULL,
                isCompleted boolean NOT NULL DEFAULT false,
                PRIMARY KEY  (id)
              ) {$charsetCollate};";
    }

    public function initialData(string $tablePrefix): void {
    }

    public function tableName(string $tablePrefix): string {
      return $tablePrefix . TableNames::CHAMPIONSHIP_EVENTS;
    }
  }