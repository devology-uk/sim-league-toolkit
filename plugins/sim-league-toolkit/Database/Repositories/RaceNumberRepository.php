<?php

  namespace SLTK\Database\Repositories;

  use SLTK\Database\TableNames;
  use SLTK\Domain\RaceNumber;

  /**
   * Provides database access for Race Numbers
   */
  class RaceNumberRepository extends RepositoryBase {

    /**
     * @return array Collection of allocated race numbers
     */
    public static function listActiveAllocations(): array {
      $queryResults = self::getResultsFromTable(TableNames::RACE_NUMBERS);

      return self::mapRaceNumbers($queryResults);
    }

    private static function mapRaceNumbers(array $queryResults): array {
      $results = array();

      foreach($queryResults as $item) {
        $results[] = new RaceNumber($item);
      }

      return $results;
    }

  }