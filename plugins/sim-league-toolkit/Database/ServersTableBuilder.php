<?php

  namespace SLTK\Database;

  class ServersTableBuilder implements TableBuilder {

    public function applyAdjustments(string $tablePrefix): void {}

    public function definitionSql(string $tablePrefix, string $charsetCollate): string {
      $serversTableName = $this->tableName($tablePrefix);
      $gamesTableName = $tablePrefix . TableNames::GAMES;
      $platformsTableName = $tablePrefix . TableNames::PLATFORMS;

      return "CREATE TABLE {$serversTableName} (
          id bigint NOT NULL AUTO_INCREMENT,
          gameId bigint NOT NULL,
          name tinytext NOT NULL,
          isHostedServer tinyint(1) NOT NULL DEFAULT 0,
          platformId bigint NOT NULL DEFAULT 1,
          PRIMARY KEY  (id),
          FOREIGN KEY (gameId) REFERENCES {$gamesTableName}(id),
          FOREIGN KEY (platformId) REFERENCES {$platformsTableName}(id)
        ) {$charsetCollate};";
    }

    public function initialData(string $tablePrefix): void {}

    public function tableName(string $tablePrefix): string {
      return $tablePrefix . TableNames::SERVERS;
    }
  }