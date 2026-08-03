<?php

  namespace SLTK\Migration;

  use Exception;
  use SLTK\Database\Repositories\RepositoryBase;
  use stdClass;

  class AccltLegacyDatabase extends RepositoryBase {
    private const string AMS2_SERVERS_TABLE = 'acclt_ams2_servers';
    private const string CARS_TABLE = 'acclt_cars';
    private const string CAR_DRIVER_CLASSES_TABLE = 'acclt_car_driver_classes';
    private const string CHAMPIONSHIPS_TABLE = 'acclt_championships';
    private const string CHAMPIONSHIP_CLASSES_TABLE = 'acclt_championship_classes';
    private const string CHAMPIONSHIP_ENTRANTS_TABLE = 'acclt_championship_entrants';
    private const string EVENTS_TABLE = 'acclt_events';
    private const string EVENT_CLASSES_TABLE = 'acclt_event_classes';
    private const string EVENT_ENTRANTS_TABLE = 'acclt_event_entrants';
    private const string EVENT_SESSIONS_TABLE = 'acclt_event_sessions';
    private const string NATIONALITIES_TABLE = 'acclt_nationalities';
    private const string PLATFORMS_TABLE = 'acclt_platforms';
    private const string SCORING_SET_SCORES_TABLE = 'acclt_scoring_set_scores';
    private const string SCORING_SETS_TABLE = 'acclt_scoring_sets';
    private const string SERVERS_TABLE = 'acclt_servers';
    private const string TRACKS_TABLE = 'acclt_tracks';
    private const string USER_PROFILE_TABLE = 'acclt_user_profile';

    /**
     * @return stdClass[]
     * @throws Exception
     */
    public static function getAms2Servers(): array {
      return self::getResultsFromTable(self::AMS2_SERVERS_TABLE);
    }

    /**
     * @return stdClass[]
     * @throws Exception
     */
    public static function getCarDriverClasses(): array {
      return self::getResultsFromTable(self::CAR_DRIVER_CLASSES_TABLE);
    }

    /**
     * @return stdClass[]
     * @throws Exception
     */
    public static function getCars(): array {
      return self::getResultsFromTable(self::CARS_TABLE);
    }

    /**
     * @return stdClass[]
     * @throws Exception
     */
    public static function getChampionships(): array {
      return self::getResultsFromTable(self::CHAMPIONSHIPS_TABLE);
    }

    /**
     * Per-championship class rows (`championshipId` set) - the championship-scoped equivalent of
     * `acclt_event_classes` (standalone events).
     *
     * @return stdClass[]
     * @throws Exception
     */
    public static function getChampionshipClasses(): array {
      return self::getResultsFromTable(self::CHAMPIONSHIP_CLASSES_TABLE);
    }

    /**
     * The championship's season-long entrant roster - distinct from `acclt_event_entrants`, which
     * tracks per-round attendance and has no SLTK equivalent for championship events.
     *
     * @return stdClass[]
     * @throws Exception
     */
    public static function getChampionshipEntrants(): array {
      return self::getResultsFromTable(self::CHAMPIONSHIP_ENTRANTS_TABLE);
    }

    /**
     * Standalone (championshipId IS NULL) and championship events alike; callers filter as needed.
     *
     * @return stdClass[]
     * @throws Exception
     */
    public static function getEvents(): array {
      return self::getResultsFromTable(self::EVENTS_TABLE);
    }

    /**
     * Per-event class rows (`eventId` set) - distinct from the reusable "car driver class" templates.
     *
     * @return stdClass[]
     * @throws Exception
     */
    public static function getEventClasses(): array {
      return self::getResultsFromTable(self::EVENT_CLASSES_TABLE);
    }

    /**
     * @return stdClass[]
     * @throws Exception
     */
    public static function getEventEntrants(): array {
      return self::getResultsFromTable(self::EVENT_ENTRANTS_TABLE);
    }

    /**
     * @return stdClass[]
     * @throws Exception
     */
    public static function getEventSessions(): array {
      return self::getResultsFromTable(self::EVENT_SESSIONS_TABLE);
    }

    /**
     * @return stdClass[]
     * @throws Exception
     */
    public static function getNationalities(): array {
      return self::getResultsFromTable(self::NATIONALITIES_TABLE);
    }

    /**
     * @return stdClass[]
     * @throws Exception
     */
    public static function getPlatforms(): array {
      return self::getResultsFromTable(self::PLATFORMS_TABLE);
    }

    /**
     * @return stdClass[]
     * @throws Exception
     */
    public static function getScoringSetScores(): array {
      return self::getResultsFromTable(self::SCORING_SET_SCORES_TABLE);
    }

    /**
     * @return stdClass[]
     * @throws Exception
     */
    public static function getScoringSets(): array {
      return self::getResultsFromTable(self::SCORING_SETS_TABLE);
    }

    /**
     * @return stdClass[]
     * @throws Exception
     */
    public static function getServers(): array {
      return self::getResultsFromTable(self::SERVERS_TABLE);
    }

    /**
     * @return stdClass[]
     * @throws Exception
     */
    public static function getTracks(): array {
      return self::getResultsFromTable(self::TRACKS_TABLE);
    }

    /**
     * @return stdClass[]
     * @throws Exception
     */
    public static function getUserProfiles(): array {
      return self::getResultsFromTable(self::USER_PROFILE_TABLE);
    }
  }
