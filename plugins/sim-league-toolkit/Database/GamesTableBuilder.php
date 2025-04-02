<?php

  namespace SLTK\Database;

  use SLTK\Core\GameKeys;

  class GamesTableBuilder implements TableBuilder {

    public function addConstraints(string $tablePrefix): void {
    }

    public function applyAdjustments(string $tablePrefix): void {
    }

    public function definitionSql(string $tablePrefix, string $charsetCollate): string {
      $tableName = $this->tableName($tablePrefix);

      return "CREATE TABLE {$tableName} (
        id bigint NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        gameKey varchar(5) NOT NULL,
        latestVersion varchar(20) NOT NULL,
        supportsResultUpload bit NOT NULL DEFAULT 0,
        PRIMARY KEY  (id)
      ) {$charsetCollate};";
    }

    public function initialData(string $tablePrefix): void {
      global $wpdb;
      $tableName = $this->tableName($tablePrefix);

      $data = [
        [
          'name' => 'Assetto Corsa Competizione',
          'gameKey' => GameKeys::AssettoCorsaCompetizione,
          'latestVersion' => '1.10.2',
          'supportsResultUpload' => true,
        ],
        [
          'name' => 'Assetto Corsa',
          'gameKey' => GameKeys::AssettoCorsa,
          'latestVersion' => '1.16.0',
          'supportsResultUpload' => false,
        ],
        [
          'name' => 'Le Mans Ultimate',
          'gameKey' => GameKeys::LeMansUltimate,
          'latestVersion' => '1.4',
          'supportsResultUpload' => false,
        ],
        [
          'name' => 'Automobilista 2',
          'gameKey' => GameKeys::AutoMobilista2,
          'latestVersion' => '1.6',
          'supportsResultUpload' => false,
        ]
      ];

      foreach ($data as $item) {
        $exists = $wpdb->get_var("SELECT COUNT(*) FROM {$tableName} WHERE gameKey = '{$item['gameKey']}';");

        if (!$exists) {
          $wpdb->insert($tableName, $item);
        }
      }
    }

    public function tableName(string $tablePrefix): string {
      return $tablePrefix . TableNames::GAMES;
    }
  }