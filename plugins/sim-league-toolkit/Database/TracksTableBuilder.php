<?php

  namespace SLTK\Database;


  class TracksTableBuilder implements TableBuilder{

    public function applyAdjustments(string $tablePrefix): void {

    }

    public function addConstraints(string $tablePrefix): void {

    }

    public function definitionSql(string $tablePrefix, string $charsetCollate): string {
      $tableName = $this->tableName($tablePrefix);

      return "CREATE TABLE {$tableName} (
          id bigint NOT NULL AUTO_INCREMENT,
          shortName tinytext NOT NULL,
          fullName mediumtext NOT NULL,
          country tinytext NOT NULL,
          countryCode tinytext NOT NULL,
          PRIMARY KEY  (id)
        ) {$charsetCollate};";
    }

    public function initialData(string $tablePrefix): void {

    }

    public function tableName(string $tablePrefix): string {
      return $tablePrefix . TableNames::TRACKS;
    }
  }