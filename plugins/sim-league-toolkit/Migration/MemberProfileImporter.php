<?php

  namespace SLTK\Migration;

  use Exception;
  use SLTK\Core\UserMetaKeys;
  use SLTK\Database\Repositories\MigrationRecordsRepository;
  use SLTK\Domain\Country;
  use SLTK\Domain\RaceNumber;
  use stdClass;

  class MemberProfileImporter implements MigrationImporter {
    private const string ENTITY_KEY = 'member-profile';
    private const int UNSET_NATIONALITY_CODE = 0;

    public function getEntityKey(): string {
      return self::ENTITY_KEY;
    }

    public function getLabel(): string {
      return __('Member profiles', 'sim-league-toolkit');
    }

    public function run(): MigrationRunResult {
      $result = new MigrationRunResult();
      $countryIdsByAlpha3 = $this->buildCountryLookup();
      $flagIconNamesByNationalityCode = $this->buildNationalityLookup();

      foreach (AcclLegacyDatabase::getUserProfiles() as $profile) {
        $this->migrateProfile($profile, $countryIdsByAlpha3, $flagIconNamesByNationalityCode, $result);
      }

      return $result;
    }

    private function buildCountryLookup(): array {
      $lookup = [];

      foreach (Country::list() as $country) {
        $lookup[strtoupper($country->getAlpha3())] = $country->getId();
      }

      return $lookup;
    }

    private function buildNationalityLookup(): array {
      $lookup = [];

      foreach (AcclLegacyDatabase::getNationalities() as $nationality) {
        $lookup[(int)$nationality->accNationalityCode] = $nationality->flagIconName;
      }

      return $lookup;
    }

    private function migrateCountry(int $userId, int $nationalityCode, array $flagIconNamesByNationalityCode, array $countryIdsByAlpha3): void {
      if ($nationalityCode === self::UNSET_NATIONALITY_CODE) {
        return;
      }

      $flagIconName = $flagIconNamesByNationalityCode[$nationalityCode] ?? null;
      $countryId = $flagIconName !== null ? ($countryIdsByAlpha3[strtoupper($flagIconName)] ?? null) : null;

      if ($countryId !== null) {
        update_user_meta($userId, UserMetaKeys::COUNTRY_ID, $countryId);
      }
    }

    private function migrateProfile(stdClass $profile, array $countryIdsByAlpha3, array $flagIconNamesByNationalityCode, MigrationRunResult $result): void {
      $userId = (int)$profile->userId;

      if ($userId <= 0) {
        $result->recordSkipped();
        return;
      }

      try {
        if (MigrationRecordsRepository::isMigrated(self::ENTITY_KEY, $userId)) {
          $result->recordSkipped();
          return;
        }

        if (get_user_by('id', $userId) === false) {
          $result->recordSkipped(sprintf(__('User %d: no matching WordPress account, skipped.', 'sim-league-toolkit'), $userId));
          return;
        }

        update_user_meta($userId, UserMetaKeys::STEAM_ID, $profile->steamId);
        update_user_meta($userId, UserMetaKeys::PLAYSTATION_ID, $profile->playStationId);

        $this->migrateCountry($userId, (int)$profile->accNationalityCode, $flagIconNamesByNationalityCode, $countryIdsByAlpha3);
        $this->migrateRaceNumber($userId, (int)$profile->raceNumber, $result);

        MigrationRecordsRepository::recordMigration(self::ENTITY_KEY, $userId, $userId);
        $result->recordMigrated();
      } catch (Exception $e) {
        $result->recordFailed(sprintf(__('User %1$d: %2$s', 'sim-league-toolkit'), $userId, $e->getMessage()));
      }
    }

    private function migrateRaceNumber(int $userId, int $raceNumber, MigrationRunResult $result): void {
      if ($raceNumber === 0 || !RaceNumber::isValid($raceNumber)) {
        return;
      }

      $currentHolders = array_map('intval', get_users([
        'meta_key' => UserMetaKeys::RACE_NUMBER,
        'meta_value' => $raceNumber,
        'fields' => 'ID',
      ]));
      $heldByAnotherUser = array_diff($currentHolders, [$userId]);

      if (!empty($heldByAnotherUser)) {
        $result->addWarning(sprintf(
          __('User %1$d: race number %2$d already held by user %3$d, left unchanged.', 'sim-league-toolkit'),
          $userId,
          $raceNumber,
          reset($heldByAnotherUser)
        ));
        return;
      }

      RaceNumber::allocate($userId, $raceNumber);
    }
  }
