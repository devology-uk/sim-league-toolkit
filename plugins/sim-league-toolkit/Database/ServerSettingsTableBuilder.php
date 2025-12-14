<?php

  namespace SLTK\Database;

  class ServerSettingsTableBuilder extends TableBuilder {

    public function addConstraints(string $tablePrefix): void {
      global $wpdb;
      $tableName = $this->tableName($tablePrefix);
      $parentTableName = $tablePrefix . TableNames::SERVERS;

      $constraintName = 'fk_server_id';
      $existCheckSql = "SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS
                        WHERE TABLE_NAME = '{$tableName}'
                        AND CONSTRAINT_NAME = '{$constraintName}';";

      $exists = $wpdb->get_var($existCheckSql);
      if ($exists === 0) {
        $fkSql = "ALTER TABLE {$tableName}
                    ADD CONSTRAINT {$constraintName} 
                        FOREIGN KEY (serverId) REFERENCES {$parentTableName}(id);";
        $wpdb->query($fkSql);
      }
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