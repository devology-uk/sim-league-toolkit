<?php

  namespace SLTK\Database\Repositories;

  use Exception;
  use SLTK\Database\TableNames;
  use stdClass;

  class GameRepository extends RepositoryBase {

    public static function getById(int $gameId): stdClass {
      return self::getRowById(TableNames::GAMES, $gameId);
    }

    /**
     * @throws Exception
     */
    public static function getKey(int $gameId): string {
      $tableName = self::prefixedTableName(TableNames::GAMES);

      $query = "SELECT gameKey FROM {$tableName} WHERE id={$gameId};";

      return self::getValue($query);
    }

    public static function getName(int $gameId): string {
      $row = self::getRowById(TableNames::GAMES, $gameId);

      return $row->name ?? '';
    }

    public static function listAll(): array {
      return self::getResultsFromTable(TableNames::GAMES, null, 'published DESC, name');
    }

    public static function listPublished(): array {
      return self::getResultsFromTable(TableNames::GAMES, 'published = 1', 'published DESC, name');
    }
  }