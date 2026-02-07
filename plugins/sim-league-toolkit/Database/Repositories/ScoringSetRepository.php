<?php

  namespace SLTK\Database\Repositories;

  use Exception;
  use SLTK\Database\TableNames;
  use stdClass;

  class ScoringSetRepository extends RepositoryBase {

    /**
     * @throws Exception
     */
    public static function add(array $scoringSet): int {
      return self::insert(TableNames::SCORING_SETS, $scoringSet);
    }

    /**
     * @throws Exception
     */
    public static function addScore(array $score): int {
      return self::insert(TableNames::SCORING_SET_SCORES, $score);
    }

    /**
     * @throws Exception
     */
    public static function delete(int $id): void {
      self::deleteById(TableNames::SCORING_SETS, $id);
    }

    /**
     * @throws Exception
     */
    public static function deleteScore(int $id): void {
      self::deleteById(TableNames::SCORING_SET_SCORES, $id);

    }

    /**
     * @throws Exception
     */
    public static function getById(int $id): stdClass|null {
      return self::getRowById(TableNames::SCORING_SETS, $id);
    }

    /**
     * @throws Exception
     */
    public static function getScoreById(int $id): stdClass|null {
      return self::getRowById(TableNames::SCORING_SET_SCORES, $id);
    }

    /**
     * @return stdClass[]
     * @throws Exception
     */
    public static function list(): array {
      $tableName = self::prefixedTableName(TableNames::SCORING_SETS);
      $championshipShipsTableName = self::prefixedTableName(TableNames::CHAMPIONSHIPS);
//      $eventsTableName = self::prefixedTableName(TableNames::EVENTS);

      $query = "SELECT ss.*,
                (SELECT COUNT(*) FROM $championshipShipsTableName c WHERE ss.id = c.scoringSetId) as isInUse 
                FROM {$tableName} ss
                ORDER BY isBuiltIn, name";

      return self::getResults($query);
    }

    /**
     * @return stdClass[]
     * @throws Exception
     */
    public static function listScores(int $scoringSetId): array {
      $filter = "scoringSetId = $scoringSetId";
      return self::getResultsFromTable(TableNames::SCORING_SET_SCORES, $filter, 'position');
    }

    /**
     * @throws Exception
     */
    public static function update(int $id, array $updates): void {
      self::updateById(TableNames::SCORING_SETS, $id, $updates);
    }

    /**
     * @throws Exception
     */
    public static function updateScore(int $id, array $updates): void {
      self::updateById(TableNames::SCORING_SET_SCORES, $id, $updates);
    }
  }