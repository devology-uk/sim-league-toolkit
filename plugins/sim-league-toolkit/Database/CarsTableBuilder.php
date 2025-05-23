<?php

  namespace SLTK\Database;

  use SLTK\Core\Constants;
  use SLTK\Core\GameKeys;

  class CarsTableBuilder implements TableBuilder {
    public function addConstraints(string $tablePrefix): void {
      global $wpdb;
      $tableName = $this->tableName($tablePrefix);
      $gamesTableName = $tablePrefix . TableNames::GAMES;
      $carClasssTableName = $tablePrefix . TableNames::CAR_CLASSES;

      $gameConstraintName = 'fk_cars_gameId';
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

      $carClassConstraintName = 'fk_cars_carClassId';
      $carClassExistsCheckSql = "SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS
                        WHERE TABLE_NAME = '{$tableName}'
                        AND CONSTRAINT_NAME = '{$carClassConstraintName}';";

      $carClassExists = (int)$wpdb->get_var($carClassExistsCheckSql);
      if (!$carClassExists) {
        $fkSql = "ALTER TABLE {$tableName}
                    ADD CONSTRAINT {$carClassConstraintName} 
                        FOREIGN KEY (carClassId) REFERENCES {$carClasssTableName}(id);";
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
          carClassId bigint NOT NULL,
          carKey tinytext NOT NULL,
          name tinytext NOT NULL,
          year mediumint NOT NULL,
          manufacturer tinytext NOT NULL,
          PRIMARY KEY  (Id)
        ) {$charsetCollate};";
    }

    public function initialData(string $tablePrefix): void {
      global $wpdb;
      $tableName = $this->tableName($tablePrefix);
      $gamesTableName = $tablePrefix . TableNames::GAMES;
      $carClassesTableName = $tablePrefix . TableNames::CAR_CLASSES;

      $dataFilePath = Constants::PLUGIN_ROOT_DIR . 'data/cars.csv';

      $handle = fopen($dataFilePath, 'r');
      if ($handle !== false) {

        while (($data = fgetcsv($handle, 1000, ',')) != false) {

          $gameKey = $data[0];
          $carClassKey = $data[1];
          $carKey = $data[2];

          $gameId = $wpdb->get_var("SELECT id FROM {$gamesTableName} WHERE gameKey = '{$gameKey}'");
          $carClassId = $wpdb->get_var("SELECT id FROM {$carClassesTableName} WHERE name = '{$carClassKey}'");

          $existingId = $wpdb->get_var("SELECT id FROM {$tableName} WHERE gameId = {$gameId} AND carClassId = {$carClassId} AND carKey = '{$carKey}';");

          if ($existingId == null) {
            $car = [
              'gameId' => $gameId,
              'carClassId' => $carClassId,
              'carKey' => $carKey,
              'name' => $data[3],
              'year' => $data[4],
              'manufacturer' => $data[5]
            ];

            $wpdb->insert($tableName, $car);
          }
        }

        fclose($handle);
      }


    }

    public function tableName(string $tablePrefix): string {
      return $tablePrefix . TableNames::CARS;
    }
  }