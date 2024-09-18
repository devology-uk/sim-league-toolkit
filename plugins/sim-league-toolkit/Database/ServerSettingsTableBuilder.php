<?php

  namespace SLTK\Database;

  class ServerSettingsTableBuilder implements TableBuilder {

    public function applyAdjustments(string $tablePrefix): void {}

    public function definitionSql(string $tablePrefix, string $charsetCollate): string {

      $tableName = $this->tableName($tablePrefix);
      $parentTableName = $tablePrefix . TableNames::SERVERS;

      return "CREATE TABLE {$tableName} (
                id bigint NOT NULL AUTO_INCREMENT,
                serverId bigint NOT NULL,
                settingName tinytext NOT NULL,
                settingValue mediumtext NOT NULL,
                PRIMARY KEY  (id),
                FOREIGN KEY (serverId) REFERENCES {$parentTableName}(id)
              ) {$charsetCollate};";
    }

    public function initialData(string $tablePrefix): void {}

    public function tableName(string $tablePrefix): string {
      return $tablePrefix . TableNames::SERVER_SETTINGS;
    }
  }