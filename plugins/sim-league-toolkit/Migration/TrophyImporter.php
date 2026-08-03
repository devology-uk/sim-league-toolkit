<?php

  namespace SLTK\Migration;

  use DateTime;
  use DateTimeZone;
  use Exception;
  use SLTK\Core\Enums\TrophyAwardType;
  use SLTK\Core\Enums\TrophyScope;
  use SLTK\Database\Repositories\MigrationRecordsRepository;
  use SLTK\Domain\Championship;
  use SLTK\Domain\ChampionshipEvent;
  use SLTK\Domain\StandaloneEvent;
  use SLTK\Domain\Trophy;
  use stdClass;

  /**
   * Migrates ACCLT's `acclt_user_trophies` - a denormalized, append-only award log (playerId is a
   * Steam/PSN-prefixed string, class is embedded as free text in the award string, e.g.
   * "Winner in Class - GT3") into SLTK's structured `Trophy` records.
   *
   * ACCLT never recorded which Race session a trophy came from - confirmed via the real data that
   * every trophy'd event has exactly one Race session, so this is resolved unambiguously per event
   * rather than being a genuine gap. ACCLT never awarded Fastest Lap at all (no such trophy type
   * exists in the legacy data) and never awarded an overall (non-class) championship trophy - both
   * are simply absent from what gets migrated, not skipped.
   *
   * Track Master championships/events and the one team event are already unmigrated by earlier
   * importers, so their trophies naturally can't resolve a scope id either - skipped consistently
   * with every other phase of this migration, no special-casing needed here.
   */
  class TrophyImporter implements MigrationImporter {
    private const string ENTITY_KEY = 'trophy';
    private const string EVENT_SESSION_ENTITY_KEY = 'event-session';
    private const string STANDALONE_EVENT_ENTITY_KEY = 'standalone-event';
    private const string CHAMPIONSHIP_EVENT_ENTITY_KEY = 'championship-event';
    private const string CHAMPIONSHIP_ENTITY_KEY = 'championship';
    private const string CLASS_AWARD_DELIMITER = ' in Class - ';
    private const string POLE_AWARD = 'Pole';

    private EventClassCatalog $eventClassCatalog;
    private array $legacyEventsById;
    private array $legacyRaceSessionIdByEventId;
    private SteamIdUserLookup $steamIdUserLookup;

    public function getEntityKey(): string {
      return self::ENTITY_KEY;
    }

    public function getLabel(): string {
      return __('Trophies', 'sim-league-toolkit');
    }

    /**
     * @throws Exception
     */
    public function run(): MigrationRunResult {
      $result = new MigrationRunResult();

      $this->eventClassCatalog = new EventClassCatalog();
      $this->steamIdUserLookup = new SteamIdUserLookup();
      $this->legacyEventsById = $this->indexById(AccltLegacyDatabase::getEvents());
      $this->legacyRaceSessionIdByEventId = $this->buildRaceSessionIdByEventId();

      foreach (AccltLegacyDatabase::getUserTrophies() as $legacyTrophy) {
        $this->migrateTrophy($legacyTrophy, $result);
      }

      return $result;
    }

    /**
     * @throws Exception
     */
    private function buildRaceSessionIdByEventId(): array {
      $lookup = [];

      foreach (AccltLegacyDatabase::getEventSessions() as $session) {
        if ($session->sessionType === 'R') {
          $lookup[(int)$session->eventId] = (int)$session->id;
        }
      }

      return $lookup;
    }

    /**
     * @return array<int, stdClass>
     */
    private function indexById(array $rows): array {
      $lookup = [];

      foreach ($rows as $row) {
        $lookup[(int)$row->id] = $row;
      }

      return $lookup;
    }

    /**
     * @throws Exception
     */
    private function migrateTrophy(stdClass $legacyTrophy, MigrationRunResult $result): void {
      $legacyId = (int)$legacyTrophy->id;

      try {
        if (MigrationRecordsRepository::isMigrated(self::ENTITY_KEY, $legacyId)) {
          $result->recordSkipped();
          return;
        }

        $userId = $this->steamIdUserLookup->resolve($legacyTrophy->playerId);
        if ($userId === null) {
          $result->recordSkipped(sprintf(__('Trophy %1$d: player %2$s could not be matched to a user, skipped.', 'sim-league-toolkit'), $legacyId, $legacyTrophy->playerId));
          return;
        }

        $awardType = $this->resolveAwardType($legacyTrophy);
        if ($awardType === null) {
          $result->recordFailed(sprintf(__('Trophy %1$d: could not interpret award text "%2$s" (position %3$d).', 'sim-league-toolkit'), $legacyId, $legacyTrophy->award, (int)$legacyTrophy->position));
          return;
        }

        $scoping = $this->resolveScope($legacyTrophy);
        if ($scoping === null) {
          $result->recordSkipped(sprintf(__('Trophy %1$d: its %2$s (legacy id %3$d) was not migrated, skipped.', 'sim-league-toolkit'), $legacyId, $legacyTrophy->awardType, (int)$legacyTrophy->awardTargetId));
          return;
        }
        [$scope, $scopeId, $eventSessionId, $gameId] = $scoping;

        $className = $this->extractClassName($legacyTrophy->award);
        $eventClassId = null;
        if ($className !== null) {
          $eventClassId = $this->eventClassCatalog->tryResolve($className, $gameId);
          if ($eventClassId === null) {
            $result->recordFailed(sprintf(__('Trophy %1$d: class "%2$s" could not be matched, skipped.', 'sim-league-toolkit'), $legacyId, $className));
            return;
          }
        }

        $trophy = new Trophy();
        $trophy->setMemberId($userId);
        $trophy->setScope($scope);
        $trophy->setScopeId($scopeId);
        $trophy->setEventSessionId($eventSessionId);
        $trophy->setEventClassId($eventClassId);
        $trophy->setAwardType($awardType);
        $trophy->setAwardedDate($this->resolveAwardedDate($legacyTrophy));
        $trophy->save();

        MigrationRecordsRepository::recordMigration(self::ENTITY_KEY, $legacyId, $trophy->getId());
        $result->recordMigrated();
      } catch (Exception $e) {
        $result->recordFailed(sprintf(__('Trophy %1$d: %2$s', 'sim-league-toolkit'), $legacyId, $e->getMessage()));
      }
    }

    private function extractClassName(string $award): ?string {
      $delimiterPosition = strpos($award, self::CLASS_AWARD_DELIMITER);

      return $delimiterPosition === false ? null : trim(substr($award, $delimiterPosition + strlen(self::CLASS_AWARD_DELIMITER)));
    }

    private function resolveAwardedDate(stdClass $legacyTrophy): DateTime {
      $date = DateTime::createFromFormat('Y-m-d H:i:s', $legacyTrophy->awardDate, new DateTimeZone('UTC'));

      return $date ?: new DateTime('now', new DateTimeZone('UTC'));
    }

    private function resolveAwardType(stdClass $legacyTrophy): ?TrophyAwardType {
      if (str_starts_with($legacyTrophy->award, self::POLE_AWARD)) {
        return TrophyAwardType::Pole;
      }

      return TrophyAwardType::fromPodiumPosition((int)$legacyTrophy->position);
    }

    /**
     * @return array{0: TrophyScope, 1: int, 2: ?int, 3: int}|null [scope, scopeId, eventSessionId, gameId]
     * @throws Exception
     */
    private function resolveScope(stdClass $legacyTrophy): ?array {
      $awardTargetId = (int)$legacyTrophy->awardTargetId;

      if ($legacyTrophy->awardType === 'championship') {
        $championshipId = MigrationRecordsRepository::getTargetId(self::CHAMPIONSHIP_ENTITY_KEY, $awardTargetId);
        if ($championshipId === null) {
          return null;
        }

        $gameId = Championship::get($championshipId)?->getGameId();
        if ($gameId === null) {
          throw new Exception(sprintf('migrated championship %d could not be reloaded', $championshipId));
        }

        return [TrophyScope::Championship, $championshipId, null, $gameId];
      }

      $legacyEvent = $this->legacyEventsById[$awardTargetId] ?? null;
      if ($legacyEvent === null) {
        return null;
      }

      $legacyRaceSessionId = $this->legacyRaceSessionIdByEventId[$awardTargetId] ?? null;
      $eventSessionId = $legacyRaceSessionId !== null
        ? MigrationRecordsRepository::getTargetId(self::EVENT_SESSION_ENTITY_KEY, $legacyRaceSessionId)
        : null;

      if (empty($legacyEvent->championshipId)) {
        $standaloneEventId = MigrationRecordsRepository::getTargetId(self::STANDALONE_EVENT_ENTITY_KEY, $awardTargetId);
        if ($standaloneEventId === null) {
          return null;
        }

        $gameId = StandaloneEvent::get($standaloneEventId)?->getGameId();
        if ($gameId === null) {
          throw new Exception(sprintf('migrated standalone event %d could not be reloaded', $standaloneEventId));
        }

        return [TrophyScope::StandaloneEvent, $standaloneEventId, $eventSessionId, $gameId];
      }

      $championshipEventId = MigrationRecordsRepository::getTargetId(self::CHAMPIONSHIP_EVENT_ENTITY_KEY, $awardTargetId);
      if ($championshipEventId === null) {
        return null;
      }

      $championshipId = MigrationRecordsRepository::getTargetId(self::CHAMPIONSHIP_ENTITY_KEY, (int)$legacyEvent->championshipId);
      if ($championshipId === null) {
        return null;
      }

      $gameId = Championship::get($championshipId)?->getGameId();
      if ($gameId === null) {
        throw new Exception(sprintf('migrated championship %d could not be reloaded', $championshipId));
      }

      return [TrophyScope::ChampionshipEvent, $championshipEventId, $eventSessionId, $gameId];
    }
  }
