<?php

  namespace SLTK\Database;


  use SLTK\Core\Constants;

  class CountriesTableBuilder extends TableBuilder {

    public function addConstraints(string $tablePrefix): void {

    }

    public function applyAdjustments(string $tablePrefix): void {

    }

    public function definitionSql(string $tablePrefix, string $charsetCollate): string {
      $tableName = $this->tableName($tablePrefix);

      return "CREATE TABLE {$tableName} (
          id bigint NOT NULL AUTO_INCREMENT,
          name mediumtext NOT NULL,
          alpha2 varchar(2) NOT NULL,
          alpha3 varchar(3) NOT NULL,
          countryCode smallint NOT NULL,
          hide tinyint(1) NOT NULL DEFAULT '0',
          PRIMARY KEY  (id)
        ) {$charsetCollate};";
    }

    public function initialData(string $tablePrefix): void {
      global $wpdb;
      $tableName = $this->tableName($tablePrefix);

      $dataFilePath = Constants::PLUGIN_ROOT_DIR . 'data/iso-countries.csv';

      $handle = fopen($dataFilePath, 'r');
      if ($handle !== false) {

        while (($data = fgetcsv($handle, 1000, ',')) != false) {

          $countryCode = $data[3];

          $existingId = $wpdb->get_var("SELECT id FROM {$tableName} WHERE countryCode = '{$countryCode}';");

          if ($existingId == null) {

            $country = array(
              'name' => $data[0],
              'alpha2' => $data[1],
              'alpha3' => $data[2],
              'countryCode' => $countryCode,
            );

            $wpdb->insert($tableName, $country);
          }
        }

        fclose($handle);
      }
    }

    public function tableName(string $tablePrefix): string {
      return $tablePrefix . TableNames::COUNTRIES;
    }
  }