<?php

  namespace SLTK\Database;

  use SLTK\Core\GameKeys;

  class CarClassTableBuilder implements TableBuilder {
    public function addConstraints(string $tablePrefix): void {
      global $wpdb;
      $tableName = $this->tableName($tablePrefix);
      $gamesTableName = $tablePrefix . TableNames::GAMES;

      $gameConstraintName = 'fk_car_classes_gameId';
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

      return "CREATE TABLE {$tableName} (
          id bigint NOT NULL AUTO_INCREMENT,
          gameId bigint NOT NULL,
          name tinytext NOT NULL,
          displayName tinytext NOT NULL,
          PRIMARY KEY  (Id)
        ) {$charsetCollate};";
    }

    public function initialData(string $tablePrefix): void {
      global $wpdb;
      $tableName = $this->tableName($tablePrefix);
      $gamesTableName = $tablePrefix . TableNames::GAMES;

      $data = [
        [
          'name' => 'GT2',
          'displayName' => 'GT2',
          'gameKey' => GameKeys::AssettoCorsaCompetizione
        ],
        [
          'name' => 'GT3',
          'displayName' => 'GT3',
          'gameKey' => GameKeys::AssettoCorsaCompetizione,
        ],
        [
          'name' => 'GT4',
          'displayName' => 'GT4',
          'gameKey' => GameKeys::AssettoCorsaCompetizione,
        ],
        [
          'name' => 'GTC',
          'displayName' => 'GTC',
          'gameKey' => GameKeys::AssettoCorsaCompetizione,
        ],
        [
          'name' => 'TCX',
          'displayName' => 'TCX',
          'gameKey' => GameKeys::AssettoCorsaCompetizione,
        ],
        [
          'name' => 'HYP',
          'displayName' => 'Hypercar',
          'gameKey' => GameKeys::LeMansUltimate,
        ],
        [
          'name' => 'LMP2',
          'displayName' => 'LMP2',
          'gameKey' => GameKeys::LeMansUltimate,
        ],
        [
          'name' => 'GTE',
          'displayName' => 'GTE',
          'gameKey' => GameKeys::LeMansUltimate,
        ],
        [
          'name' => 'GT3',
          'displayName' => 'LMGT3',
          'gameKey' => GameKeys::LeMansUltimate,
        ]
      ];

      foreach ($data as $item) {
        $gameId = $wpdb->get_var("SELECT id FROM {$gamesTableName} WHERE gameKey = '{$item['gameKey']}'");
        $exists = $wpdb->get_var("SELECT COUNT(*) FROM {$tableName} WHERE gameId = {$gameId} AND name = '{$item['name']}';");

        if (!$exists) {
          $newItem = [
            'gameId' => $gameId,
            'name' => $item['name'],
            'displayName' => $item['displayName']
          ];
          $wpdb->insert($tableName, $newItem);
        }
      }
    }

    public function tableName(string $tablePrefix): string {
      return $tablePrefix . TableNames::CAR_CLASSES;
    }
  }