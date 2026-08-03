<?php

  namespace SLTK\Migration;

  use DateTime;
  use Exception;
  use SLTK\Core\Enums\GameKey;
  use SLTK\Database\Repositories\MigrationRecordsRepository;
  use SLTK\Domain\StandaloneEvent;
  use SLTK\Domain\StandaloneEventEntry;
  use stdClass;

  /**
   * Migrates ACCLT's standalone events (events with no championshipId) - event, its classes,
   * sessions and entrants together, since none of those are independently meaningful without the
   * parent event they belong to.
   */
  class StandaloneEventImporter implements MigrationImporter {
    private const string ENTITY_KEY = 'standalone-event';
    private const string SCORING_SET_ENTITY_KEY = 'scoring-set';

    private CarClassResolver $carClassResolver;
    private DriverCategoryLookup $driverCategoryLookup;
    private EventClassCatalog $eventClassCatalog;
    private EventSessionMigrator $eventSessionMigrator;
    private GameKeyLookup $gameKeyLookup;
    private TrackResolver $trackResolver;

    public function getEntityKey(): string {
      return self::ENTITY_KEY;
    }

    public function getLabel(): string {
      return __('Standalone events', 'sim-league-toolkit');
    }

    /**
     * @throws Exception
     */
    public function run(): MigrationRunResult {
      $result = new MigrationRunResult();

      $this->carClassResolver = new CarClassResolver();
      $this->driverCategoryLookup = new DriverCategoryLookup();
      $this->eventClassCatalog = new EventClassCatalog();
      $this->eventSessionMigrator = new EventSessionMigrator();
      $this->gameKeyLookup = new GameKeyLookup();
      $this->trackResolver = new TrackResolver();

      $classesByEventId = $this->groupByEventId(AccltLegacyDatabase::getEventClasses());
      $sessionsByEventId = $this->groupByEventId(AccltLegacyDatabase::getEventSessions());
      $entrantsByEventId = $this->groupByEventId(AccltLegacyDatabase::getEventEntrants());

      foreach (AccltLegacyDatabase::getEvents() as $legacyEvent) {
        if (!empty($legacyEvent->championshipId)) {
          continue;
        }

        $legacyId = (int)$legacyEvent->id;
        $this->migrateEvent(
          $legacyEvent,
          $classesByEventId[$legacyId] ?? [],
          $sessionsByEventId[$legacyId] ?? [],
          $entrantsByEventId[$legacyId] ?? [],
          $result
        );
      }

      return $result;
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
    private function migrateEntrants(array $legacyEntrants, int $gameId, int $standaloneEventId, array $eventClassIdsByLegacyId, MigrationRunResult $result): void {
      foreach ($legacyEntrants as $legacyEntrant) {
        $userId = (int)$legacyEntrant->userId;

        if (!empty($legacyEntrant->hasSkipped)) {
          $result->recordSkipped(sprintf(__('Entrant (user %d): withdrawn from event, SLTK has no equivalent status, skipped.', 'sim-league-toolkit'), $userId));
          continue;
        }

        if (get_user_by('id', $userId) === false) {
          $result->recordSkipped(sprintf(__('Entrant: user %d no longer exists, skipped.', 'sim-league-toolkit'), $userId));
          continue;
        }

        $carId = $this->carClassResolver->resolveCarId((int)$legacyEntrant->carId, $gameId);
        if ($carId === null) {
          $result->recordFailed(sprintf(__('Entrant (user %1$d): car (legacy id %2$d) could not be matched, skipped.', 'sim-league-toolkit'), $userId, (int)$legacyEntrant->carId));
          continue;
        }

        $entry = new StandaloneEventEntry();
        $entry->setStandaloneEventId($standaloneEventId);
        $entry->setCarId($carId);
        $entry->setUserId($userId);
        $entry->setStatus('confirmed');

        $eventClassId = $eventClassIdsByLegacyId[(int)$legacyEntrant->classId] ?? null;
        if ($eventClassId !== null) {
          $entry->setEventClassId($eventClassId);
        }

        $entry->save();
      }
    }

    /**
     * @throws Exception
     */
    private function migrateEvent(stdClass $legacyEvent, array $legacyClasses, array $legacySessions, array $legacyEntrants, MigrationRunResult $result): void {
      $legacyId = (int)$legacyEvent->id;

      if (!empty($legacyEvent->isTeamEvent)) {
        $result->recordSkipped(sprintf(__('Event %1$d (%2$s): team event, SLTK has no team concept yet, skipped.', 'sim-league-toolkit'), $legacyId, $legacyEvent->name));
        return;
      }

      try {
        if (MigrationRecordsRepository::isMigrated(self::ENTITY_KEY, $legacyId)) {
          $result->recordSkipped();
          return;
        }

        $legacyGameType = $legacyEvent->gameType ?? GameKey::AssettoCorsaCompetizione->value;
        $gameId = $this->gameKeyLookup->resolve($legacyGameType);
        if ($gameId === null) {
          throw new Exception(sprintf('unknown game "%s"', $legacyGameType));
        }

        [$trackId, $trackLayoutId] = $this->trackResolver->resolve((int)$legacyEvent->trackId, $gameId);

        $standaloneEvent = new StandaloneEvent();
        $standaloneEvent->setName($legacyEvent->name);
        $standaloneEvent->setDescription($legacyEvent->description ?? '');
        $standaloneEvent->setBannerImageUrl($legacyEvent->bannerImageUrl ?? '');
        $standaloneEvent->setGameId($gameId);
        $standaloneEvent->setTrackId($trackId);
        $standaloneEvent->setTrackLayoutId($trackLayoutId);
        $standaloneEvent->setEventDate($this->resolveEventDate($legacyEvent));
        $standaloneEvent->setIsActive((bool)$legacyEvent->isActive);
        $standaloneEvent->setStartTime(substr($legacyEvent->startTime ?? '', 0, 5));
        $standaloneEvent->setMaxEntrants((int)($legacyEvent->maxCarSlots ?? 0));
        $standaloneEvent->setScoringSetId($this->resolveScoringSetId($legacyClasses));
        $standaloneEvent->save();

        MigrationRecordsRepository::recordMigration(self::ENTITY_KEY, $legacyId, $standaloneEvent->getId());

        $eventClassIdsByLegacyId = $this->migrateEventClasses($legacyClasses, $gameId, $standaloneEvent->getId());
        $this->eventSessionMigrator->migrate($legacySessions, $legacyGameType, $standaloneEvent->getEventRefId());
        $this->migrateEntrants($legacyEntrants, $gameId, $standaloneEvent->getId(), $eventClassIdsByLegacyId, $result);

        $result->recordMigrated();
      } catch (Exception $e) {
        $result->recordFailed(sprintf(__('Event %1$d (%2$s): %3$s', 'sim-league-toolkit'), $legacyId, $legacyEvent->name ?? '', $e->getMessage()));
      }
    }

    /**
     * @return array<int, int> legacy eventClassId => SLTK eventClassId
     * @throws Exception
     */
    private function migrateEventClasses(array $legacyClasses, int $gameId, int $standaloneEventId): array {
      $idsByLegacyId = [];

      foreach ($legacyClasses as $legacyClass) {
        $driverCategoryId = $this->driverCategoryLookup->resolve($legacyClass->driverCategory);
        if ($driverCategoryId === null) {
          throw new Exception(sprintf('unknown driver category "%s" for class "%s"', $legacyClass->driverCategory, $legacyClass->name));
        }

        [$carClass, $singleCarId] = $this->carClassResolver->resolveCarClassAndSingleCarId(
          (bool)$legacyClass->isSingleCarClass,
          $legacyClass->carClass,
          (int)($legacyClass->singleCarId ?? 0),
          $gameId
        );

        $eventClassId = $this->eventClassCatalog->resolveOrCreate($legacyClass->name, $gameId, $driverCategoryId, $carClass, (bool)$legacyClass->isSingleCarClass, $singleCarId);

        StandaloneEvent::addStandaloneEventClass($standaloneEventId, $eventClassId);
        $idsByLegacyId[(int)$legacyClass->id] = $eventClassId;
      }

      return $idsByLegacyId;
    }

    private function resolveEventDate(stdClass $legacyEvent): DateTime {
      $date = DateTime::createFromFormat('Y-m-d', $legacyEvent->startDate);

      return $date ?: new DateTime();
    }

    /**
     * @throws Exception
     */
    private function resolveScoringSetId(array $legacyClasses): ?int {
      foreach ($legacyClasses as $legacyClass) {
        if (empty($legacyClass->scoringSetId)) {
          continue;
        }

        $targetId = MigrationRecordsRepository::getTargetId(self::SCORING_SET_ENTITY_KEY, (int)$legacyClass->scoringSetId);
        if ($targetId !== null) {
          return $targetId;
        }
      }

      return null;
    }
  }
