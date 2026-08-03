<?php

  namespace SLTK\Migration;

  use Exception;
  use SLTK\Database\Repositories\ChampionshipEntriesRepository;
  use SLTK\Database\Repositories\MigrationRecordsRepository;
  use SLTK\Domain\Championship;
  use SLTK\Domain\ChampionshipEntry;
  use SLTK\Domain\ChampionshipSessionResult;
  use stdClass;

  /**
   * Migrates Qualifying/Race session results for already-migrated championships. Practice results
   * are skipped (Mike's call - no competitive significance). Championships not migrated (Track
   * Master, deferred - see `ChampionshipImporter`) are skipped along with their results.
   *
   * Some legacy results belong to a driver who raced a single round as a one-off without ever being
   * added to that championship's season roster (`acclt_championship_entrants`) - ACCLT tracked that
   * via per-round attendance (`acclt_event_entrants`) instead, which isn't otherwise migrated (no
   * SLTK equivalent - see `sltk-championship-migration` memory). Rather than lose that result, a
   * minimal `ChampionshipEntry` is back-filled from the per-round attendance row's car/class, per
   * Mike's call (2026-08-03).
   */
  class ChampionshipSessionResultImporter implements MigrationImporter {
    private const string ENTITY_KEY = 'championship-session-result';
    private const string CHAMPIONSHIP_ENTITY_KEY = 'championship';
    private const string EVENT_SESSION_ENTITY_KEY = 'event-session';
    private const array COMPETITIVE_SESSION_TYPES = ['Q', 'R'];

    private CarClassResolver $carClassResolver;
    private DriverCategoryLookup $driverCategoryLookup;
    private EventClassCatalog $eventClassCatalog;
    private array $legacyChampionshipClassesById;
    private array $legacyEventEntrantsByEventAndUser;

    public function getEntityKey(): string {
      return self::ENTITY_KEY;
    }

    public function getLabel(): string {
      return __('Championship session results', 'sim-league-toolkit');
    }

    /**
     * @throws Exception
     */
    public function run(): MigrationRunResult {
      $result = new MigrationRunResult();

      $this->carClassResolver = new CarClassResolver();
      $this->driverCategoryLookup = new DriverCategoryLookup();
      $this->eventClassCatalog = new EventClassCatalog();
      $this->legacyChampionshipClassesById = $this->indexById(AccltLegacyDatabase::getChampionshipClasses());
      $this->legacyEventEntrantsByEventAndUser = $this->buildEventEntrantsByEventAndUser();

      $resultDataIndex = new LegacyResultDataIndex();
      $steamIdUserLookup = new SteamIdUserLookup();
      $sessionsByEventId = $this->groupByEventId(AccltLegacyDatabase::getEventSessions());

      foreach (AccltLegacyDatabase::getChampionships() as $legacyChampionship) {
        $legacyChampionshipId = (int)$legacyChampionship->id;
        $championshipId = MigrationRecordsRepository::getTargetId(self::CHAMPIONSHIP_ENTITY_KEY, $legacyChampionshipId);
        if ($championshipId === null) {
          continue;
        }

        $gameId = $this->resolveChampionshipGameId($championshipId);
        $entryIdsByUserId = $this->buildEntryIdsByUserId($championshipId);

        foreach (AccltLegacyDatabase::getEvents() as $legacyEvent) {
          if ((int)($legacyEvent->championshipId ?? 0) !== $legacyChampionshipId) {
            continue;
          }

          $legacyEventId = (int)$legacyEvent->id;

          foreach ($sessionsByEventId[$legacyEventId] ?? [] as $legacySession) {
            if (!in_array($legacySession->sessionType, self::COMPETITIVE_SESSION_TYPES, true)) {
              continue;
            }

            $this->migrateSessionResults(
              (int)$legacySession->id,
              $legacyEventId,
              $championshipId,
              $gameId,
              $entryIdsByUserId,
              $resultDataIndex,
              $steamIdUserLookup,
              $result
            );
          }
        }
      }

      return $result;
    }

    /**
     * @throws Exception
     */
    private function backfillEntry(int $legacyEventId, int $championshipId, int $gameId, int $userId, MigrationRunResult $result): ?int {
      $legacyEntrant = $this->legacyEventEntrantsByEventAndUser[$legacyEventId][$userId] ?? null;
      if ($legacyEntrant === null) {
        return null;
      }

      $legacyClass = $this->legacyChampionshipClassesById[(int)$legacyEntrant->classId] ?? null;
      if ($legacyClass === null) {
        return null;
      }

      $driverCategoryId = $this->driverCategoryLookup->resolve($legacyClass->driverCategory);
      if ($driverCategoryId === null) {
        return null;
      }

      [$carClass, $singleCarId] = $this->carClassResolver->resolveCarClassAndSingleCarId(
        (bool)$legacyClass->isSingleCarClass,
        $legacyClass->carClass,
        (int)($legacyClass->singleCarId ?? 0),
        $gameId
      );
      $eventClassId = $this->eventClassCatalog->resolveOrCreate($legacyClass->name, $gameId, $driverCategoryId, $carClass, (bool)$legacyClass->isSingleCarClass, $singleCarId);

      $carId = $this->carClassResolver->resolveCarId((int)$legacyEntrant->carId, $gameId);
      if ($carId === null) {
        return null;
      }

      $entry = new ChampionshipEntry();
      $entry->setChampionshipId($championshipId);
      $entry->setCarId($carId);
      $entry->setUserId($userId);
      $entry->setStatus('confirmed');
      $entry->setEventClassId($eventClassId);
      $entry->save();

      $result->addWarning(sprintf(__('Championship %1$d: back-filled a roster entry for user %2$d (raced a round as a one-off, was never added to the season roster).', 'sim-league-toolkit'), $championshipId, $userId));

      return $entry->getId();
    }

    /**
     * @throws Exception
     */
    private function buildEntryIdsByUserId(int $championshipId): array {
      $lookup = [];

      foreach (ChampionshipEntriesRepository::listByChampionshipId($championshipId) as $entry) {
        $lookup[(int)$entry->userId] = (int)$entry->id;
      }

      return $lookup;
    }

    /**
     * @return array<int, array<int, stdClass>> legacy eventId => userId => entrant row
     * @throws Exception
     */
    private function buildEventEntrantsByEventAndUser(): array {
      $lookup = [];

      foreach (AccltLegacyDatabase::getEventEntrants() as $entrant) {
        $lookup[(int)$entrant->eventId][(int)$entrant->userId] = $entrant;
      }

      return $lookup;
    }

    /**
     * @return array<int, stdClass> keyed by id
     */
    private function indexById(array $rows): array {
      $lookup = [];

      foreach ($rows as $row) {
        $lookup[(int)$row->id] = $row;
      }

      return $lookup;
    }

    /**
     * @return array<int, stdClass[]> legacy eventId => rows
     */
    private function groupByEventId(array $rows): array {
      $grouped = [];

      foreach ($rows as $row) {
        $grouped[(int)$row->eventId][] = $row;
      }

      return $grouped;
    }

    /**
     * @throws Exception
     */
    private function migrateResult(stdClass $leaderBoardRow, int $legacyEventSessionId, int $legacyEventId, int $championshipId, int $gameId, int $newSessionId, array &$entryIdsByUserId, LegacyResultDataIndex $resultDataIndex, SteamIdUserLookup $steamIdUserLookup, MigrationRunResult $result): void {
      $legacyId = (int)$leaderBoardRow->id;

      try {
        if (MigrationRecordsRepository::isMigrated(self::ENTITY_KEY, $legacyId)) {
          $result->recordSkipped();
          return;
        }

        $userId = $steamIdUserLookup->resolve($leaderBoardRow->playerId);
        if ($userId === null) {
          $result->recordSkipped(sprintf(__('Result %1$d: player %2$s could not be matched to a user, skipped.', 'sim-league-toolkit'), $legacyId, $leaderBoardRow->playerId));
          return;
        }

        $entryId = $entryIdsByUserId[$userId] ?? null;
        if ($entryId === null) {
          $entryId = $this->backfillEntry($legacyEventId, $championshipId, $gameId, $userId, $result);
          if ($entryId !== null) {
            $entryIdsByUserId[$userId] = $entryId;
          }
        }
        if ($entryId === null) {
          $result->recordSkipped(sprintf(__('Result %1$d: user %2$d has no entrant record anywhere in ACCLT for this event (not even per-round attendance), nothing to back-fill from, dropped.', 'sim-league-toolkit'), $legacyId, $userId));
          return;
        }

        $carSummary = $resultDataIndex->getCarSummary($legacyEventSessionId, $leaderBoardRow->playerId);
        $lapValidity = $carSummary !== null ? $resultDataIndex->getLapValidity($legacyEventSessionId, (int)$carSummary->id) : null;
        $fields = SessionResultFieldMapper::mapFields($leaderBoardRow, $carSummary, $lapValidity);

        $sessionResult = new ChampionshipSessionResult();
        $sessionResult->setEventSessionId($newSessionId);
        $sessionResult->setChampionshipEntryId($entryId);
        $sessionResult->setPosition($fields['position']);
        $sessionResult->setTotalTimeMs($fields['totalTimeMs']);
        $sessionResult->setFastestLapMs($fields['fastestLapMs']);
        $sessionResult->setSector1TimeMs($fields['sector1TimeMs']);
        $sessionResult->setSector2TimeMs($fields['sector2TimeMs']);
        $sessionResult->setSector3TimeMs($fields['sector3TimeMs']);
        $sessionResult->setLapsCompleted($fields['lapsCompleted']);
        $sessionResult->setValidLapsCount($fields['validLapsCount']);
        $sessionResult->setStatus($fields['status']);
        $sessionResult->setPoints($fields['points']);
        $sessionResult->save();

        MigrationRecordsRepository::recordMigration(self::ENTITY_KEY, $legacyId, $sessionResult->getId());
        $result->recordMigrated();
      } catch (Exception $e) {
        $result->recordFailed(sprintf(__('Result %1$d: %2$s', 'sim-league-toolkit'), $legacyId, $e->getMessage()));
      }
    }

    /**
     * @throws Exception
     */
    private function migrateSessionResults(int $legacyEventSessionId, int $legacyEventId, int $championshipId, int $gameId, array &$entryIdsByUserId, LegacyResultDataIndex $resultDataIndex, SteamIdUserLookup $steamIdUserLookup, MigrationRunResult $result): void {
      $leaderBoardRows = $resultDataIndex->getLeaderBoardRows($legacyEventSessionId);
      if (empty($leaderBoardRows)) {
        return;
      }

      $newSessionId = MigrationRecordsRepository::getTargetId(self::EVENT_SESSION_ENTITY_KEY, $legacyEventSessionId);
      if ($newSessionId === null) {
        $result->recordFailed(sprintf(__('Session %d: not migrated, skipped its results.', 'sim-league-toolkit'), $legacyEventSessionId));
        return;
      }

      foreach ($leaderBoardRows as $leaderBoardRow) {
        $this->migrateResult($leaderBoardRow, $legacyEventSessionId, $legacyEventId, $championshipId, $gameId, $newSessionId, $entryIdsByUserId, $resultDataIndex, $steamIdUserLookup, $result);
      }
    }

    /**
     * @throws Exception
     */
    private function resolveChampionshipGameId(int $championshipId): int {
      return (int)Championship::get($championshipId)->getGameId();
    }
  }
