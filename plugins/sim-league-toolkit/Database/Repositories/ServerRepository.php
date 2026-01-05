<?php

  namespace SLTK\Database\Repositories;

  use Exception;
  use SLTK\Database\TableNames;
  use SLTK\Domain\ServerSetting;
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
    public static function addSetting(array $setting): int {
      return self::insert(TableNames::SERVER_SETTINGS, $setting);
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
    public static function deleteSetting(int $id): void {
      self::deleteById(TableNames::SERVER_SETTINGS, $id);
    }

    /**
     * @throws Exception
     */
    public static function getById(int $id): ?stdClass {
      $tableName = self::prefixedTableName(TableNames::SERVERS);
      $gamesTableName = self::prefixedTableName(TableNames::GAMES);
      $platformsTableName = self::prefixedTableName(TableNames::PLATFORMS);

      $query = "SELECT s.*, g.name AS gameName, p.name AS platformName
            FROM {$tableName} s
            INNER JOIN $gamesTableName g
            ON g.id = s.gameid
            INNER JOIN $platformsTableName p
            ON p.id = s.platformid
            WHERE t.id = {$id};";

      return self::getRow($query);
    }

    /**
     * @throws Exception
     */
    public static function getSettingById(int $id): ?stdClass {
      return self::getRowById(TableNames::SERVER_SETTINGS, $id);
    }

    /**
     * @throws Exception
     */
    public static function getSettingByName(int $serverId, string $settingName): ?stdClass {
      $filter = "serverId = {$serverId} and settingName = '{$settingName}'";

      return self::getRowFromTable(TableNames::SERVER_SETTINGS, $filter);
    }

    /**
     * @return stdClass[]
     */
    public static function getSettings(int $serverId): array {

      $filter = "serverId = {$serverId}";

      return self::getResultsFromTable(TableNames::SERVER_SETTINGS, $filter);
    }

    /**
     * @return stdClass[]
     * @throws Exception
     */
    public static function list(): array {
      $query = self::selectAllQuery(TableNames::SERVERS);

      return self::getResults($query);
    }

    /**
     * @return stdClass[]
     */
    public static function listSettings(int $serverId): array {
      $filter = "serverId = {$serverId}";

      return self::getResultsFromTable(TableNames::SERVER_SETTINGS, $filter);
    }


    public static function update(int $id, array $updates): void {
      self::updateById(TableNames::SERVER_SETTINGS, $id, $updates);
    }

    public static function updateSetting(int $id, array $updates): void {
      self::updateById(TableNames::SERVER_SETTINGS, $id, $updates);
    }
  }