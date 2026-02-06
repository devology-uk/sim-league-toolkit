<?php

  namespace SLTK\Database\Repositories;

  use Exception;
  use SLTK\Database\TableNames;
  use stdClass;

  class ServerRepository extends RepositoryBase {

    /**
     * @throws Exception
     */
    public static function add(array $server): int {
      return self::insert(TableNames::SERVERS, $server);
    }

    /**
     * @throws Exception
     */
    public static function delete(int $id): void {
      self::deleteById(TableNames::SERVERS, $id);
    }

    /**
     * @throws Exception
     */
    public static function getById(int $id): ?stdClass {
      $tableName = self::prefixedTableName(TableNames::SERVERS);
      $gamesTableName = self::prefixedTableName(TableNames::GAMES);
      $platformsTableName = self::prefixedTableName(TableNames::PLATFORMS);

      $query = "SELECT s.*, g.name AS gameName, g.gameKey, p.name AS platformName
            FROM {$tableName} s
            INNER JOIN $gamesTableName g
            ON g.id = s.gameid
            INNER JOIN $platformsTableName p
            ON p.id = s.platformid
            WHERE s.id = {$id};";

      return self::getRow($query);
    }

    /**
     * @return stdClass[]
     * @throws Exception
     */
    public static function list(): array {
      $tableName = self::prefixedTableName(TableNames::SERVERS);
      $gamesTableName = self::prefixedTableName(TableNames::GAMES);
      $platformsTableName = self::prefixedTableName(TableNames::PLATFORMS);

      $query = "SELECT s.*, g.name AS gameName, g.gameKey, p.name AS platformName
            FROM {$tableName} s
            INNER JOIN $gamesTableName g
            ON g.id = s.gameid
            INNER JOIN $platformsTableName p
            ON p.id = s.platformid;";


      return self::getResults($query);
    }

    /**
     * @return stdClass[]
     * @throws Exception
     */
    public static function listSettings(int $serverId): array {
      $filter = "serverId = {$serverId}";

      return self::getResultsFromTable(TableNames::SERVER_SETTINGS, $filter);
    }


    /**
     * @throws Exception
     */
    public static function update(int $id, array $updates): void {
      self::updateById(TableNames::SERVERS, $id, $updates);
    }
  }