<?php

  namespace SLTK\Database\Repositories;

  use Exception;
  use SLTK\Database\TableNames;
  use stdClass;

  class EventClassesRepository extends RepositoryBase {

    /**
     * @throws Exception
     */
    public static function add(array $eventClass): int {
      return self::insert(TableNames::EVENT_CLASSES, $eventClass);
    }

    /**
     * @throws Exception
     */
    public static function delete(int $eventClassId): void {
      self::deleteById(TableNames::EVENT_CLASSES, $eventClassId);
    }

    /**
     * @throws Exception
     */
    public static function getById(int $id): stdClass|null {
      $tableName = self::prefixedTableName(TableNames::EVENT_CLASSES);
      $gamesTableName = self::prefixedTableName(TableNames::GAMES);
      $driverCategoriesTableName = self::prefixedTableName(TableNames::DRIVER_CATEGORIES);
      $carsTableName = self::prefixedTableName(TableNames::CARS);

      $query = "SELECT ec.*, g.name as game, dc.name as driverCategory, c.name as singleCarName
                FROM $tableName ec
                INNER JOIN $gamesTableName g
                ON ec.gameId = g.id
                INNER JOIN $driverCategoriesTableName dc
                ON ec.driverCategoryId = dc.id
                LEFT OUTER JOIN $carsTableName c
                ON ec.singleCarId = c.id
                WHERE ec.id = $id;";

      return self::getRow($query);
    }

    /**
     * @throws Exception
     */
    public static function isInUse(int $id): bool {
      return self::simpleExists(TableNames::CHAMPIONSHIP_EVENT_CLASSES, "eventClassId = $id");
    }

    /**
     * @returns stdClass[]
     * @throws Exception     *
     */
    public static function list(): array {
      $tableName = self::prefixedTableName(TableNames::EVENT_CLASSES);
      $gamesTableName = self::prefixedTableName(TableNames::GAMES);
      $driverCategoriesTableName = self::prefixedTableName(TableNames::DRIVER_CATEGORIES);
      $carsTableName = self::prefixedTableName(TableNames::CARS);

      $query = "SELECT ec.*, g.name as game, dc.name as driverCategory, c.name as singleCarName
                FROM $tableName ec
                INNER JOIN $gamesTableName g
                ON ec.gameId = g.id
                INNER JOIN $driverCategoriesTableName dc
                ON ec.driverCategoryId = dc.id
                LEFT OUTER JOIN $carsTableName c
                ON ec.singleCarId = c.id
                ORDER BY ec.isBuiltIn, ec.name;";

      return self::getResults($query);
    }

    /**
     * @return stdClass[]
     * @throws Exception
     */
    public static function listAvailableForChampionship(int $championshipId): array {
      $tableName = self::prefixedTableName(TableNames::EVENT_CLASSES);
      $championshipEventClassesTableName = self::prefixedTableName(TableNames::CHAMPIONSHIP_EVENT_CLASSES);
      $championshipsTableName = self::prefixedTableName(TableNames::CHAMPIONSHIPS);

      $query = "SELECT ec.*
                FROM $tableName ec
                INNER JOIN $championshipsTableName c
                ON ec.gameId = c.gameId
                LEFT OUTER JOIN $championshipEventClassesTableName cc
                ON c.id = cc.championshipId
                AND ec.id = cc.eventClassId
                WHERE c.id = $championshipId
                AND cc.eventClassId IS NULL;";

      return self::getResults($query);
    }

    /**
     * @return stdClass[]
     * @throws Exception
     */
    public static function listForChampionship($id): array {
      $tableName = self::prefixedTableName(TableNames::EVENT_CLASSES);
      $gamesTableName = self::prefixedTableName(TableNames::GAMES);
      $driverCategoriesTableName = self::prefixedTableName(TableNames::DRIVER_CATEGORIES);
      $carsTableName = self::prefixedTableName(TableNames::CARS);
      $championshipEventClassesTableName = self::prefixedTableName(TableNames::CHAMPIONSHIP_EVENT_CLASSES);
      $championshipEntriesTableName = self::prefixedTableName(TableNames::CHAMPIONSHIP_ENTRIES);

      $query = "SELECT $id as championshipId,
                        cec.eventClassId,
                        ec.carClass,
                        ec.driverCategoryId,
                        ec.gameId,
                        ec.isSingleCarClass,                    
                        ec.name,
                        ec.singleCarId,
                        ec.isBuiltIn,
                        g.name as game,
                        dc.name as driverCategory,
                        c.name as singleCarName,
                        (SELECT COUNT(*) FROM $championshipEntriesTableName WHERE championshipId = $id AND eventClassId = cec.eventClassId) as isInUse
                FROM $tableName ec
                INNER JOIN $championshipEventClassesTableName cec
                ON ec.Id = cec.eventClassId                
                INNER JOIN $gamesTableName g
                ON ec.gameId = g.id
                INNER JOIN $driverCategoriesTableName dc
                ON ec.driverCategoryId = dc.id
                LEFT OUTER JOIN $carsTableName c
                ON ec.singleCarId = c.id                
                WHERE cec.championshipId = $id;";

      return self::getResults($query);
    }

    /**
     * @returns stdClass[]
     * @throws Exception
     */
    public static function listForGame(int $gameId): array {
      $tableName = self::prefixedTableName(TableNames::EVENT_CLASSES);
      $gamesTableName = self::prefixedTableName(TableNames::GAMES);
      $driverCategoriesTableName = self::prefixedTableName(TableNames::DRIVER_CATEGORIES);
      $carsTableName = self::prefixedTableName(TableNames::CARS);

      $query = "SELECT ec.*, g.name as game, dc.name as driverCategory, c.name as singleCarName
                FROM $tableName ec
                INNER JOIN $gamesTableName g
                ON ec.gameId = g.id
                INNER JOIN $driverCategoriesTableName dc
                ON ec.driverCategoryId = dc.id
                INNER JOIN $carsTableName c
                ON ec.singleCarId = c.id
                WHERE ec.gameId = $gameId;";

      return self::getResults($query);
    }

    public static function update(int $id, array $updates): void {
      self::updateById(TableNames::EVENT_CLASSES, $id, $updates);
    }
  }