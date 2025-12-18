<?php

  namespace SLTK\Database\Repositories;

  use Exception;
  use SLTK\Database\TableNames;
  use stdClass;

  class EventClassesRepository extends RepositoryBase {

    /**
     * @returns stdClass[]
     * @throws Exception
     */
    public static function listForGame(int $gameId): array {
      $tableName = self::prefixedTableName(TableNames::EVENT_CLASSES);
      $gamesTableName = self::prefixedTableName(TableNames::GAMES);
      $driverCategoriesTableName = self::prefixedTableName(TableNames::DRIVER_CATEGORIES);
      $carsTableName = self::prefixedTableName(TableNames::CARS);

      $query = "SELECT ec.*, g.name as game dc.name as driverCategory, c.name as singleCarName
                FROM $tableName ec
                INNER JOIN $gamesTableName g
                ON ec.gameId = g.id
                INNER JOIN $driverCategoriesTableName dc
                ON ec.driverCategoryId = dc.id
                INNER JOIN $carsTableName c
                ON ec.singleCarId = c.id
                WHERE gameId = $gameId;";

      return self::getResults($query);
    }

    /**
     * @throws Exception
     */
    public static function getById(int $id): stdClass|null {
      $tableName = self::prefixedTableName(TableNames::EVENT_CLASSES);
      $gamesTableName = self::prefixedTableName(TableNames::GAMES);
      $driverCategoriesTableName = self::prefixedTableName(TableNames::DRIVER_CATEGORIES);
      $carsTableName = self::prefixedTableName(TableNames::CARS);

      $query = "SELECT ec.*, g.name as game dc.name as driverCategory, c.name as singleCarName
                FROM $tableName ec
                INNER JOIN $gamesTableName g
                ON ec.gameId = g.id
                INNER JOIN $driverCategoriesTableName dc
                ON ec.driverCategoryId = dc.id
                INNER JOIN $carsTableName c
                ON ec.singleCarId = c.id
                WHERE id = $id;";

      return self::getRow($query);
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

      $query = "SELECT ec.*, g.name as game dc.name as driverCategory, c.name as singleCarName
                FROM $tableName ec
                INNER JOIN $gamesTableName g
                ON ec.gameId = g.id
                INNER JOIN $driverCategoriesTableName dc
                ON ec.driverCategoryId = dc.id
                INNER JOIN $carsTableName c
                ON ec.singleCarId = c.id";

      return self::getResults($query);
    }

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

    public static function update(int $id, array $updates): void {
      self::updateById(TableNames::EVENT_CLASSES, $id, $updates);
    }
  }