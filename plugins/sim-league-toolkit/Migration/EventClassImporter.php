<?php

  namespace SLTK\Migration;

  use Exception;
  use SLTK\Database\Repositories\CarRepository;
  use SLTK\Database\Repositories\MigrationRecordsRepository;
  use SLTK\Domain\DriverCategory;
  use SLTK\Domain\EventClass;
  use SLTK\Domain\Game;
  use stdClass;

  class EventClassImporter implements MigrationImporter {
    private const string ENTITY_KEY = 'event-class';
    private const string TRACK_MASTER_CAR_CLASS = 'FreeForAll';

    public function getEntityKey(): string {
      return self::ENTITY_KEY;
    }

    public function getLabel(): string {
      return __('Event classes', 'sim-league-toolkit');
    }

    public function run(): MigrationRunResult {
      $result = new MigrationRunResult();
      $gameIdsByKey = $this->buildGameIdsByKey();
      $driverCategoryIdsByName = $this->buildDriverCategoryIdsByName();
      $legacyCarNamesById = $this->buildLegacyCarNamesById();
      $sltkCarsByGameAndName = $this->buildSltkCarsByGameAndName();

      foreach (AccltLegacyDatabase::getCarDriverClasses() as $legacyClass) {
        $this->migrateClass($legacyClass, $gameIdsByKey, $driverCategoryIdsByName, $legacyCarNamesById, $sltkCarsByGameAndName, $result);
      }

      return $result;
    }

    private function buildDriverCategoryIdsByName(): array {
      $lookup = [];

      foreach (DriverCategory::list() as $category) {
        $lookup[$category->getName()] = $category->getId();
      }

      return $lookup;
    }

    private function buildGameIdsByKey(): array {
      $lookup = [];

      foreach (Game::list() as $game) {
        $lookup[$game->getGameKey()] = $game->getId();
      }

      return $lookup;
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

    /**
     * A single-car class trusts the linked car's real class over the legacy row's own carClass
     * field, which is frequently stale (e.g. a class recorded as GT3 whose linked car is really GTC).
     */
    private function resolveCarClassAndSingleCarId(stdClass $legacyClass, int $gameId, array $legacyCarNamesById, array $sltkCarsByGameAndName): array {
      if (!$legacyClass->isSingleCarClass) {
        return [$legacyClass->carClass, null];
      }

      $legacyCarName = $legacyCarNamesById[(int)$legacyClass->singleCarId] ?? null;
      if ($legacyCarName === null) {
        throw new Exception(sprintf('linked car (legacy id %d) no longer exists', (int)$legacyClass->singleCarId));
      }

      $sltkCar = $sltkCarsByGameAndName[$gameId . '|' . $legacyCarName] ?? null;
      if ($sltkCar === null) {
        throw new Exception(sprintf('no matching SLTK car found for "%s"', $legacyCarName));
      }

      return [$sltkCar->carClass, (int)$sltkCar->id];
    }

    private function migrateClass(stdClass $legacyClass, array $gameIdsByKey, array $driverCategoryIdsByName, array $legacyCarNamesById, array $sltkCarsByGameAndName, MigrationRunResult $result): void {
      $legacyId = (int)$legacyClass->id;

      if (!empty($legacyClass->eventId)) {
        $result->recordSkipped(sprintf(__('Car driver class %1$d (%2$s): tied to a specific legacy event, not a reusable template, skipped.', 'sim-league-toolkit'), $legacyId, $legacyClass->name));
        return;
      }

      if ($legacyClass->carClass === self::TRACK_MASTER_CAR_CLASS) {
        $result->recordSkipped(sprintf(__('Car driver class %1$d (%2$s): Track Master class, no SLTK equivalent, skipped.', 'sim-league-toolkit'), $legacyId, $legacyClass->name));
        return;
      }

      try {
        if (MigrationRecordsRepository::isMigrated(self::ENTITY_KEY, $legacyId)) {
          $result->recordSkipped();
          return;
        }

        $gameId = $gameIdsByKey[$legacyClass->game] ?? null;
        if ($gameId === null) {
          throw new Exception(sprintf('unknown game "%s"', $legacyClass->game));
        }

        $driverCategoryId = $driverCategoryIdsByName[$legacyClass->driverCategory] ?? null;
        if ($driverCategoryId === null) {
          throw new Exception(sprintf('unknown driver category "%s"', $legacyClass->driverCategory));
        }

        [$carClass, $singleCarId] = $this->resolveCarClassAndSingleCarId($legacyClass, $gameId, $legacyCarNamesById, $sltkCarsByGameAndName);

        $eventClass = new EventClass();
        $eventClass->setName($legacyClass->name);
        $eventClass->setGameId($gameId);
        $eventClass->setDriverCategoryId($driverCategoryId);
        $eventClass->setCarClass($carClass);
        $eventClass->setIsSingleCarClass((bool)$legacyClass->isSingleCarClass);
        $eventClass->setSingleCarId($singleCarId);
        $eventClass->setIsBuiltIn(false);
        $eventClass->save();

        MigrationRecordsRepository::recordMigration(self::ENTITY_KEY, $legacyId, $eventClass->getId());
        $result->recordMigrated();
      } catch (Exception $e) {
        $result->recordFailed(sprintf(__('Car driver class %1$d (%2$s): %3$s', 'sim-league-toolkit'), $legacyId, $legacyClass->name ?? '', $e->getMessage()));
      }
    }
  }
