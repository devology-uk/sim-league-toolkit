<?php

  namespace SLTK\Migration;

  use Exception;
  use stdClass;

  /**
   * Loads and indexes ACCLT's imported-result tables once, for fast in-memory lookup while migrating
   * session results. `acclt_event_result_cars.id` (a surrogate key) is what
   * `acclt_event_result_drivers`/`acclt_event_result_laps` actually key their own `carId` column
   * against - not `acclt_event_result_cars.carId` itself, which is only ACC's raw in-session car
   * index, reused as a dedup key inside the legacy importer and otherwise irrelevant here.
   */
  class LegacyResultDataIndex {
    /** @var array<int, stdClass[]> legacy eventSessionId => leaderboard rows */
    private array $leaderBoardRowsByEventSessionId;

    /** @var array<int, array<int, array{valid: int, total: int}>> resultSessionId => carRowId => counts */
    private array $lapValidityByResultSessionAndCarRowId;

    /** @var array<int, array<string, stdClass>> resultSessionId => playerId => result_cars row */
    private array $carSummaryByResultSessionAndPlayerId;

    /** @var array<int, int> legacy eventSessionId => acclt_event_result_sessions.id */
    private array $resultSessionIdByEventSessionId;

    /**
     * @throws Exception
     */
    public function __construct() {
      $this->resultSessionIdByEventSessionId = $this->buildResultSessionIdByEventSessionId();
      $this->carSummaryByResultSessionAndPlayerId = $this->buildCarSummaryByResultSessionAndPlayerId();
      $this->lapValidityByResultSessionAndCarRowId = $this->buildLapValidityByResultSessionAndCarRowId();
      $this->leaderBoardRowsByEventSessionId = $this->buildLeaderBoardRowsByEventSessionId();
    }

    public function getCarSummary(int $legacyEventSessionId, string $playerId): ?stdClass {
      $resultSessionId = $this->resultSessionIdByEventSessionId[$legacyEventSessionId] ?? null;

      return $resultSessionId !== null
        ? ($this->carSummaryByResultSessionAndPlayerId[$resultSessionId][$playerId] ?? null)
        : null;
    }

    /**
     * @return array{valid: int, total: int}|null
     */
    public function getLapValidity(int $legacyEventSessionId, int $carRowId): ?array {
      $resultSessionId = $this->resultSessionIdByEventSessionId[$legacyEventSessionId] ?? null;

      return $resultSessionId !== null
        ? ($this->lapValidityByResultSessionAndCarRowId[$resultSessionId][$carRowId] ?? null)
        : null;
    }

    /**
     * @return stdClass[]
     */
    public function getLeaderBoardRows(int $legacyEventSessionId): array {
      return $this->leaderBoardRowsByEventSessionId[$legacyEventSessionId] ?? [];
    }

    /**
     * @throws Exception
     */
    private function buildCarSummaryByResultSessionAndPlayerId(): array {
      $playerIdByResultSessionAndCarRowId = [];
      foreach (AccltLegacyDatabase::getResultDrivers() as $driver) {
        if (empty($driver->isCurrentDriver)) {
          continue;
        }

        $playerIdByResultSessionAndCarRowId[(int)$driver->sessionId][(int)$driver->carId] = $driver->playerId;
      }

      $summaries = [];
      foreach (AccltLegacyDatabase::getResultCars() as $car) {
        $resultSessionId = (int)$car->sessionId;
        $carRowId = (int)$car->id;
        $playerId = $playerIdByResultSessionAndCarRowId[$resultSessionId][$carRowId] ?? null;

        if ($playerId !== null) {
          $summaries[$resultSessionId][$playerId] = $car;
        }
      }

      return $summaries;
    }

    /**
     * @throws Exception
     */
    private function buildLapValidityByResultSessionAndCarRowId(): array {
      $validity = [];

      foreach (AccltLegacyDatabase::getResultLaps() as $lap) {
        $resultSessionId = (int)$lap->sessionId;
        $carRowId = (int)$lap->carId;

        if (!isset($validity[$resultSessionId][$carRowId])) {
          $validity[$resultSessionId][$carRowId] = ['valid' => 0, 'total' => 0];
        }

        $validity[$resultSessionId][$carRowId]['total']++;
        if (!empty($lap->isValid)) {
          $validity[$resultSessionId][$carRowId]['valid']++;
        }
      }

      return $validity;
    }

    /**
     * @throws Exception
     */
    private function buildLeaderBoardRowsByEventSessionId(): array {
      $rows = [];

      foreach (AccltLegacyDatabase::getLeaderBoardRows() as $row) {
        $rows[(int)$row->eventSessionId][] = $row;
      }

      return $rows;
    }

    /**
     * @throws Exception
     */
    private function buildResultSessionIdByEventSessionId(): array {
      $lookup = [];

      foreach (AccltLegacyDatabase::getResultSessions() as $resultSession) {
        $lookup[(int)$resultSession->eventSessionId] = (int)$resultSession->id;
      }

      return $lookup;
    }
  }
