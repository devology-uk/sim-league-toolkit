<?php

  namespace SLTK\Database\Repositories;

  use Exception;
  use SLTK\Database\TableNames;
  use stdClass;

  class TrophiesRepository extends RepositoryBase {
    /**
     * @throws Exception
     */
    public static function add(array $data): int {
      return self::insert(TableNames::TROPHIES, $data);
    }

    /**
     * @throws Exception
     */
    public static function delete(int $id): void {
      self::deleteById(TableNames::TROPHIES, $id);
    }

    /**
     * @throws Exception
     */
    public static function deleteByScope(string $scope, int $scopeId): void {
      self::deleteFromTable(TableNames::TROPHIES, "scope = '{$scope}' AND scopeId = '{$scopeId}'");
    }

    /**
     * @return stdClass[]
     * @throws Exception
     */
    public static function listByScope(string $scope, int $scopeId): array {
      $trophiesTable = self::prefixedTableName(TableNames::TROPHIES);
      $usersTable = self::prefixedTableName(TableNames::USERS);
      $eventClassesTable = self::prefixedTableName(TableNames::EVENT_CLASSES);
      $eventSessionsTable = self::prefixedTableName(TableNames::EVENT_SESSIONS);

      $query = "SELECT
                  t.*,
                  u.display_name as memberName,
                  ec.name as className,
                  es.name as sessionName
              FROM {$trophiesTable} t
              LEFT JOIN {$usersTable} u ON t.memberId = u.ID
              LEFT JOIN {$eventClassesTable} ec ON t.eventClassId = ec.id
              LEFT JOIN {$eventSessionsTable} es ON t.eventSessionId = es.id
              WHERE t.scope = '{$scope}' AND t.scopeId = '{$scopeId}';";

      return self::getResults($query);
    }
  }
