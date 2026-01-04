<?php

  namespace SLTK\Domain;

  use SLTK\Core\UserMetaKeys;
  use WP_User;

  class Member {
    public int $id = 0;
    public string $displayName = '';
    public string $firsName = '';
    public string $lastName = '';
    public string $playStationId = '';
    public int $raceNumber = 0;
    public string $steamId = '';
    public string $username = '';
    public string $xBoxId = '';

    public static function get(int $id): Member|null {
      $user = get_user_by('id', $id);
      if(isset($user->ID)) {
        return self::createInstance($user);
      }

      return null;
    }

    public static function list(): array {
      $users = get_users(['fields' => 'all_with_meta']);

      return array_map(function($user) {
        return self::createInstance($user);
      }, $users);
    }

    public function save(): bool {
      return false;
    }

    private static function createInstance(WP_User $user): Member {
      $result = new self();

      $result->id = $user->ID;
      $result->displayName = $user->display_name;
      $result->firsName = $user->first_name;
      $result->lastName = $user->last_name;
      $result->playStationId = $user->get(UserMetaKeys::PLAYSTATION_ID) ?? '';
      $result->raceNumber = (int)($user->get(UserMetaKeys::RACE_NUMBER) ?? 0);
      $result->steamId = $user->get(UserMetaKeys::STEAM_ID) ?? '';
      $result->username = $user->user_login;
      $result->xBoxId = $user->get(UserMetaKeys::XBOX_ID) ?? '';

      return $result;
    }
  }