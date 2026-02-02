<?php

  namespace SLTK\Domain;

  use SLTK\Core\UserMetaKeys;
  use stdClass;

  class RaceNumber {
    public final const int MAXIMUM = 998;
    public final const int MINIMUM = 1;

    public static function allocate(int $userId, int $raceNumber): void {
      self::reset($raceNumber);
      update_user_meta($userId, UserMetaKeys::RACE_NUMBER, $raceNumber);
    }

    /**
     * @return int[]
     */
    public static function getRange(): array {
      return range(self::MINIMUM, self::MAXIMUM);
    }

    public static function isValid(int $raceNumber): bool {
      return $raceNumber >= self::MINIMUM && $raceNumber <= self::MAXIMUM;
    }

    /**
     * @return int[]
     */
    public static function listAvailable(): array {
      $possibleNumbers = self::getRange();
      $users = get_users(['fields' => 'ID']);
      $results = [];

      foreach($possibleNumbers as $possibleNumber) {
        $allocated = false;
        for($i = 0; $i < count($users); $i++) {
          $userId = (int)$users[$i];
          if((int)get_user_meta($userId, UserMetaKeys::RACE_NUMBER, true) === $possibleNumber) {
            $allocated = true;
            break;
          }
        }
        if(!$allocated) {
          $results[] = $possibleNumber;
        }
      }

      return $results;
    }

    public static function reset(int $raceNumber): void {
      $users = get_users(['fields' => 'all_with_meta']);
      foreach($users as $user) {
        if($user->get(UserMetaKeys::RACE_NUMBER) === $raceNumber) {
          update_user_meta($user->ID, UserMetaKeys::RACE_NUMBER, 0);
        }
      }
    }
  }