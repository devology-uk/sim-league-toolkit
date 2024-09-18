<?php

  namespace SLTK\Database;

  class ChampionshipsTableBuilder implements TableBuilder {

    public function applyAdjustments(string $tablePrefix): void {}

    public function definitionSql(string $tablePrefix, string $charsetCollate): string {
      $tableName = $this->tableName($tablePrefix);

      return "CREATE TABLE {$tableName} (
        id bigint NOT NULL AUTO_INCREMENT,
        bannerImageUrl tinytext NULL,
        description text NOT NULL,
        gameId bigint NOT NULL DEFAULT 0,
        isActive tinyint(1) NOT NULL DEFAULT 1,
        isTrackMasterChampionship tinyint(1) NOT NULL DEFAULT 0,
        name tinytext NOT NULL,
        platformId bigint NOT NULL DEFAULT 0,
        resultsToDiscard tinyint NOT NULL DEFAULT 0,
        ruleSetId bigint NOT NULL DEFAULT -1,
        serverId bigint NULL,
        startDate date NOT NULL,
        trackMasterTrackId bigint NULL,
        trophiesAwarded tinyint(1) NOT NULL DEFAULT 0,
        PRIMARY KEY  (id)
      ) {$charsetCollate};";
    }

    public function initialData(string $tablePrefix): void {}

    public function tableName(string $tablePrefix): string {
      return TableNames::CHAMPIONSHIPS;
    }
  }