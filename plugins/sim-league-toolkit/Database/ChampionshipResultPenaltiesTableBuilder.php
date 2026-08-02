<?php

  namespace SLTK\Database;

  class ChampionshipResultPenaltiesTableBuilder extends TableBuilder
  {
    public function tableName(string $tablePrefix): string
    {
      return $tablePrefix . TableNames::CHAMPIONSHIP_RESULT_PENALTIES;
    }

    public function definitionSql(string $tablePrefix, string $charsetCollate): string
    {
      $tableName = $this->tableName($tablePrefix);

      return "CREATE TABLE {$tableName} (
            id BIGINT NOT NULL AUTO_INCREMENT,
            championshipSessionResultId BIGINT NOT NULL,
            reason TEXT NOT NULL,
            penaltySeconds INT NULL,
            PRIMARY KEY (id),
            INDEX idx_result (championshipSessionResultId)
        ) {$charsetCollate};";
    }

    public function addConstraints(string $tablePrefix): void
    {
      // Default constraint-name generation exceeds MySQL's 64-character identifier limit for this
      // table/column pairing, so a shortened name is supplied explicitly.
      $this->addSimpleForeignKey(
        $tablePrefix,
        TableNames::CHAMPIONSHIP_SESSION_RESULTS,
        'championshipSessionResultId',
        'id',
        'fk_champ_result_penalties_result_id'
      );
    }

    public function initialData(string $tablePrefix): void
    {
    }

    public function applyAdjustments(string $tablePrefix): void
    {
    }
  }
