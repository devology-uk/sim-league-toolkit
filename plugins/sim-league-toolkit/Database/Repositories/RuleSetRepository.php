<?php

  namespace SLTK\Database\Repositories;

  use Exception;
  use SLTK\Database\TableNames;
  use stdClass;

  class RuleSetRepository extends RepositoryBase {

    /**
     * @throws Exception
     */
    public static function add(array $ruleSet): int {
      return self::insert(TableNames::RULE_SETS, $ruleSet);
    }

    /**
     * @throws Exception
     */
    public static function addRule(array $rule): int {
      return self::insert(TableNames::RULE_SET_RULES, $rule);
    }

    public static function getById(int $id): stdClass|null {
      return self::getRowById(TableNames::RULE_SETS, $id);
    }

    /**
     * @throws Exception
     */
    public static function getRuleById(int $id): stdClass|null {
      $tableName = self::prefixedTableName(TableNames::RULE_SET_RULES);
      $query = "SELECT * FROM {$tableName} WHERE id = {$id}";

      return self::getRow($query);
    }

    /**
     * @return stdClass[]
     */
    public static function list(): array {
      return self::getResultsFromTable(TableNames::RULE_SETS);
    }

    /**
     * @return stdClass[]
     * @throws Exception
     */
    public static function listRules(int $ruleSetId): array {
      $tableName = self::prefixedTableName(TableNames::RULE_SET_RULES);

      $query = "SELECT * FROM {$tableName} WHERE ruleSetId = {$ruleSetId}";

      return self::getResults($query);
    }

    public static function update(int $id, array $updates): void {
      self::updateById(TableNames::RULE_SETS, $id, $updates);
    }

    public static function updateRule(int $id, array $updates): void {
      self::updateById(TableNames::RULE_SET_RULES, $id, $updates);
    }
  }