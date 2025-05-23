<?php

  namespace SLTK\Database\Repositories;

  use SLTK\Database\TableNames;

  class DriverCategoryRepository extends RepositoryBase {


    public static function listForGame(int $gameId): array {
      $filter = 'gameId =' . $gameId;

      return self::getResultsFromTable(TableNames::DRIVER_CATEGORIES, $filter);
    }
  }