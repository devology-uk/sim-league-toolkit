<?php

  namespace SLTK\Database;

  class ChampionshipsTableBuilder extends TableBuilder {

    public function addConstraints(string $tablePrefix): void {
      $this->addSimpleForeignKey($tablePrefix, TableNames::GAMES, 'gameId');
      $this->addSimpleForeignKey($tablePrefix, TableNames::PLATFORMS, 'platformId');
      $this->addSimpleForeignKey($tablePrefix, TableNames::RULE_SETS, 'ruleSetId');
      $this->addSimpleForeignKey($tablePrefix, TableNames::SCORING_SETS, 'scoringSetId');
      $this->addSimpleForeignKey($tablePrefix, TableNames::TRACKS, 'trackMasterTrackId');
      $this->addSimpleForeignKey($tablePrefix, TableNames::TRACK_LAYOUTS, 'trackMasterTrackLayoutId');
    }

    public function applyAdjustments(string $tablePrefix): void {
    }

    public function definitionSql(string $tablePrefix, string $charsetCollate): string {
      $tableName = $this->tableName($tablePrefix);

      return "CREATE TABLE {$tableName} (
        id bigint NOT NULL AUTO_INCREMENT,
        allowEntryChange boolean NOT NULL DEFAULT 0,
        bannerImageUrl tinytext NULL,
        description text NOT NULL,
        entryChangeLimit tinyint NOT NULL DEFAULT 0,
        gameId bigint NOT NULL,
        isActive bit NOT NULL DEFAULT 0,
        isTrackMasterChampionship bit NOT NULL DEFAULT 0,
        name tinytext NOT NULL,
        platformId bigint NOT NULL,
        resultsToDiscard tinyint NOT NULL DEFAULT 0,
        ruleSetId bigint NULL,
        scoringSetId bigint NOT NULL,
        startDate date NOT NULL,
        trackMasterTrackId bigint NULL,
        trackMasterTrackLayoutId bigint NULL,
        trophiesAwarded bit NOT NULL DEFAULT 0,
        PRIMARY KEY  (id)
      ) {$charsetCollate};";
    }

    public function initialData(string $tablePrefix): void {
    }

    public function tableName(string $tablePrefix): string {
      return $tablePrefix . TableNames::CHAMPIONSHIPS;
    }
  }