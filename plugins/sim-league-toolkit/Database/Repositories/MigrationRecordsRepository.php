<?php

  namespace SLTK\Database\Repositories;

  use Exception;
  use SLTK\Core\Constants;
  use SLTK\Database\TableNames;

  class MigrationRecordsRepository extends RepositoryBase {
    /**
     * @throws Exception
     */
    public static function countMigrated(string $sourceEntity): int {
      return self::getCount(TableNames::MIGRATION_RECORDS, "sourceEntity = '{$sourceEntity}'");
    }

    /**
     * @throws Exception
     */
    public static function getTargetId(string $sourceEntity, int $sourceId): ?int {
      $value = self::getValue(self::targetIdQuery($sourceEntity, $sourceId));

      return $value === null ? null : (int)$value;
    }

    /**
     * @throws Exception
     */
    public static function isMigrated(string $sourceEntity, int $sourceId): bool {
      return self::simpleExists(TableNames::MIGRATION_RECORDS, "sourceEntity = '{$sourceEntity}' AND sourceId = '{$sourceId}'");
    }

    /**
     * @throws Exception
     */
    public static function recordMigration(string $sourceEntity, int $sourceId, int $targetId): void {
      self::insertWithoutReturn(TableNames::MIGRATION_RECORDS, [
        'sourceEntity' => $sourceEntity,
        'sourceId' => $sourceId,
        'targetId' => $targetId,
        'migratedAt' => current_time(Constants::STANDARD_DATE_TIME_FORMAT),
      ]);
    }

    private static function targetIdQuery(string $sourceEntity, int $sourceId): string {
      $tableName = self::prefixedTableName(TableNames::MIGRATION_RECORDS);

      return "SELECT targetId FROM {$tableName} WHERE sourceEntity = '{$sourceEntity}' AND sourceId = '{$sourceId}';";
    }
  }
