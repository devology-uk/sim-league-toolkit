<?php

  namespace SLTK\Migration;

  use Exception;
  use SLTK\Database\Repositories\RepositoryBase;
  use stdClass;

  class AccltLegacyDatabase extends RepositoryBase {
    private const string AMS2_SERVERS_TABLE = 'acclt_ams2_servers';
    private const string CARS_TABLE = 'acclt_cars';
    private const string CAR_DRIVER_CLASSES_TABLE = 'acclt_car_driver_classes';
    private const string NATIONALITIES_TABLE = 'acclt_nationalities';
    private const string PLATFORMS_TABLE = 'acclt_platforms';
    private const string SCORING_SET_SCORES_TABLE = 'acclt_scoring_set_scores';
    private const string SCORING_SETS_TABLE = 'acclt_scoring_sets';
    private const string SERVERS_TABLE = 'acclt_servers';
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
    public static function getUserProfiles(): array {
      return self::getResultsFromTable(self::USER_PROFILE_TABLE);
    }
  }
