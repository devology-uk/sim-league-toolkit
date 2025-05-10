<?php
namespace SLTK\Database\Repositories;

use SLTK\Database\TableNames;

class CarClassRepository extends RepositoryBase {


  public static function listForGame(int $gameId): array {
    $filter = 'gameId =' . $gameId;
    return self::getResultsFromTable(TableNames::CAR_CLASSES, $filter);
  }
}