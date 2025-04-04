<?php

  namespace SLTK\Domain;

  use SLTK\Database\Repositories\CountriesRepository;
  use stdClass;

  class Country extends DomainBase {

    public function __construct(stdClass $data = null) {
      if ($data) {
        $this->name = $data->name ?? '';
        $this->alpha2 = $data->alpha2 ?? '';
        $this->alpha3 = $data->alpha3 ?? '';
        $this->countryCode = $data->countryCode ?? '';
        $this->hide = $data->hide ?? false;
      }

    }

    public string $alpha2 = '';
    public string $alpha3 = '';
    public string $countryCode = '';
    public bool $hide = false;
    public string $name = '';

    public static function get(int $id): Country {
      return new Country(CountriesRepository::getCountry($id));
    }

    /**
     * @return Country[]
     */
    public static function list(): array {
      $queryResults = CountriesRepository::listAll();

      return self::mapCountries($queryResults);
    }

    public function save(): bool {
      return false;
    }

    private static function mapCountries(array $queryResults): array {
      $results = array();

      foreach ($queryResults as $item) {
        $results[] = new Country($item);
      }

      return $results;
    }
  }