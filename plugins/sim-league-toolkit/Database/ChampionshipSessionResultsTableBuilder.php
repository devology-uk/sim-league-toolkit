<?php

  namespace SLTK\Database;

  class ChampionshipSessionResultsTableBuilder extends TableBuilder
  {
    public function tableName(string $tablePrefix): string
    {
      return $tablePrefix . TableNames::CHAMPIONSHIP_SESSION_RESULTS;
    }

    public function definitionSql(string $tablePrefix, string $charsetCollate): string
    {
      $tableName = $this->tableName($tablePrefix);

      return "CREATE TABLE {$tableName} (
            id BIGINT NOT NULL AUTO_INCREMENT,
            eventSessionId BIGINT NOT NULL,
            championshipEntryId BIGINT NOT NULL,
            position INT NULL,
            totalTimeMs INT NULL,
            fastestLapMs INT NULL,
            sector1TimeMs INT NULL,
            sector2TimeMs INT NULL,
            sector3TimeMs INT NULL,
            lapsCompleted INT NOT NULL DEFAULT 0,
            status VARCHAR(20) NOT NULL DEFAULT 'finished',
            points INT NULL,
            PRIMARY KEY (id),
            UNIQUE INDEX idx_session_entry (eventSessionId, championshipEntryId),
            INDEX idx_session (eventSessionId)
        ) {$charsetCollate};";
    }

    public function addConstraints(string $tablePrefix): void
    {
      $this->addSimpleForeignKey($tablePrefix, TableNames::EVENT_SESSIONS, 'eventSessionId');
      $this->addSimpleForeignKey($tablePrefix, TableNames::CHAMPIONSHIP_ENTRIES, 'championshipEntryId');
    }

    public function initialData(string $tablePrefix): void
    {
    }

    public function applyAdjustments(string $tablePrefix): void
    {
      global $wpdb;
      $tableName = $this->tableName($tablePrefix);

      if (empty($wpdb->get_results("SHOW COLUMNS FROM {$tableName} LIKE 'validLapsCount'"))) {
        $wpdb->query("ALTER TABLE {$tableName} ADD COLUMN validLapsCount INT NULL");
      }
    }
  }
