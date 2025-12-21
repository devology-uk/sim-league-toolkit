<?php

  namespace SLTK\Database;

  if (!defined('ABSPATH')) {
    die;
  }

  class GamePlatformsTableBuilder extends TableBuilder {
    public function addConstraints(string $tablePrefix): void {
      $this->addSimpleForeignKey($tablePrefix, TableNames::GAMES, 'fk_game_gameId', 'gameId');
      $this->addSimpleForeignKey($tablePrefix, TableNames::PLATFORMS, 'fk_platform_platformId', 'platformId');
    }

    public function applyAdjustments(string $tablePrefix): void {
    }

    public function definitionSql(string $tablePrefix, string $charsetCollate): string {
      $tableName = $this->tableName($tablePrefix);

      return "CREATE TABLE {$tableName} (
        gameId bigint NOT NULL,
        platformId bigint NOT NULL,
        PRIMARY KEY  (gameId, platformId)
      ) {$charsetCollate};";
    }

    public function initialData(string $tablePrefix): void {
      global $wpdb;
      $tableName = $this->tableName($tablePrefix);
      $gamesTableName = $tablePrefix . TableNames::GAMES;
      $platformsTableName = $tablePrefix . TableNames::PLATFORMS;

      $accGameId = (int)$wpdb->get_var("SELECT id FROM {$gamesTableName} WHERE gameKey = 'ACC'");
      $acGameId = (int)$wpdb->get_var("SELECT id FROM {$gamesTableName} WHERE gameKey = 'AC'");
      $lmuGameId = (int)$wpdb->get_var("SELECT id FROM {$gamesTableName} WHERE gameKey = 'LMU'");
      $ams2GameId = (int)$wpdb->get_var("SELECT id FROM {$gamesTableName} WHERE gameKey = 'AMS2'");

      $pcPlatformId = (int)$wpdb->get_var("SELECT id FROM {$platformsTableName} WHERE shortName = 'PC'");
      $psPlatformId = (int)$wpdb->get_var("SELECT id FROM {$platformsTableName} WHERE shortName = 'PS'");

      $mappings = [
        [$accGameId, $pcPlatformId],
        [$accGameId, $psPlatformId],
        [$acGameId, $pcPlatformId],
        [$acGameId, $psPlatformId],
        [$ams2GameId, $pcPlatformId],
        [$lmuGameId, $pcPlatformId],
      ];

      for($i = 0; $i < count($mappings); $i++) {

        $gameId = $mappings[$i][0];
        $platformId = $mappings[$i][1];

        if(!((int)$wpdb->get_var("SELECT COUNT(*) FROM {$tableName} WHERE gameId = {$gameId} AND platformId = {$platformId}"))) {
          $wpdb->insert($tableName, [
            'gameId' => $gameId,
            'platformId' => $platformId,
          ]);
        }
      }
    }

    public function tableName(string $tablePrefix): string {
      return $tablePrefix . TableNames::GAME_PLATFORMS;
    }
  }