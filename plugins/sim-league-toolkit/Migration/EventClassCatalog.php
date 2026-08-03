<?php

  namespace SLTK\Migration;

  use Exception;
  use SLTK\Database\Repositories\EventClassesRepository;
  use SLTK\Domain\EventClass;

  /**
   * Dedups per-event/per-championship legacy class rows against SLTK's shared `EventClass` catalog
   * (built-ins and already-migrated reusable templates alike), matched by (gameId, lowercased name);
   * only creates a new one-off `EventClass` when nothing matches.
   */
  class EventClassCatalog {
    private array $idsByGameAndName;

    /**
     * @throws Exception
     */
    public function __construct() {
      $this->idsByGameAndName = [];

      foreach (EventClassesRepository::list() as $eventClass) {
        $this->idsByGameAndName[$eventClass->gameId . '|' . strtolower($eventClass->name)] = (int)$eventClass->id;
      }
    }

    /**
     * @throws Exception
     */
    public function resolveOrCreate(string $name, int $gameId, int $driverCategoryId, string $carClass, bool $isSingleCarClass, ?int $singleCarId): int {
      $key = $gameId . '|' . strtolower(trim($name));
      if (isset($this->idsByGameAndName[$key])) {
        return $this->idsByGameAndName[$key];
      }

      $eventClass = new EventClass();
      $eventClass->setName($name);
      $eventClass->setGameId($gameId);
      $eventClass->setDriverCategoryId($driverCategoryId);
      $eventClass->setCarClass($carClass);
      $eventClass->setIsSingleCarClass($isSingleCarClass);
      $eventClass->setSingleCarId($singleCarId);
      $eventClass->setIsBuiltIn(false);
      $eventClass->save();

      $this->idsByGameAndName[$key] = $eventClass->getId();

      return $eventClass->getId();
    }
  }
