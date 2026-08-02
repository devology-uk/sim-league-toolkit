<?php

  namespace SLTK\Database\Repositories;

  use Exception;
  use SLTK\Database\TableNames;
  use stdClass;

  class StandaloneSessionResultsRepository extends RepositoryBase {
    /**
     * @throws Exception
     */
    public static function add(array $data): int {
      return self::insert(TableNames::STANDALONE_SESSION_RESULTS, $data);
    }

    /**
     * @throws Exception
     */
    public static function delete(int $id): void {
      StandaloneResultPenaltiesRepository::deleteByResultId($id);
      self::deleteById(TableNames::STANDALONE_SESSION_RESULTS, $id);
    }

    /**
     * @throws Exception
     */
    public static function getById(int $id): ?stdClass {
      $resultsTable = self::prefixedTableName(TableNames::STANDALONE_SESSION_RESULTS);
      $entriesTable = self::prefixedTableName(TableNames::STANDALONE_EVENT_ENTRIES);
      $usersTable = self::prefixedTableName(TableNames::USERS);
      $userMetaTable = self::prefixedTableName(TableNames::USER_META);
      $eventClassesTable = self::prefixedTableName(TableNames::EVENT_CLASSES);

      $query = "SELECT
                  r.*,
                  u.display_name as memberName,
                  um_rn.meta_value as raceNumber,
                  ec.name as className
              FROM {$resultsTable} r
              INNER JOIN {$entriesTable} e ON r.standaloneEventEntryId = e.id
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
      $resultsTable = self::prefixedTableName(TableNames::STANDALONE_SESSION_RESULTS);
      $entriesTable = self::prefixedTableName(TableNames::STANDALONE_EVENT_ENTRIES);
      $usersTable = self::prefixedTableName(TableNames::USERS);
      $userMetaTable = self::prefixedTableName(TableNames::USER_META);
      $eventClassesTable = self::prefixedTableName(TableNames::EVENT_CLASSES);

      $query = "SELECT
                  r.*,
                  u.display_name as memberName,
                  um_rn.meta_value as raceNumber,
                  ec.name as className
              FROM {$resultsTable} r
              INNER JOIN {$entriesTable} e ON r.standaloneEventEntryId = e.id
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
      self::updateById(TableNames::STANDALONE_SESSION_RESULTS, $id, $data);
    }
  }
