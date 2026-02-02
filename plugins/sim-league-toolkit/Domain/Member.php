<?php

  namespace SLTK\Domain;

  use SLTK\Core\UserMetaKeys;
  use SLTK\Domain\Abstractions\Listable;
  use SLTK\Domain\Traits\HasIdentity;
  use WP_User;

  class Member implements Listable {
    use HasIdentity;

    private string $displayName = '';
    private string $firstName = '';
    private string $lastName = '';
    private string $playStationId = '';
    private int $raceNumber = 0;
    private string $steamId = '';
    private string $username = '';
    private string $xBoxId = '';

    public static function get(int $id): Member|null {
      $user = get_user_by('id', $id);
      if (isset($user->ID)) {
        return self::createInstance($user);
      }

      return null;
    }

    public static function list(): array {
      $users = get_users(['fields' => 'all_with_meta']);

      return array_map(function ($user) {
        return self::createInstance($user);
      }, $users);
    }

    public function getDisplayName(): string {
      return $this->displayName ?? '';
    }

    public function getFirstName(): string {
      return $this->firstName ?? '';
    }

    public function getLastName(): string {
      return $this->lastName ?? '';
    }

    public function getPlayStationId(): string {
      return $this->playStationId ?? '';
    }

    public function getRaceNumber(): int {
      return $this->raceNumber;
    }

    public function getSteamId(): string {
      return $this->steamId ?? '';
    }

    public function getUsername(): string {
      return $this->username ?? '';
    }

    public function getXBoxId(): string {
      return $this->xBoxId ?? '';
    }

    public function toDto(): array {
      return [
        'id' => $this->getId(),
        'displayName' => $this->getDisplayName(),
        'firstName' => $this->getFirstName(),
        'lastName' => $this->getLastName(),
        'playStationId' => $this->getPlayStationId(),
        'raceNumber' => $this->getRaceNumber(),
        'steamId' => $this->getSteamId(),
        'username' => $this->getUsername(),
        'xBoxId' => $this->getXBoxId(),
      ];
    }

    private static function createInstance(WP_User $user): Member {
      $result = new self();

      $result->setId($user->ID);
      $result->setDisplayName($user->display_name);
      $result->setFirstName($user->first_name);
      $result->setLastName($user->last_name);
      $result->setPlayStationId($user->get(UserMetaKeys::PLAYSTATION_ID) ?? '');
      $result->setRaceNumber((int)($user->get(UserMetaKeys::RACE_NUMBER) ?? 0));
      $result->setSteamId($user->get(UserMetaKeys::STEAM_ID) ?? '');
      $result->setUsername($user->user_login);
      $result->setXBoxId($user->get(UserMetaKeys::XBOX_ID) ?? '');

      return $result;
    }

    private function setDisplayName(string $value): void {
      $this->displayName = $value;
    }

    private function setFirstName(string $value): void {
      $this->firstName = $value;
    }

    private function setLastName(string $value): void {
      $this->lastName = $value;
    }

    private function setPlayStationId(string $value): void {
      $this->playStationId = $value;
    }

    private function setRaceNumber(int $value): void {
      $this->raceNumber = $value;
    }

    private function setSteamId(string $value): void {
      $this->steamId = $value;
    }

    private function setUsername(string $value): void {
      $this->username = $value;
    }

    private function setXBoxId(string $value): void {
      $this->xBoxId = $value;
    }
  }