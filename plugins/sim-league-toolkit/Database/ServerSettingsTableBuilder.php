<?php

  namespace SLTK\Database;

  class ServerSettingsTableBuilder implements TableBuilder {

    /**
     * @inheritDoc
     */
    public function applyAdjustments(string $tablePrefix): void {
      // TODO: Implement applyAdjustments() method.
    }

    /**
     * @inheritDoc
     */
    public function definitionSql(string $tablePrefix, string $charsetCollate): string {

      $serverSettingsTableName = $this->tableName($tablePrefix);
      $serversTableName = $tablePrefix . TableNames::SERVERS;

      return "CREATE TABLE {$serverSettingsTableName} (
                id bigint NOT NULL AUTO_INCREMENT,
                serverId bigint NOT NULL,
                settingName tinytext NOT NULL,
                settingValue mediumtext NOT NULL,
                PRIMARY KEY  (id),
                FOREIGN KEY (serverId) REFERENCES {$serversTableName}(id)
              ) {$charsetCollate};";
    }

    /**
     * @inheritDoc
     */
    public function initialData(string $tablePrefix): void {
      // TODO: Implement initialData() method.
    }

    /**
     * @inheritDoc
     */
    public function tableName(string $tablePrefix): string {
      return $tablePrefix . TableNames::SERVER_SETTINGS;
    }
  }