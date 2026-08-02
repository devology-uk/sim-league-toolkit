<?php

  namespace SLTK\Migration;

  use Exception;
  use SLTK\Database\Repositories\RepositoryBase;
  use stdClass;

  class AcclLegacyDatabase extends RepositoryBase {
    private const string NATIONALITIES_TABLE = 'acclt_nationalities';
    private const string USER_PROFILE_TABLE = 'acclt_user_profile';

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
    public static function getUserProfiles(): array {
      return self::getResultsFromTable(self::USER_PROFILE_TABLE);
    }
  }
