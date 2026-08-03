<?php

  namespace SLTK\Migration;

  use Exception;
  use SLTK\Database\Repositories\CarRepository;
  use stdClass;

  /**
   * Resolves legacy ACCLT car ids to their SLTK equivalent, by matching car name within a game.
   * Shared by any importer that needs to translate a legacy carId into an SLTK carId/carClass.
   */
  class CarClassResolver {
    private array $legacyCarNamesById;
    private array $sltkCarsByGameAndName;

    /**
     * @throws Exception
     */
    public function __construct() {
      $this->legacyCarNamesById = $this->buildLegacyCarNamesById();
      $this->sltkCarsByGameAndName = $this->buildSltkCarsByGameAndName();
    }

    /**
     * A single-car class trusts the linked car's real class over a legacy carClass field, which is
     * frequently stale (e.g. a class recorded as GT3 whose linked car is really GTC).
     *
     * @return array{0: string, 1: ?int} [carClass, singleCarId]
     * @throws Exception
     */
    public function resolveCarClassAndSingleCarId(bool $isSingleCarClass, string $fallbackCarClass, int $legacySingleCarId, int $gameId): array {
      if (!$isSingleCarClass) {
        return [$fallbackCarClass, null];
      }

      $sltkCar = $this->findSltkCar($legacySingleCarId, $gameId);
      if ($sltkCar === null) {
        throw new Exception(sprintf('linked car (legacy id %d) could not be matched to an SLTK car', $legacySingleCarId));
      }

      return [$sltkCar->carClass, (int)$sltkCar->id];
    }

    public function resolveCarId(int $legacyCarId, int $gameId): ?int {
      $sltkCar = $this->findSltkCar($legacyCarId, $gameId);

      return $sltkCar !== null ? (int)$sltkCar->id : null;
    }

    private function findSltkCar(int $legacyCarId, int $gameId): ?stdClass {
      $legacyCarName = $this->legacyCarNamesById[$legacyCarId] ?? null;
      if ($legacyCarName === null) {
        return null;
      }

      return $this->sltkCarsByGameAndName[$gameId . '|' . $legacyCarName] ?? null;
    }

    /**
     * @throws Exception
     */
    private function buildLegacyCarNamesById(): array {
      $lookup = [];

      foreach (AccltLegacyDatabase::getCars() as $legacyCar) {
        $lookup[(int)$legacyCar->id] = $legacyCar->name;
      }

      return $lookup;
    }

    /**
     * @throws Exception
     */
    private function buildSltkCarsByGameAndName(): array {
      $lookup = [];

      foreach (CarRepository::list() as $car) {
        $lookup[$car->gameId . '|' . $car->name] = $car;
      }

      return $lookup;
    }
  }
