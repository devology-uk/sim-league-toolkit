<?php

  namespace SLTK\Database\Repositories;

  use Exception;
  use SLTK\Database\TableNames;
  use stdClass;

  class DriverCategoriesRepository extends RepositoryBase {
    /**
     * @throws Exception
     */
    public static function getById(int $id): ?stdClass {
      return self::getRowById(TableNames::DRIVER_CATEGORIES, $id);
    }

    public static function list(): array {
      return self::getResultsFromTable(TableNames::DRIVER_CATEGORIES);
    }
  }