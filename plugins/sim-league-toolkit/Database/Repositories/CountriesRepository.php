<?php

  namespace SLTK\Database\Repositories;

  use SLTK\Database\TableNames;
  use stdClass;

  class CountriesRepository extends RepositoryBase {

    public static function getCountry(int $countryId): stdClass {
      return self::getRowById(TableNames::COUNTRIES, $countryId);
    }

    /**
     * @return stdClass[]
     */
    public static function listAll(): array {
      return self::getResultsFromTable(TableNames::COUNTRIES);
    }
  }