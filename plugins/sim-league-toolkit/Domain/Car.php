<?php

  namespace SLTK\Domain;

  use Exception;
  use SLTK\Core\Constants;
  use SLTK\Database\Repositories\CarRepository;
  use SLTK\Domain\Abstractions\ValueObject;
  use SLTK\Domain\Traits\HasIdentity;
  use stdClass;

  class Car implements ValueObject {
    use HasIdentity;

    private string $carClass = '';
    private string $carKey = '';
    private int $gameId = Constants::DEFAULT_ID;
    private string $manufacturer = '';
    private string $name = '';
    private int $year = 0;

    public static function fromStdClass(?stdClass $data): ?self {
      if(!$data){
        return null;
      }

      $result = new self();
      $result->setId($data->id);
      $result->gameId = $data->gameId;
      $result->carClass = $data->carClass;
      $result->carKey = $data->carKey;
      $result->name = $data->name;
      $result->year = $data->year;
      $result->manufacturer = $data->manufacturer;
      return $result;
    }

    /**
     * @throws Exception
     */
    public static function get(int $id): ?Car {
      $queryResult = CarRepository::getById($id);
      return self::fromStdClass($queryResult);
    }

    /**
     * @return Car[]
     * @throws Exception
     */
    public static function list(): array {
      $queryResult = CarRepository::list();

      return self::mapCars($queryResult);
    }

    /**
     * @return string[]
     * @throws Exception
     */
    public static function listClassesForGame(int $gameId): array {
      $queryResults = CarRepository::listCarClassesForGame($gameId);

      $results = [];

      foreach ($queryResults as $carClass) {
        $results[] = $carClass->carClass;
      }

      return $results;
    }

    /**
     * @return Car[]
     * @throws Exception
     */
    public static function listForGame(int $gameId, ?string $carClass = null): array {
      $queryResult = CarRepository::listForGame($gameId, $carClass);

      return self::mapCars($queryResult);
    }

    public function getCarClass(): string {
      return $this->carClass;
    }

    public function setCarClass(string $carClass): void {
      $this->carClass = $carClass;
    }

    public function getCarKey(): string {
      return $this->carKey;
    }
    public function setCarKey(string $carKey): void {
      $this->carKey = $carKey;
    }

    public function getDisplayName(): string {
      return $this->name . ' (' . $this->year . ')';
    }

    public function getGameId(): int {
      return $this->gameId;
    }
    public function setGameId(int $gameId): void {
      $this->gameId = $gameId;
    }

    public function getManufacturer(): string {
      return $this->manufacturer;
    }
    public function setManufacturer(string $manufacturer): void {
      $this->manufacturer = $manufacturer;
    }

    public function getName(): string {
      return $this->name;
    }
    public function setName(string $name): void {
      $this->name = $name;
    }

    public function getYear(): int {
      return $this->year;
    }
    public function setYear(int $year): void {
      $this->year = $year;
    }

    public function toArray(): array {
      return [
        'carClass' => $this->getCarClass(),
        'carKey' => $this->getCarKey(),
        'gameId' => $this->getgameId(),
        'manufacturer' => $this->getManufacturer(),
        'name' => $this->getName(),
        'year' => $this->getYear(),
      ];
    }

    public function toDto(): array {
      return [
        'id' => $this->getId(),
        'carClass' => $this->getCarClass(),
        'carKey' => $this->getCarKey(),
        'gameId' => $this->getGameId(),
        'manufacturer' => $this->getManufacturer(),
        'name' => $this->getName(),
        'year' => $this->getYear(),
      ];
    }

    private static function mapCars(array $queryResults): array {
      $results = array();

      foreach ($queryResults as $item) {
        $results[] = self::fromStdClass($item);
      }

      return $results;
    }
  }