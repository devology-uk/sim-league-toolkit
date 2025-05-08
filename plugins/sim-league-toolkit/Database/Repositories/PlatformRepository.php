<?php

  namespace SLTK\Database\Repositories;

  use Exception;
  use SLTK\Database\TableNames;
  use stdClass;

  class PlatformRepository extends RepositoryBase {

    public static function getName(int $id): string {
      $row = self::getRowById(TableNames::PLATFORMS, $id);

      return $row->name ?? '';
    }

    /**
     * @return stdClass[]
     */
    public static function listAll(): array {
      return self::getResultsFromTable(TableNames::PLATFORMS);
    }

    /**
     * @return stdClass[]
     * @throws Exception
     */
    public static function listForGame(int $gameId): array {
      $platformsTableName = self::prefixedTableName(TableNames::PLATFORMS);
      $gamePlatformsTableName = self::prefixedTableName(TableNames::GAME_PLATFORMS);

      $query = "SELECT p.*
                FROM {$platformsTableName} p
                INNER JOIN {$gamePlatformsTableName} pg
                    ON pg.platformId = p.id
                WHERE pg.gameId = {$gameId};";

      return self::getResults($query);
    }

    /**
     * @return int[]
     * @throws Exception
     */
    public static function listIdsForGame(int $gameId): array {
      $gamePlatformsTableName = self::prefixedTableName(TableNames::GAME_PLATFORMS);

      $query = "SELECT platformId
                FROM {$gamePlatformsTableName}
                WHERE gameId = {$gameId};";

      $queryResults = self::getResults($query);

      $results = [];
      foreach ($queryResults as $row) {
        $results[] = (int)$row->platformId;
      }
      return $results;
    }
  }