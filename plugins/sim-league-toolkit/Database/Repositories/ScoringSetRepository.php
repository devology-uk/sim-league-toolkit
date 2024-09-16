<?php

  namespace SLTK\Database\Repositories;

  use SLTK\Database\TableNames;
  use SLTK\Domain\ScoringSet;

  class ScoringSetRepository extends RepositoryBase {

    /**
     * @param int $id
     *
     * @return ScoringSet|null
     */
    public static function getById(int $id): ScoringSet|null {

      $row = self::getRowById(TableNames::SCORING_SETS, $id);
      if($row) {
        return new ScoringSet($row);
      }

      return null;
    }

    /**
     * @return ScoringSet[] Collection of all scoring sets in the database
     */
    public static function list(): array {
      $queryResults = self::getResultsFromTable(TableNames::SCORING_SETS);

      return self::mapScoringSets($queryResults);
    }

    private static function mapScoringSets(array $queryResults): array {
      $results = array();

      foreach($queryResults as $item) {
        $results[] = new ScoringSet($item);
      }

      return $results;
    }
  }