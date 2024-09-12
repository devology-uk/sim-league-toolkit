<?php

  namespace SLTK\Database;

  class RaceNumbersTableBuilder implements TableBuilder {

    /**
     * @inheritDoc
     */
    public function applyAdjustments(string $tablePrefix): void {
      // TODO: Implement applyAdjustments() method.
    }

    /**
     * @inheritDoc
     */
    public function definitionSql(string $tablePrefix, string $charsetCollate): string {
      $tableName = $this->tableName($tablePrefix);
      $usersTableName = $tablePrefix . 'users';

      return "CREATE TABLE {$tableName} (
              userId bigint unsigned NOT NULL,
              raceNumber mediumint NOT NULL DEFAULT 0,
              PRIMARY KEY  (userId),
              FOREIGN KEY FK_RaceNumbers_UserId (userId) REFERENCES {$usersTableName}(ID)        
            ) {$charsetCollate};";
    }

    /**
     * @inheritDoc
     */
    public function initialData(string $tablePrefix): void {
      // TODO: Implement initialData() method.
    }

    /**
     * @inheritDoc
     */
    public function tableName(string $tablePrefix): string {
      return $tablePrefix . TableNames::RACE_NUMBERS;
    }
  }