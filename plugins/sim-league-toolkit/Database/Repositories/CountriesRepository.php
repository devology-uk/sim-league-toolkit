<?php

  namespace SLTK\Database\Repositories;

  use Exception;
  use SLTK\Database\TableNames;
  use stdClass;

  class CountriesRepository extends RepositoryBase {

    /**
     * @throws Exception
     */
    public static function getCountry(int $countryId): stdClass {
      return self::getRowById(TableNames::COUNTRIES, $countryId);
    }

    /**
     * @return stdClass[]
     * @throws Exception
     */
    public static function listAll(): array {
      return self::getResultsFromTable(TableNames::COUNTRIES);
    }
  }