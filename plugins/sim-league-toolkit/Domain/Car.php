<?php

  namespace SLTK\Domain;

  use Exception;
  use SLTK\Core\Constants;
  use SLTK\Database\Repositories\CarRepository;
  use stdClass;

  class Car extends EntityBase {

    private string $carClass = '';
    private string $carKey = '';
    private int $gameId = Constants::DEFAULT_ID;
    private string $manufacturer = '';
    private string $name = '';
    private int $year = 0;

    public function __construct(stdClass $data = null) {
      parent::__construct($data);

      if ($data) {
        $this->gameId = $data->gameId;
        $this->carClass = $data->carClass;
        $this->carKey = $data->carKey;
        $this->name = $data->name;
        $this->year = $data->year;
        $this->manufacturer = $data->manufacturer;
      }
    }

    public static function get(int $id): Car|null {
      $queryResult = CarRepository::getById($id);

      return new Car($queryResult);
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

    public function getCarKey(): string {
      return $this->carKey;
    }

    public function getDisplayName(): string {
      return $this->name . ' (' . $this->year . ')';
    }

    public function getGameId(): int {
      return $this->gameId;
    }

    public function getManufacturer(): string {
      return $this->manufacturer;
    }

    public function getName(): string {
      return $this->name;
    }

    public function getYear(): int {
      return $this->year;
    }

    public function toArray(): array {
      $result = [
        'carClass' => $this->carClass,
        'carKey' => $this->carKey,
        'gameId' => $this->gameId,
        'manufacturer' => $this->manufacturer,
        'name' => $this->name,
        'year' => $this->year,
      ];

      if ($this->getId() !== Constants::DEFAULT_ID) {
        $result['id'] = $this->getId();
      }

      return $result;
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
        $results[] = new Car($item);
      }

      return $results;
    }
  }