<?php

  namespace SLTK\Database\Repositories;

  use SLTK\Database\TableNames;
  use stdClass;

  class GamesRepository extends RepositoryBase {

    public static function getGame(int $gameId): stdClass {
      return self::getRowById(TableNames::GAMES, $gameId);
    }

    public static function getGameKey(int $gameId): string {
      $tableName = self::prefixedTableName(TableNames::GAMES);

      $query = "SELECT gameKey FROM {$tableName} WHERE id={$gameId};";

      return self::getValue($query);
    }

    public static function getName(int $gameId): string {
      $row = self::getRowById(TableNames::GAMES, $gameId);

      return $row->name ?? '';
    }

    /**
     * @return stdClass[]
     */
    public static function listAll(): array {
      return self::getResultsFromTable(TableNames::GAMES);
    }
  }