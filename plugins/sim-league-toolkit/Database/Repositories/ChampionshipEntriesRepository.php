<?php

namespace SLTK\Database\Repositories;

use Exception;
use SLTK\Database\TableNames;
use stdClass;

class ChampionshipEntriesRepository extends RepositoryBase {

    /**
     * @throws Exception
     */
    public static function add(array $data): int {
        return self::insert(TableNames::CHAMPIONSHIP_ENTRIES, $data);
    }

    /**
     * @throws Exception
     */
    public static function delete(int $id): void {
        self::deleteById(TableNames::CHAMPIONSHIP_ENTRIES, $id);
    }

    /**
     * @throws Exception
     */
    public static function getById(int $id): ?stdClass {
        $entriesTable = self::prefixedTableName(TableNames::CHAMPIONSHIP_ENTRIES);
        $usersTable = self::prefixedTableName(TableNames::USERS);
        $eventClassesTable = self::prefixedTableName(TableNames::EVENT_CLASSES);
        $carsTable = self::prefixedTableName(TableNames::CARS);

        $query = "SELECT
                    e.*,
                    u.display_name as memberName,
                    ec.name as className,
                    c.name as carName
                FROM {$entriesTable} e
                LEFT JOIN {$usersTable} u ON e.userId = u.ID
                LEFT JOIN {$eventClassesTable} ec ON e.eventClassId = ec.id
                LEFT JOIN {$carsTable} c ON e.carId = c.id
                WHERE e.id = '{$id}';";

        return self::getRow($query);
    }

    /**
     * @return stdClass[]
     * @throws Exception
     */
    public static function listByChampionshipId(int $championshipId): array {
        $entriesTable = self::prefixedTableName(TableNames::CHAMPIONSHIP_ENTRIES);
        $usersTable = self::prefixedTableName(TableNames::USERS);
        $eventClassesTable = self::prefixedTableName(TableNames::EVENT_CLASSES);
        $carsTable = self::prefixedTableName(TableNames::CARS);

        $query = "SELECT
                    e.*,
                    u.display_name as memberName,
                    ec.name as className,
                    c.name as carName
                FROM {$entriesTable} e
                LEFT JOIN {$usersTable} u ON e.userId = u.ID
                LEFT JOIN {$eventClassesTable} ec ON e.eventClassId = ec.id
                LEFT JOIN {$carsTable} c ON e.carId = c.id
                WHERE e.championshipId = '{$championshipId}'
                ORDER BY u.display_name;";

        return self::getResults($query);
    }
}
