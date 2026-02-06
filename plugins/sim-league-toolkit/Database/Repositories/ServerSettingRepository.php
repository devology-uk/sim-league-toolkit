<?php

  namespace SLTK\Database\Repositories;

  use Exception;
  use SLTK\Database\TableNames;
  use stdClass;

  class ServerSettingRepository extends DomainRepositoryBase {

    /**
     * @throws Exception
     */
    public static function deleteByServerId(int $serverId): void {
      $tableName = self::prefixedTableName(TableNames::SERVER_SETTINGS);
      $query = "DELETE FROM $tableName WHERE serverId = $serverId";

      self::execute($query);
    }

    /**
     * @throws Exception
     */
    public static function getByName(int $serverId, string $settingName): ?stdClass {
      $filter = "serverId = {$serverId} and settingName = '{$settingName}'";

      return self::getRowFromTable(TableNames::SERVER_SETTINGS, $filter);
    }

    /**
     * @return stdClass[]
     * @throws Exception
     */
    public static function listByServerId(int $serverId): array {
      $filter = "serverId = {$serverId}";

      return self::getResultsFromTable(TableNames::SERVER_SETTINGS, $filter);
    }

  }

  ServerSettingRepository::init(TableNames::SERVER_SETTINGS);