<?php

  namespace SLTK\Database;

  class ServersTableBuilder implements TableBuilder {

    public function addConstraints(string $tablePrefix): void {
      global $wpdb;
      $tableName = $this->tableName($tablePrefix);
      $gamesTableName = $tablePrefix . TableNames::GAMES;
      $platformsTableName = $tablePrefix . TableNames::PLATFORMS;

      $gameConstraintName = 'fk_game_id';
      $gameExistsCheckSql = "SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS
                        WHERE TABLE_NAME = '{$tableName}'
                        AND CONSTRAINT_NAME = '{$gameConstraintName}';";

      $gameExists = (int)$wpdb->get_var($gameExistsCheckSql);
      if (!$gameExists) {
        $fkSql = "ALTER TABLE {$tableName}
                    ADD CONSTRAINT {$gameConstraintName} 
                        FOREIGN KEY (gameId) REFERENCES {$gamesTableName}(id);";
        $wpdb->query($fkSql);
      }

      $platformConstraintName = 'fk_platform_id';
      $platformExistsCheckSql = "SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS
                        WHERE TABLE_NAME = '{$tableName}'
                        AND CONSTRAINT_NAME = '{$platformConstraintName}';";

      $platformExists = (int)$wpdb->get_var($platformExistsCheckSql);
      if (!$platformExists) {
        $fkSql = "ALTER TABLE {$tableName}
                    ADD CONSTRAINT {$platformConstraintName} 
                        FOREIGN KEY (platformId) REFERENCES {$platformsTableName}(id);";
        $wpdb->query($fkSql);
      }
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
          PRIMARY KEY  (id),
        ) {$charsetCollate};";
    }

    public function initialData(string $tablePrefix): void {
    }

    public function tableName(string $tablePrefix): string {
      return $tablePrefix . TableNames::SERVERS;
    }
  }