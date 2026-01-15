<?php

  namespace SLTK\Database;

  class ServerSettingsTableBuilder extends TableBuilder {

    public function addConstraints(string $tablePrefix): void {
      $this->addSimpleForeignKey($tablePrefix, TableNames::SERVERS, 'serverId');
    }

    public function applyAdjustments(string $tablePrefix): void {
    }

    public function definitionSql(string $tablePrefix, string $charsetCollate): string {

      $tableName = $this->tableName($tablePrefix);

      return "CREATE TABLE {$tableName} (
                id bigint NOT NULL AUTO_INCREMENT,
                serverId bigint NOT NULL,
                settingName tinytext NOT NULL,
                settingValue mediumtext NOT NULL,
                PRIMARY KEY  (id)
              ) {$charsetCollate};";
    }

    public function initialData(string $tablePrefix): void {
    }

    public function tableName(string $tablePrefix): string {
      return $tablePrefix . TableNames::SERVER_SETTINGS;
    }
  }