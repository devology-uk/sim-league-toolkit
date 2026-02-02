<?php

  namespace SLTK\Domain;

  use Exception;
  use SLTK\Database\Repositories\CountriesRepository;
  use SLTK\Domain\Abstractions\AggregateRoot;
  use SLTK\Domain\Traits\HasIdentity;
  use stdClass;

  class Country implements AggregateRoot {
    use HasIdentity;

    private string $alpha2 = '';
    private string $alpha3 = '';
    private string $countryCode = '';
    private bool $hide = false;
    private string $name = '';

    public static function fromStdClass(?stdClass $data): ?self {
      $result = new self();

      $result->setName($data->name ?? '');
      $result->setAlpha2($data->alpha2 ?? '');
      $result->setAlpha3($data->alpha3 ?? '');
      $result->setCountryCode($data->countryCode ?? '');
      $result->setHide($data->hide ?? false);

      return $result;
    }

    /**
     * @throws Exception
     */
    public static function get(int $id): Country {
      return self::fromStdClass(CountriesRepository::getCountry($id));
    }

    /**
     * @return Country[]
     * @throws Exception
     */
    public static function list(): array {
      $queryResults = CountriesRepository::listAll();

      return self::mapCountries($queryResults);
    }

    public function getAlpha2(): string {
      return $this->alpha2 ?? '';
    }

    public function getAlpha3(): string {
      return $this->alpha3 ?? '';
    }

    public function getCountryCode(): string {
      return $this->countryCode ?? '';
    }

    public function getHide(): bool {
      return $this->hide ?? false;
    }

    public function setHide(bool $value): void {
      $this->hide = $value;
    }

    public function getName(): string {
      return $this->name ?? '';
    }

    public function toDto(): array {
      return [
        'alpha2' => $this->getAlpha2(),
        'alpha3' => $this->getAlpha3(),
        'countryCode' => $this->getCountryCode(),
        'hide' => $this->getHide(),
        'name' => $this->getName()
      ];
    }

    private static function mapCountries(array $queryResults): array {
      return array_map(function ($item) {
        return self::fromStdClass($item);
      }, $queryResults);
    }

    private function setAlpha2(string $value): void {
      $this->alpha2 = $value;
    }

    private function setAlpha3(string $value): void {
      $this->alpha3 = $value;
    }

    private function setCountryCode(string $value): void {
      $this->countryCode = $value;
    }

    private function setName(string $value): void {
      $this->name = $value;
    }
  }