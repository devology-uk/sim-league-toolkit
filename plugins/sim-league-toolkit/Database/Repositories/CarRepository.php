<?php
  namespace SLTK\Database\Repositories;

  use SLTK\Database\TableNames;

  class CarRepository extends RepositoryBase {


    public static function listForGame(int $gameId): array {
      $carsTableName = self::prefixedTableName(TableNames::CARS);
      $carClassesTableName = self::prefixedTableName(TableNames::CAR_CLASSES);

      $query = "SELECT c.*, cc.displayName as className FROM {$carsTableName} c
         INNER JOIN {$carClassesTableName} cc
        ON c.carClassId = cc.id
        WHERE c.gameid = {$gameId}
        ORDER BY c.name;";

      return self::getResults($query);
    }
  }