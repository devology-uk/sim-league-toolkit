<?php

  namespace SLTK\Database;

  class RuleSetRulesTableBuilder implements TableBuilder {

    public function applyAdjustments(string $tablePrefix): void {}

    public function definitionSql(string $tablePrefix, string $charsetCollate): string {
      $tableName = $this->tableName($tablePrefix);
      $parentTableName = $tablePrefix . TableNames::RULE_SETS;

      return "CREATE TABLE {$tableName} (
        id bigint NOT NULL AUTO_INCREMENT,
        ruleSetId bigint NOT NULL,
        rule text NOT NULL,            
        PRIMARY KEY  (id),
        FOREIGN KEY (ruleSetId) REFERENCES {$parentTableName}(id)
      ) {$charsetCollate};";
    }

    public function initialData(string $tablePrefix): void {}

    public function tableName(string $tablePrefix): string {
      return $tablePrefix . TableNames::RULE_SET_RULES;
    }
  }