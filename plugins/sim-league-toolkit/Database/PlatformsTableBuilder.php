<?php

  namespace SLTK\Database;

  class PlatformsTableBuilder extends TableBuilder {

    public function addConstraints(string $tablePrefix): void {
    }

    public function applyAdjustments(string $tablePrefix): void {
    }

    public function definitionSql(string $tablePrefix, string $charsetCollate): string {

      $tableName = $this->tableName($tablePrefix);

      return "CREATE TABLE {$tableName} (
          id bigint NOT NULL AUTO_INCREMENT,
          name tinytext NOT NULL,
          shortName tinytext NOT NULL,
          playerIdPrefix char(1) NOT NULL,
          PRIMARY KEY  (id)
        ) {$charsetCollate};";
    }

    public function initialData(string $tablePrefix): void {
      $platforms = [
        [
          'name' => 'Steam',
          'playerIdPrefix' => 'S',
          'shortName' => 'PC',
        ],
        [
          'name' => 'PlayStation',
          'playerIdPrefix' => 'P',
          'shortName' => 'PS',
        ]
      ];

      global $wpdb;
      $tableName = $this->tableName($tablePrefix);

      foreach ($platforms as $platform) {
        $existingId = $wpdb->get_var("SELECT id FROM {$tableName} WHERE shortName = '{$platform['shortName']}';");
        if ($existingId === null) {
          $wpdb->insert($tableName, $platform);
        }
      }
    }

    public function tableName(string $tablePrefix): string {
      return $tablePrefix . TableNames::PLATFORMS;
    }
  }