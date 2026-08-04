<?php

  namespace SLTK\Database\Repositories;

  use Exception;
  use SLTK\Database\TableNames;
  use stdClass;

  class ChampionshipSessionResultsRepository extends RepositoryBase {
    /**
     * @throws Exception
     */
    public static function add(array $data): int {
      return self::insert(TableNames::CHAMPIONSHIP_SESSION_RESULTS, $data);
    }

    /**
     * @throws Exception
     */
    public static function delete(int $id): void {
      ChampionshipResultPenaltiesRepository::deleteByResultId($id);
      self::deleteById(TableNames::CHAMPIONSHIP_SESSION_RESULTS, $id);
    }

    /**
     * @throws Exception
     */
    public static function getById(int $id): ?stdClass {
      $resultsTable = self::prefixedTableName(TableNames::CHAMPIONSHIP_SESSION_RESULTS);
      $entriesTable = self::prefixedTableName(TableNames::CHAMPIONSHIP_ENTRIES);
      $usersTable = self::prefixedTableName(TableNames::USERS);
      $userMetaTable = self::prefixedTableName(TableNames::USER_META);
      $eventClassesTable = self::prefixedTableName(TableNames::EVENT_CLASSES);

      $query = "SELECT
                  r.*,
                  e.userId as userId,
                  e.eventClassId as eventClassId,
                  u.display_name as memberName,
                  um_rn.meta_value as raceNumber,
                  ec.name as className
              FROM {$resultsTable} r
              INNER JOIN {$entriesTable} e ON r.championshipEntryId = e.id
              LEFT JOIN {$usersTable} u ON e.userId = u.ID
              LEFT JOIN {$userMetaTable} um_rn ON e.userId = um_rn.user_id AND um_rn.meta_key = 'sltk_race_number'
              LEFT JOIN {$eventClassesTable} ec ON e.eventClassId = ec.id
              WHERE r.id = '{$id}';";

      return self::getRow($query);
    }

    /**
     * @return stdClass[]
     * @throws Exception
     */
    public static function listByEventSessionId(int $eventSessionId): array {
      $resultsTable = self::prefixedTableName(TableNames::CHAMPIONSHIP_SESSION_RESULTS);
      $entriesTable = self::prefixedTableName(TableNames::CHAMPIONSHIP_ENTRIES);
      $usersTable = self::prefixedTableName(TableNames::USERS);
      $userMetaTable = self::prefixedTableName(TableNames::USER_META);
      $eventClassesTable = self::prefixedTableName(TableNames::EVENT_CLASSES);

      $query = "SELECT
                  r.*,
                  e.userId as userId,
                  e.eventClassId as eventClassId,
                  u.display_name as memberName,
                  um_rn.meta_value as raceNumber,
                  ec.name as className
              FROM {$resultsTable} r
              INNER JOIN {$entriesTable} e ON r.championshipEntryId = e.id
              LEFT JOIN {$usersTable} u ON e.userId = u.ID
              LEFT JOIN {$userMetaTable} um_rn ON e.userId = um_rn.user_id AND um_rn.meta_key = 'sltk_race_number'
              LEFT JOIN {$eventClassesTable} ec ON e.eventClassId = ec.id
              WHERE r.eventSessionId = '{$eventSessionId}';";

      return self::getResults($query);
    }

    /**
     * @throws Exception
     */
    public static function update(int $id, array $data): void {
      self::updateById(TableNames::CHAMPIONSHIP_SESSION_RESULTS, $id, $data);
    }

    /**
     * @return stdClass[]
     * @throws Exception
     */
    public static function listByUserId(int $userId, int $limit): array {
      return self::listRecent($limit, "e.userId = '{$userId}'");
    }

    /**
     * @return stdClass[]
     * @throws Exception
     */
    public static function listRecent(int $limit, ?string $extraWhere = null): array {
      $resultsTable = self::prefixedTableName(TableNames::CHAMPIONSHIP_SESSION_RESULTS);
      $entriesTable = self::prefixedTableName(TableNames::CHAMPIONSHIP_ENTRIES);
      $usersTable = self::prefixedTableName(TableNames::USERS);
      $eventSessionsTable = self::prefixedTableName(TableNames::EVENT_SESSIONS);
      $championshipEventsTable = self::prefixedTableName(TableNames::CHAMPIONSHIP_EVENTS);
      $championshipsTable = self::prefixedTableName(TableNames::CHAMPIONSHIPS);

      $where = $extraWhere !== null ? "WHERE {$extraWhere}" : '';

      $query = "SELECT
                  r.*,
                  e.userId as userId,
                  u.display_name as memberName,
                  es.name as sessionName,
                  ce.name as eventName,
                  ce.startDateTime as eventDateTime,
                  c.name as championshipName
              FROM {$resultsTable} r
              INNER JOIN {$entriesTable} e ON r.championshipEntryId = e.id
              LEFT JOIN {$usersTable} u ON e.userId = u.ID
              INNER JOIN {$eventSessionsTable} es ON r.eventSessionId = es.id
              INNER JOIN {$championshipEventsTable} ce ON ce.eventRefId = es.eventRefId
              INNER JOIN {$championshipsTable} c ON c.id = ce.championshipId
              {$where}
              ORDER BY ce.startDateTime DESC
              LIMIT {$limit};";

      return self::getResults($query);
    }
  }
