<?php

  namespace SLTK\Database;

  class RuleSetRulesTableBuilder extends TableBuilder {

    public function addConstraints(string $tablePrefix): void {
      global $wpdb;
      $tableName = $this->tableName($tablePrefix);
      $parentTableName = $tablePrefix . TableNames::RULE_SETS;

      $constraintName = 'fk_ruleset_id';
      $existCheckSql = "SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS
                        WHERE TABLE_NAME = '{$tableName}'
                        AND CONSTRAINT_NAME = '{$constraintName}';";

      $exists = $wpdb->get_var($existCheckSql);
      if ($exists === 0) {
        $fkSql = "ALTER TABLE {$tableName}
                    ADD CONSTRAINT {$constraintName} 
                        FOREIGN KEY (ruleSetId) REFERENCES {$parentTableName}(id);";
        $wpdb->query($fkSql);
      }
    }

    public function applyAdjustments(string $tablePrefix): void {
    }

    public function definitionSql(string $tablePrefix, string $charsetCollate): string {
      $tableName = $this->tableName($tablePrefix);

      return "CREATE TABLE {$tableName} (
        id bigint NOT NULL AUTO_INCREMENT,
        ruleIndex tinytext NULL,
        ruleSetId bigint NOT NULL,
        rule text NOT NULL,
        PRIMARY KEY  (id)
      ) {$charsetCollate};";
    }

    public function initialData(string $tablePrefix): void {
    }

    public function tableName(string $tablePrefix): string {
      return $tablePrefix . TableNames::RULE_SET_RULES;
    }
  }