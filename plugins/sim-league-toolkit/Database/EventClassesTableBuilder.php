<?php

  namespace SLTK\Database;

  use SLTK\Core\GameKeys;

  class EventClassesTableBuilder extends TableBuilder {

    public function addConstraints(string $tablePrefix): void {
      $this->addSimpleForeignKey($tablePrefix, TableNames::GAMES, 'gameId');
      $this->addSimpleForeignKey($tablePrefix, TableNames::CARS, 'singleCarId');
      $this->addSimpleForeignKey($tablePrefix, TableNames::DRIVER_CATEGORIES, 'driverCategoryId');
    }

    public function applyAdjustments(string $tablePrefix): void {

    }

    public function definitionSql(string $tablePrefix, string $charsetCollate): string {
      $tableName = $this->tableName($tablePrefix);

      return "CREATE TABLE {$tableName} (
          id bigint NOT NULL AUTO_INCREMENT,
          carClass mediumtext NOT NULL,
          driverCategoryId bigint NOT NULL DEFAULT 1,
          gameId bigint NOT NULL,
          isBuiltIn boolean NOT NULL DEFAULT 1,
          isSingleCarClass boolean NOT NULL DEFAULT 0,
          name mediumtext NOT NULL,
          singleCarId bigint NULL,          
          PRIMARY KEY  (id)
        ) {$charsetCollate};";
    }

    public function initialData(string $tablePrefix): void {
      global $wpdb;
      $tableName = $this->tableName($tablePrefix);
      $gamesTableName = $tablePrefix . TableNames::GAMES;
      $carsTableName = $tablePrefix . TableNames::CARS;
      $driverCategoriesTableName = $tablePrefix . TableNames::DRIVER_CATEGORIES;

      $data = [
        [
          'carClass' => 'GT3',
          'driverCategoryName' => 'Bronze',
          'gameKey' => GameKeys::AssettoCorsaCompetizione,
          'isSingleCarClass' => false,
          'name' => 'GT3 Open',
          'singleCarKey' => null,
        ],
        [
          'carClass' => 'GT4',
          'driverCategoryName' => 'Bronze',
          'gameKey' => GameKeys::AssettoCorsaCompetizione,
          'isSingleCarClass' => false,
          'name' => 'GT4 Open',
          'singleCarKey' => null,
        ],
        [
          'carClass' => 'GT2',
          'driverCategoryName' => 'Bronze',
          'gameKey' => GameKeys::AssettoCorsaCompetizione,
          'isSingleCarClass' => false,
          'name' => 'GT2 Open',
          'singleCarKey' => null,
        ],
        [
          'carClass' => 'GTC',
          'driverCategoryName' => 'Bronze',
          'gameKey' => GameKeys::AssettoCorsaCompetizione,
          'isSingleCarClass' => true,
          'name' => 'Porsche 992 Cup Open',
          'singleCarKey' => 'porsche_992_gt3_cup',
        ],
        [
          'carClass' => 'HYP',
          'driverCategoryName' => 'Bronze',
          'gameKey' => GameKeys::LeMansUltimate,
          'isSingleCarClass' => false,
          'name' => 'Hyper Car Open',
          'singleCarKey' => null,
        ],
        [
          'carClass' => 'LMP2',
          'driverCategoryName' => 'Bronze',
          'gameKey' => GameKeys::LeMansUltimate,
          'isSingleCarClass' => false,
          'name' => 'LMP2 Open',
          'singleCarKey' => null,
        ],
        [
          'carClass' => 'LMP3',
          'driverCategoryName' => 'Bronze',
          'gameKey' => GameKeys::LeMansUltimate,
          'isSingleCarClass' => false,
          'name' => 'LMP3 Open',
          'singleCarKey' => null,
        ],
        [
          'carClass' => 'GTE',
          'driverCategoryName' => 'Bronze',
          'gameKey' => GameKeys::LeMansUltimate,
          'isSingleCarClass' => false,
          'name' => 'GTE Open',
          'singleCarKey' => null,
        ],
        [
          'carClass' => 'GT3',
          'driverCategoryName' => 'Bronze',
          'gameKey' => GameKeys::LeMansUltimate,
          'isSingleCarClass' => false,
          'name' => 'GT3 Open',
          'singleCarKey' => null,
        ],
        [
          'carClass' => 'Porsche Carrera Cup',
          'driverCategoryName' => 'Bronze',
          'gameKey' => GameKeys::AutoMobilista2,
          'isSingleCarClass' => false,
          'name' => 'Porsche Carrera Cup Open',
          'singleCarKey' => null,
        ],
        [
          'carClass' => 'GTE',
          'driverCategoryName' => 'Bronze',
          'gameKey' => GameKeys::AutoMobilista2,
          'isSingleCarClass' => false,
          'name' => 'GTE Open',
          'singleCarKey' => null,
        ],
        [
          'carClass' => 'GT4',
          'driverCategoryName' => 'Bronze',
          'gameKey' => GameKeys::AutoMobilista2,
          'isSingleCarClass' => false,
          'name' => 'GT4 Open',
          'singleCarKey' => null,
        ],
      ];

      foreach ($data as $item) {
        $gameId = $wpdb->get_var("SELECT id FROM $gamesTableName WHERE gameKey = '{$item['gameKey']}'");
        $carId = $wpdb->get_var("SELECT id FROM $carsTableName WHERE carKey = '{$item['singleCarKey']}'");
        $driverCategoryId = $wpdb->get_var("SELECT id FROM $driverCategoriesTableName WHERE name = '{$item['driverCategoryName']}'");
        $exists = $wpdb->get_var("SELECT COUNT(*) FROM $tableName WHERE gameId = {$gameId} AND name = '{$item['name']}';");

        if (!$exists) {
          $newItem = [
            'carClass' => $item['carClass'],
            'driverCategoryId' => $driverCategoryId,
            'gameId' => $gameId,
            'isBuiltIn' => true,
            'isSingleCarClass' => $item['isSingleCarClass'],
            'name' => $item['name'],
            'singleCarId' => $carId ?? null,
          ];
          $wpdb->insert($tableName, $newItem);
        }
      }
    }

    public function tableName(string $tablePrefix): string {
      return $tablePrefix . TableNames::EVENT_CLASSES;
    }
  }