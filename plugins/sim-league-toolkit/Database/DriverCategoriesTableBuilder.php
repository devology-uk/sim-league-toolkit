<?php

  namespace SLTK\Database;


  use SLTK\Core\GameKeys;

  if (!defined('ABSPATH')) {
    die;
  }

  class DriverCategoriesTableBuilder implements TableBuilder {
    public function addConstraints(string $tablePrefix): void {
      global $wpdb;
      $tableName = $this->tableName($tablePrefix);
      $gamesTableName = $tablePrefix . TableNames::GAMES;

      $gameConstraintName = 'fk_driver_categories_gameId';
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
    }

    public function applyAdjustments(string $tablePrefix): void {
    }

    public function definitionSql(string $tablePrefix, string $charsetCollate): string {
      $tableName = $this->tableName($tablePrefix);

      return "CREATE TABLE " . $tableName . " (
        id mediumint NOT NULL AUTO_INCREMENT,
          gameId bigint NOT NULL,
          name tinytext NOT NULL,
          plaque tinytext NOT NULL,
        PRIMARY KEY  (id)
      ) " . $charsetCollate . ";";
    }

    public function initialData(string $tablePrefix): void {
      global $wpdb;
      $tableName = $this->tableName($tablePrefix);
      $gamesTableName = $tablePrefix . TableNames::GAMES;

      $data = [
        [
          'name' => 'Bronze',
          'plaque' => 'AM',
          'gameKey' => GameKeys::AssettoCorsaCompetizione
        ],
        [
          'name' => 'Silver',
          'plaque' => 'PRO/AM',
          'gameKey' => GameKeys::AssettoCorsaCompetizione,
        ],
        [
          'name' => 'Gold',
          'plaque' => 'PRO',
          'gameKey' => GameKeys::AssettoCorsaCompetizione,
        ],
        [
          'name' => 'Platinum',
          'plaque' => 'PRO',
          'gameKey' => GameKeys::AssettoCorsaCompetizione,
        ]
      ];

      foreach ($data as $item) {
        $gameId = $wpdb->get_var("SELECT id FROM {$gamesTableName} WHERE gameKey = '{$item['gameKey']}'");
        $exists = $wpdb->get_var("SELECT COUNT(*) FROM {$tableName} WHERE gameId = {$gameId} AND name = '{$item['name']}';");

        if (!$exists) {
          $newItem = [
            'gameId' => $gameId,
            'name' => $item['name'],
            'plaque' => $item['plaque']
          ];
          $wpdb->insert($tableName, $newItem);
        }
      }
    }

    public function tableName(string $tablePrefix): string {
      return $tablePrefix . TableNames::DRIVER_CATEGORIES;
    }
  }