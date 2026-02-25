<?php

  namespace SLTK\Database;

  class ChampionshipEventsTableBuilder extends TableBuilder {
    public function addConstraints(string $tablePrefix): void {
      $this->addSimpleForeignKey($tablePrefix, TableNames::EVENT_REFS, 'eventRefId');
      $this->addSimpleForeignKey($tablePrefix, TableNames::CHAMPIONSHIPS, 'championshipId');
    }

    public function applyAdjustments(string $tablePrefix): void {
    }

    public function definitionSql(string $tablePrefix, string $charsetCollate): string {
      $tableName = $this->tableName($tablePrefix);

      return "CREATE TABLE {$tableName} (
            id BIGINT NOT NULL AUTO_INCREMENT,
            eventRefId BIGINT NOT NULL,
            championshipId BIGINT NOT NULL,
            trackId BIGINT NOT NULL,
            trackLayoutId BIGINT NULL,
            name VARCHAR(255) NOT NULL,
            startDateTime DATETIME NOT NULL,
            isActive BOOLEAN NOT NULL DEFAULT false,
            isCompleted BOOLEAN NOT NULL DEFAULT false,
            bannerImageUrl TINYTEXT NULL,
            PRIMARY KEY (id),
            INDEX idx_championship (championshipId),
            INDEX idx_event_ref (eventRefId)
        ) {$charsetCollate};";
    }

    public function initialData(string $tablePrefix): void {
    }

    public function tableName(string $tablePrefix): string {
      return $tablePrefix . TableNames::CHAMPIONSHIP_EVENTS;
    }
  }
