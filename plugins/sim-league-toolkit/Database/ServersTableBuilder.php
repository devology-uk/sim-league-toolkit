<?php

  namespace SLTK\Database;

  class ServersTableBuilder extends TableBuilder {

    public function addConstraints(string $tablePrefix): void {
      $this->addSimpleForeignKey($tablePrefix, TableNames::GAMES, 'gameId');
      $this->addSimpleForeignKey($tablePrefix, TableNames::PLATFORMS, 'platformId');
    }

    public function applyAdjustments(string $tablePrefix): void {
    }

    public function definitionSql(string $tablePrefix, string $charsetCollate): string {
      $serversTableName = $this->tableName($tablePrefix);

      return "CREATE TABLE {$serversTableName} (
          id bigint NOT NULL AUTO_INCREMENT,
          gameId bigint NOT NULL,
          name tinytext NOT NULL,
          isHostedServer tinyint(1) NOT NULL DEFAULT 0,
          platformId bigint NOT NULL DEFAULT 1,
          PRIMARY KEY  (id)
        ) {$charsetCollate};";
    }

    public function initialData(string $tablePrefix): void {
    }

    public function tableName(string $tablePrefix): string {
      return $tablePrefix . TableNames::SERVERS;
    }
  }