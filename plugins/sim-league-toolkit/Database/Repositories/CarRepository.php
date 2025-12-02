<?php
  namespace SLTK\Database\Repositories;

  use Exception;
  use SLTK\Database\TableNames;

  class CarRepository extends RepositoryBase {


    /**
     * @throws Exception
     */
    public static function listForGame(int $gameId): array {
      return self::getResultsFromTable(TableNames::CARS, "gameId = $gameId", "name");
    }

    /**
     * @throws Exception
     */
    public static function listCarClassesForGame(int $gameId): array {
      $tableName = self::prefixedTableName(TableNames::CARS);

      $query = "SELECT DISTINCT carClass FROM $tableName WHERE gameId = $gameId";

      return self::getResults($query);
    }
  }