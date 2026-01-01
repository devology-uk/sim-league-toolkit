<?php

  namespace SLTK\Database;

  class UserProfileTableBuilder extends TableBuilder {

    public function addConstraints(string $tablePrefix): void {
      $this->addSimpleForeignKey($tablePrefix, TableNames::USERS, 'fk_user_profiles_userId', 'userId', 'ID');
      $this->addSimpleForeignKey($tablePrefix, TableNames::COUNTRIES, 'fk_user_profiles_countryId', 'countryId');
    }

    public function applyAdjustments(string $tablePrefix): void {

    }

    public function definitionSql(string $tablePrefix, string $charsetCollate): string {
      $tableName = $this->tableName($tablePrefix);

      return "CREATE TABLE {$tableName} (
          userId bigint unsigned NOT NULL,
          avatarUrl mediumtext NULL,      
          countryId bigint NOT NULL,    
          playStationId tinytext NOT NULL,
          raceNumber smallint NOT NULL,
          steamId tinytext NOT NULL,       
          PRIMARY KEY  (userId)
        ) {$charsetCollate};";
    }

    public function initialData(string $tablePrefix): void {

    }

    public function tableName(string $tablePrefix): string {
      return $tablePrefix . TableNames::USER_PROFILE;
    }
  }