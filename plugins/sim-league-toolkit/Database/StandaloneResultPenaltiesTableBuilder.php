<?php

  namespace SLTK\Database;

  class StandaloneResultPenaltiesTableBuilder extends TableBuilder
  {
    public function tableName(string $tablePrefix): string
    {
      return $tablePrefix . TableNames::STANDALONE_RESULT_PENALTIES;
    }

    public function definitionSql(string $tablePrefix, string $charsetCollate): string
    {
      $tableName = $this->tableName($tablePrefix);

      return "CREATE TABLE {$tableName} (
            id BIGINT NOT NULL AUTO_INCREMENT,
            standaloneSessionResultId BIGINT NOT NULL,
            reason TEXT NOT NULL,
            penaltySeconds INT NULL,
            PRIMARY KEY (id),
            INDEX idx_result (standaloneSessionResultId)
        ) {$charsetCollate};";
    }

    public function addConstraints(string $tablePrefix): void
    {
      $this->addSimpleForeignKey($tablePrefix, TableNames::STANDALONE_SESSION_RESULTS, 'standaloneSessionResultId');
    }

    public function initialData(string $tablePrefix): void
    {
    }

    public function applyAdjustments(string $tablePrefix): void
    {
    }
  }
