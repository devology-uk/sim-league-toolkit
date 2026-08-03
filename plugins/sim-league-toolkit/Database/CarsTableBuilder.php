<?php

  namespace SLTK\Database;

  use SLTK\Core\Constants;

  class CarsTableBuilder extends TableBuilder {
    public function addConstraints(string $tablePrefix): void {
      global $wpdb;
      $tableName = $this->tableName($tablePrefix);
      $gamesTableName = $tablePrefix . TableNames::GAMES;

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
    }

    public function applyAdjustments(string $tablePrefix): void {

    }

    public function definitionSql(string $tablePrefix, string $charsetCollate): string {
      $tableName = $this->tableName($tablePrefix);

      return "CREATE TABLE {$tableName} (
          id bigint NOT NULL AUTO_INCREMENT,
          gameId bigint NOT NULL,
          carClass tinytext NOT NULL,
          carKey tinytext NOT NULL,
          name tinytext NOT NULL,
          year mediumint NOT NULL,
          manufacturer tinytext NOT NULL,
          dlcPack tinytext NULL,
          PRIMARY KEY  (id)
        ) {$charsetCollate};";
    }

    public function initialData(string $tablePrefix): void {
      $this->loadCars($tablePrefix, 'acc-cars.csv', 'ACC');
      $this->loadCars($tablePrefix, 'ams2-cars.csv', 'AMS2');
      $this->loadCars($tablePrefix, 'lmu-cars.csv', 'LMU');
    }

    public function tableName(string $tablePrefix): string {
      return $tablePrefix . TableNames::CARS;
    }


    private function loadCars(string $tablePrefix, string $fileName, string $gameKey): void {
      global $wpdb;

      $carsTableName = $this->tableName($tablePrefix);
      $gamesTableName = $tablePrefix . TableNames::GAMES;

      $dataFilePath = Constants::PLUGIN_ROOT_DIR . 'data/' . $fileName;

      $handle = fopen($dataFilePath, 'r');
      if ($handle !== false) {

        while (($data = fgetcsv($handle, 1000, ',', '"', '\\')) != false) {

          $carClass = $data[0];
          $carKey = $data[1];
          $dlcPack = !empty($data[5]) ? $data[5] : null;

          $gameId = $wpdb->get_var("SELECT id FROM {$gamesTableName} WHERE gameKey = '$gameKey'");

          $this->upsertSeedRow($carsTableName, ['gameId' => $gameId, 'carKey' => $carKey, 'carClass' => $carClass], [
            'name' => $data[2],
            'year' => $data[3],
            'manufacturer' => $data[4],
            'dlcPack' => $dlcPack,
          ]);
        }

        fclose($handle);
      }
    }
  }