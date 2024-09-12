<?php

  namespace SLTK\Database\Repositories;

  use SLTK\Database\TableNames;
  use SLTK\Domain\Server;
  use SLTK\Domain\ServerSetting;

  /**
   * Provides access to the server related data in the database
   */
  class ServerRepository extends RepositoryBase {

    /**
     * @param Server $server The server instance to be added
     *
     * @return int The id of the server assigned by the database
     */
    public static function add(Server $server): int {
      return self::insert(TableNames::SERVERS, $server->toArray());
    }

    /**
     * Adds a setting for a server
     *
     * @param ServerSetting $setting The setting to be added
     *
     * @return int
     */
    public static function addSetting(ServerSetting $setting): int {
      return self::insert(TableNames::SERVER_SETTINGS, $setting->toArray());
    }

    /**
     * @param int $id The id of a server to get
     *
     * @return Server Instance of server populated from database
     */
    public static function getById(int $id): Server {
      $row = self::getRowById(TableNames::SERVERS, $id);

      return new Server($row);
    }

    /**
     * @param int $id The id of the target setting
     *
     * @return ServerSetting|null The target setting or null
     */
    public static function getSettingById(int $id): ?ServerSetting {
      $row = self::getRowById(TableNames::SERVER_SETTINGS, $id);

      if(isset($row->id)) {
        return new ServerSetting($row);
      }

      return null;
    }

    /**
     * @return array Collection of all servers in the database
     */
    public static function list(): array {
      $queryResults = self::getResultsFromTable(TableNames::SERVERS);

      return self::mapServers($queryResults);
    }

    /**
     * @param int $serverId The id of the target server
     *
     * @return array Collection of server settings for the target server or empty array
     */
    public static function listSettings(int $serverId): array {
      $filter = "serverId = {$serverId}";

      $queryResults = self::getResultsFromTable(TableNames::SERVER_SETTINGS, $filter);
      if(!count($queryResults)) {
        return [];
      }

      return self::mapServerSettings($queryResults);
    }

    /**
     * Saves changes to a server setting
     *
     * @param ServerSetting $setting The setting to be updated
     *
     * @return void
     */
    public static function updateSetting(ServerSetting $setting): void {
      $existingSetting = self::getSettingById($setting->id);

      if(!isset($existingSetting->id) || $existingSetting->settingValue === $setting->settingValue) {
        return;
      }

      $updates = [
        'settingValue' => $setting->settingValue
      ];

      self::updateById(TableNames::SERVER_SETTINGS, $setting->id, $updates);
    }

    private static function mapServerSettings(array $queryResults): array {
      $results = array();

      foreach($queryResults as $item) {
        $results[] = new ServerSetting($item);
      }

      return $results;
    }

    private static function mapServers(array $queryResults): array {
      $results = array();

      foreach($queryResults as $item) {
        $results[] = new Server($item);
      }

      return $results;
    }
  }