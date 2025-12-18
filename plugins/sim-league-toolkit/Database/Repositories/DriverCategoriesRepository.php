<?php

  namespace SLTK\Database\Repositories;

  use SLTK\Database\TableNames;

  class DriverCategoriesRepository extends RepositoryBase {
    public static function list(): array {
      return self::getResultsFromTable(TableNames::DRIVER_CATEGORIES);
    }
  }