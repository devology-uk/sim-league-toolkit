<?php

  namespace SLTK\Database;


  use SLTK\Core\Constants;

  class TracksTableBuilder implements TableBuilder{

    public function applyAdjustments(string $tablePrefix): void {

    }

    public function addConstraints(string $tablePrefix): void {

    }

    public function definitionSql(string $tablePrefix, string $charsetCollate): string {
      $tableName = $this->tableName($tablePrefix);

      return "CREATE TABLE {$tableName} (
          id bigint NOT NULL AUTO_INCREMENT,
          trackKey tinytext NOT NULL,
          shortName tinytext NOT NULL,
          fullName mediumtext NOT NULL,
          country tinytext NOT NULL,
          countryCode tinytext NOT NULL,
          latitude double(10,6) NOT NULL,
          longitude double(10,6) NOT NULL,
          PRIMARY KEY  (id)
        ) {$charsetCollate};";
    }

    public function initialData(string $tablePrefix): void {
      global $wpdb;
      $tableName = $this->tableName($tablePrefix);

      $dataFilePath = Constants::PLUGIN_ROOT_DIR . 'data/tracks.csv';

      $handle = fopen($dataFilePath, 'r');
      if ($handle !== false) {

        while (($data = fgetcsv($handle, 1000, ',')) != false) {

          $trackKey = $data[0];

          $existingId = $wpdb->get_var("SELECT id FROM {$tableName} WHERE trackKey = '{$trackKey}';");

          if ($existingId == null) {

            $track = array(
              'trackKey' => $trackKey,
              'shortName' => $data[1],
              'fullName' => $data[2],
              'country' => $data[3],
              'countryCode' => $data[4],
              'latitude'  => $data[5],
              'longitude' => $data[6],
            );

            $wpdb->insert($tableName, $track);
          }
        }

        fclose($handle);
      }
    }

    public function tableName(string $tablePrefix): string {
      return $tablePrefix . TableNames::TRACKS;
    }
  }