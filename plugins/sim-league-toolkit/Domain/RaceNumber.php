<?php

  namespace SLTK\Domain;

  use SLTK\Core\UserMetaKeys;
  use SLTK\Database\Repositories\RaceNumberRepository;
  use stdClass;

  class RaceNumber extends DomainBase {

    public int $raceNumber = 0;

    public function __construct(stdClass $data = null) {
      if($data) {
        $this->id = $data->userId;
        $this->raceNumber = $data->raceNumber;
      }
    }

    /**
     * @param int $userId
     * @param int $raceNumber
     *
     * @return void
     */
    public static function allocate(int $userId, int $raceNumber): void {
      self::reset($raceNumber);
      update_user_meta($userId, UserMetaKeys::RACE_NUMBER, $raceNumber);
    }

    /**
     * @inheritDoc
     */
    public static function get(int $id): object|null {
      // TODO: Implement get() method.
      return null;
    }

    /**
     * @inheritDoc
     */
    public static function list(): array {
      return RaceNumberRepository::listActiveAllocations();
    }

    /**
     * @inheritDoc
     */
    public function save(): bool {
      // TODO: Implement save() method.
      return false;
    }

    /**
     * @return int[]
     */
    public static function listAvailable(): array {
      $possibleNumbers = range(1, 99);
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

    /**
     * @param int $raceNumber The race number to be reset if allocated
     *
     * @return void
     */
    public static function reset(int $raceNumber): void {
      $users = get_users(['fields' => 'all_with_meta']);
      foreach($users as $user) {
        if($user->get(UserMetaKeys::RACE_NUMBER) === $raceNumber) {
          update_user_meta($user->ID, UserMetaKeys::RACE_NUMBER, 0);
        }
      }
    }

    /**
     * @return array Representation of properties as array for data transfer via rest api
     */
    public function toDto(): array {
      $result = [
        'userId'     => $this->id,
        'raceNumber' => $this->raceNumber
      ];

      $userData = get_userdata($this->id);
      $firstName = $userData->first_name;
      $lastName = $userData->last_name;
      $displayName = $userData->display_name;
      if(!empty($displayName)) {
        $displayName = $firstName . ' ' . $lastName;
      }
      $result['userDisplayName'] = trim($displayName);

      return $result;
    }
  }