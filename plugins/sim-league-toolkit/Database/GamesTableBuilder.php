<?php

  namespace SLTK\Database;

  use SLTK\Core\GameKeys;

  class GamesTableBuilder extends TableBuilder {

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
        published bit NOT NULL DEFAULT 0,
        supportsLayouts bit NOT NULL DEFAULT 0,
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
          'published' => true,
          'supportsLayouts' => false,
        ],
        [
          'name' => 'Assetto Corsa',
          'gameKey' => GameKeys::AssettoCorsa,
          'latestVersion' => '1.16.0',
          'supportsResultUpload' => false,
          'published' => false,
          'supportsLayouts' => false,
        ],
        [
          'name' => 'Le Mans Ultimate',
          'gameKey' => GameKeys::LeMansUltimate,
          'latestVersion' => '1.4',
          'supportsResultUpload' => false,
          'published' => true,
          'supportsLayouts' => true,
        ],
        [
          'name' => 'Automobilista 2',
          'gameKey' => GameKeys::AutoMobilista2,
          'latestVersion' => '1.6',
          'supportsResultUpload' => false,
          'published' => true,
          'supportsLayouts' => true,
        ],
        [
          'name' => 'F1 2025',
          'gameKey' => GameKeys::F125,
          'latestVersion' => '2025',
          'supportsResultUpload' => false,
          'published' => false,
          'supportsLayouts' => true,
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