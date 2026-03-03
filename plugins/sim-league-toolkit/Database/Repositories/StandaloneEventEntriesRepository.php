<?php

namespace SLTK\Database\Repositories;

use Exception;
use SLTK\Database\TableNames;
use stdClass;

class StandaloneEventEntriesRepository extends RepositoryBase {

    /**
     * @throws Exception
     */
    public static function add(array $data): int {
        return self::insert(TableNames::STANDALONE_EVENT_ENTRIES, $data);
    }

    /**
     * @throws Exception
     */
    public static function delete(int $id): void {
        self::deleteById(TableNames::STANDALONE_EVENT_ENTRIES, $id);
    }

    /**
     * @throws Exception
     */
    public static function getById(int $id): ?stdClass {
        $entriesTable = self::prefixedTableName(TableNames::STANDALONE_EVENT_ENTRIES);
        $usersTable = self::prefixedTableName(TableNames::USERS);
        $userMetaTable = self::prefixedTableName(TableNames::USER_META);
        $eventClassesTable = self::prefixedTableName(TableNames::EVENT_CLASSES);
        $carsTable = self::prefixedTableName(TableNames::CARS);

        $query = "SELECT
                    e.*,
                    u.display_name as memberName,
                    um_fn.meta_value as firstName,
                    um_ln.meta_value as lastName,
                    um_rn.meta_value as raceNumber,
                    ec.name as className,
                    c.name as carName
                FROM {$entriesTable} e
                LEFT JOIN {$usersTable} u ON e.userId = u.ID
                LEFT JOIN {$userMetaTable} um_fn ON e.userId = um_fn.user_id AND um_fn.meta_key = 'first_name'
                LEFT JOIN {$userMetaTable} um_ln ON e.userId = um_ln.user_id AND um_ln.meta_key = 'last_name'
                LEFT JOIN {$userMetaTable} um_rn ON e.userId = um_rn.user_id AND um_rn.meta_key = 'sltk_race_number'
                LEFT JOIN {$eventClassesTable} ec ON e.eventClassId = ec.id
                LEFT JOIN {$carsTable} c ON e.carId = c.id
                WHERE e.id = '{$id}';";

        return self::getRow($query);
    }

    /**
     * @throws Exception
     */
    public static function getConfirmedCountForClass(int $standaloneEventId, int $eventClassId): int {
        return (int)self::getCount(
            TableNames::STANDALONE_EVENT_ENTRIES,
            "standaloneEventId = {$standaloneEventId} AND eventClassId = {$eventClassId} AND status = 'confirmed'"
        );
    }

    /**
     * @throws Exception
     */
    public static function promoteFromWaitlist(int $standaloneEventId, int $eventClassId, int $maxEntrants): void {
        $confirmedCount = self::getConfirmedCountForClass($standaloneEventId, $eventClassId);

        if ($confirmedCount >= $maxEntrants) {
            return;
        }

        $tableName = self::prefixedTableName(TableNames::STANDALONE_EVENT_ENTRIES);
        $row = self::getRow(
            "SELECT id FROM {$tableName}
             WHERE standaloneEventId = {$standaloneEventId}
             AND eventClassId = {$eventClassId}
             AND status = 'waitlisted'
             ORDER BY created_at ASC
             LIMIT 1;"
        );

        if ($row) {
            self::updateById(TableNames::STANDALONE_EVENT_ENTRIES, (int)$row->id, ['status' => 'confirmed']);
        }
    }

    /**
     * @return stdClass[]
     * @throws Exception
     */
    public static function listByStandaloneEventId(int $standaloneEventId): array {
        $entriesTable = self::prefixedTableName(TableNames::STANDALONE_EVENT_ENTRIES);
        $usersTable = self::prefixedTableName(TableNames::USERS);
        $userMetaTable = self::prefixedTableName(TableNames::USER_META);
        $eventClassesTable = self::prefixedTableName(TableNames::EVENT_CLASSES);
        $carsTable = self::prefixedTableName(TableNames::CARS);

        $query = "SELECT
                    e.*,
                    u.display_name as memberName,
                    um_fn.meta_value as firstName,
                    um_ln.meta_value as lastName,
                    um_rn.meta_value as raceNumber,
                    ec.name as className,
                    c.name as carName
                FROM {$entriesTable} e
                LEFT JOIN {$usersTable} u ON e.userId = u.ID
                LEFT JOIN {$userMetaTable} um_fn ON e.userId = um_fn.user_id AND um_fn.meta_key = 'first_name'
                LEFT JOIN {$userMetaTable} um_ln ON e.userId = um_ln.user_id AND um_ln.meta_key = 'last_name'
                LEFT JOIN {$userMetaTable} um_rn ON e.userId = um_rn.user_id AND um_rn.meta_key = 'sltk_race_number'
                LEFT JOIN {$eventClassesTable} ec ON e.eventClassId = ec.id
                LEFT JOIN {$carsTable} c ON e.carId = c.id
                WHERE e.standaloneEventId = '{$standaloneEventId}'
                ORDER BY u.display_name;";

        return self::getResults($query);
    }
}
