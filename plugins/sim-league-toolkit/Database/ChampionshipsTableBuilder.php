<?php

  namespace SLTK\Database;

  class ChampionshipsTableBuilder extends TableBuilder {

    public function addConstraints(string $tablePrefix): void {
      $this->addSimpleForeignKey($tablePrefix, TableNames::GAMES, 'fk_championships_games', 'gameId');
      $this->addSimpleForeignKey($tablePrefix, TableNames::PLATFORMS, 'fk_championships_platforms', 'platformId');
      $this->addSimpleForeignKey($tablePrefix, TableNames::RULE_SETS, 'fk_championships_rule_sets', 'ruleSetId');
      $this->addSimpleForeignKey($tablePrefix, TableNames::SCORING_SETS, 'fk_championships_scoring_sets', 'scoringSetId');
      $this->addSimpleForeignKey($tablePrefix, TableNames::TRACKS, 'fk_championships_tracks', 'trackMasterTrackId');
      $this->addSimpleForeignKey($tablePrefix, TableNames::TRACK_LAYOUTS, 'fk_championships_track_layouts', 'trackMasterTrackLayoutId');
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
        entryChamgeLimit tinyint NOT NULL DEFAULT 0,
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