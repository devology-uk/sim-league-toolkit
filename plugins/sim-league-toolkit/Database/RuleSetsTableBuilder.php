<?php

  namespace SLTK\Database;

  class RuleSetsTableBuilder extends TableBuilder {

    public function addConstraints(string $tablePrefix): void {
    }

    public function applyAdjustments(string $tablePrefix): void {
    }

    public function definitionSql(string $tablePrefix, string $charsetCollate): string {
      $tableName = $this->tableName($tablePrefix);

      return "CREATE TABLE {$tableName} (
        id bigint NOT NULL AUTO_INCREMENT,
        name tinytext NOT NULL,
        description mediumtext NOT NULL,
        type tinytext NOT NULL,            
        PRIMARY KEY  (id)
      ) {$charsetCollate};";
    }

    public function initialData(string $tablePrefix): void {
    }

    public function tableName(string $tablePrefix): string {
      return $tablePrefix . TableNames::RULE_SETS;
    }
  }