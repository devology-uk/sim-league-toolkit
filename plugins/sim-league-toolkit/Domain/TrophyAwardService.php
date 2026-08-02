<?php

  namespace SLTK\Domain;

  use Exception;
  use SLTK\Core\Enums\EventSessionType;
  use SLTK\Core\Enums\TrophyScope;

  class TrophyAwardService {
    /**
     * @throws Exception
     */
    public static function previewForStandaloneEvent(int $standaloneEventId): TrophyPreviewResult {
      $event = StandaloneEvent::get($standaloneEventId);

      if ($event === null) {
        return new TrophyPreviewResult(false, 'Standalone event not found.', []);
      }

      return self::previewForEvent(
        $event->getEventRefId(),
        fn(int $sessionId) => self::standaloneEligibleResults($sessionId)
      );
    }

    /**
     * @return Trophy[]
     * @throws Exception
     */
    public static function awardForStandaloneEvent(int $standaloneEventId): array {
      $event = StandaloneEvent::get($standaloneEventId);

      if ($event === null) {
        throw new Exception('Standalone event not found.');
      }

      $preview = self::previewForStandaloneEvent($standaloneEventId);
      $trophies = self::persist(TrophyScope::StandaloneEvent, $standaloneEventId, $preview);

      $event->setTrophiesAwarded(true);
      $event->save();

      return $trophies;
    }

    /**
     * @throws Exception
     */
    public static function previewForChampionshipEvent(int $championshipEventId): TrophyPreviewResult {
      $event = ChampionshipEvent::get($championshipEventId);

      if ($event === null) {
        return new TrophyPreviewResult(false, 'Championship event not found.', []);
      }

      return self::previewForEvent(
        $event->getEventRefId(),
        fn(int $sessionId) => self::championshipEligibleResults($sessionId)
      );
    }

    /**
     * @return Trophy[]
     * @throws Exception
     */
    public static function awardForChampionshipEvent(int $championshipEventId): array {
      $event = ChampionshipEvent::get($championshipEventId);

      if ($event === null) {
        throw new Exception('Championship event not found.');
      }

      $preview = self::previewForChampionshipEvent($championshipEventId);
      $trophies = self::persist(TrophyScope::ChampionshipEvent, $championshipEventId, $preview);

      $event->setTrophiesAwarded(true);
      $event->save();

      return $trophies;
    }

    /**
     * @throws Exception
     */
    public static function previewForChampionship(int $championshipId): TrophyPreviewResult {
      $championship = Championship::get($championshipId);

      if ($championship === null) {
        return new TrophyPreviewResult(false, 'Championship not found.', []);
      }

      $events = Championship::listEvents($championshipId);

      if (empty($events)) {
        return new TrophyPreviewResult(false, 'This championship has no events yet.', []);
      }

      $incomplete = array_filter($events, fn(ChampionshipEvent $e) => !$e->getIsCompleted());

      if (!empty($incomplete)) {
        return new TrophyPreviewResult(false, 'All events must be marked complete before championship trophies can be awarded.', []);
      }

      $standings = ChampionshipStandingsCalculator::calculate($championshipId);

      if (empty($standings)) {
        return new TrophyPreviewResult(false, 'No race results have been captured for this championship yet.', []);
      }

      return new TrophyPreviewResult(true, null, ChampionshipTrophyCalculator::calculate($standings));
    }

    /**
     * @return Trophy[]
     * @throws Exception
     */
    public static function awardForChampionship(int $championshipId): array {
      $championship = Championship::get($championshipId);

      if ($championship === null) {
        throw new Exception('Championship not found.');
      }

      $preview = self::previewForChampionship($championshipId);
      $trophies = self::persist(TrophyScope::Championship, $championshipId, $preview);

      $championship->setTrophiesAwarded(true);
      $championship->save();

      return $trophies;
    }

    /**
     * @return TrophyEligibleResult[]
     * @throws Exception
     */
    private static function standaloneEligibleResults(int $eventSessionId): array {
      return array_map(
        fn(StandaloneSessionResult $r) => TrophyEligibleResult::fromStandaloneResult($r),
        StandaloneSessionResult::listByEventSession($eventSessionId)
      );
    }

    /**
     * @return TrophyEligibleResult[]
     * @throws Exception
     */
    private static function championshipEligibleResults(int $eventSessionId): array {
      return array_map(
        fn(ChampionshipSessionResult $r) => TrophyEligibleResult::fromChampionshipResult($r),
        ChampionshipSessionResult::listByEventSession($eventSessionId)
      );
    }

    /**
     * @throws Exception
     */
    private static function previewForEvent(?int $eventRefId, callable $listEligibleResults): TrophyPreviewResult {
      if ($eventRefId === null) {
        return new TrophyPreviewResult(false, 'This event has no sessions yet.', []);
      }

      $sessions = ChampionshipEvent::listSessions($eventRefId);
      $raceSessions = array_values(array_filter(
        $sessions,
        fn(EventSession $s) => $s->getSessionType() === EventSessionType::Race->value
      ));

      if (empty($raceSessions)) {
        return new TrophyPreviewResult(false, 'This event has no Race session yet.', []);
      }

      $proposedTrophies = [];

      foreach ($raceSessions as $raceSession) {
        $raceResults = $listEligibleResults($raceSession->getId());
        $hasFinishers = !empty(array_filter($raceResults, fn(TrophyEligibleResult $r) => $r->getPosition() !== null));

        if (!$hasFinishers) {
          continue;
        }

        $poleSession = self::findPrecedingQualifyingSession($sessions, $raceSession);
        $poleResults = $poleSession !== null ? $listEligibleResults($poleSession->getId()) : [];

        array_push($proposedTrophies, ...EventTrophyCalculator::calculate($raceSession, $raceResults, $poleResults));
      }

      if (empty($proposedTrophies)) {
        return new TrophyPreviewResult(false, 'No race results have been captured for this event yet.', []);
      }

      return new TrophyPreviewResult(true, null, $proposedTrophies);
    }

    /**
     * @param EventSession[] $sessions sorted ascending by sortOrder
     */
    private static function findPrecedingQualifyingSession(array $sessions, EventSession $raceSession): ?EventSession {
      $lastQualifying = null;

      foreach ($sessions as $session) {
        if ($session->getId() === $raceSession->getId()) {
          break;
        }

        if ($session->getSessionType() === EventSessionType::Qualifying->value) {
          $lastQualifying = $session;
        }
      }

      return $lastQualifying;
    }

    /**
     * @return Trophy[]
     * @throws Exception
     */
    private static function persist(TrophyScope $scope, int $scopeId, TrophyPreviewResult $preview): array {
      if (!$preview->getCanAward()) {
        throw new Exception($preview->getReason() ?? 'Unable to award trophies.');
      }

      Trophy::deleteByScope($scope, $scopeId);

      return array_map(
        fn(ProposedTrophy $p) => $p->toTrophy($scope, $scopeId)->save(),
        $preview->getProposedTrophies()
      );
    }
  }
