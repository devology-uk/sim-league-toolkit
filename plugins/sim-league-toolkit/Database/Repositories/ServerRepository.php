<?php

  namespace SLTK\Database\Repositories;

  use Exception;
  use SLTK\Database\TableNames;
  use SLTK\Domain\Server;
  use SLTK\Domain\ServerSetting;

  class ServerRepository extends RepositoryBase {

    /**
     * @throws Exception
     */
    public static function add(Server $server): int {
      return self::insert(TableNames::SERVERS, $server->toArray());
    }

    /**
     * @throws Exception
     */
    public static function addSetting(ServerSetting $setting): int {
      return self::insert(TableNames::SERVER_SETTINGS, $setting->toArray(false));
    }

    public static function getById(int $id): Server {
      $row = self::getRowById(TableNames::SERVERS, $id);

      return new Server($row);
    }

    public static function getSettingById(int $id): ?ServerSetting {
      $row = self::getRowById(TableNames::SERVER_SETTINGS, $id);

      if (isset($row->id)) {
        return new ServerSetting($row);
      }

      return null;
    }

    /**
     * @return Server[] Collection of all servers in the database
     */
    public static function list(): array {
      $queryResults = self::getResultsFromTable(TableNames::SERVERS);

      return self::mapServers($queryResults);
    }

    /**
     * @return ServerSetting[] Collection of server settings for the target server or empty array
     */
    public static function listSettings(int $serverId): array {
      $filter = "serverId = {$serverId}";

      $queryResults = self::getResultsFromTable(TableNames::SERVER_SETTINGS, $filter);
      if (!count($queryResults)) {
        return [];
      }

      return self::mapServerSettings($queryResults);
    }

    public static function updateSetting(ServerSetting $setting): void {
      $existingSetting = self::getSettingById($setting->id);

      if (!isset($existingSetting->id) || $existingSetting->settingValue === $setting->settingValue) {
        return;
      }

      $updates = [
        'settingValue' => $setting->settingValue
      ];

      self::updateById(TableNames::SERVER_SETTINGS, $setting->id, $updates);
    }

    private static function mapServerSettings(array $queryResults): array {
      $results = array();

      foreach ($queryResults as $item) {
        $results[] = new ServerSetting($item);
      }

      return $results;
    }

    private static function mapServers(array $queryResults): array {
      $results = array();

      foreach ($queryResults as $item) {
        $results[] = new Server($item);
      }

      return $results;
    }
  }