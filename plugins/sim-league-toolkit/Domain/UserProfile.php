<?php

  namespace SLTK\Domain;

  use stdClass;

  class UserProfile extends DomainBase {

    private string $avatarUrl;
    private string $countryCode;
    private int $countryId;
    private string $displayName;
    private string $firstName;
    private string $lastName;
    private string $playStationId;
    private int $raceNumber;
    private string $steamId;
    private int $userId = 0;

    public function __construct(?stdClass $data = null) {
      if ($data) {
        $this->userId = $data->userId;
        $this->avatarUrl = $data->avatarUrl;
        $this->countryCode = $data->countryCode;
        $this->countryId = $data->countryId;
        $this->displayName = $data->displayName;
        $this->firstName = $data->firstName;
        $this->lastName = $data->lastName;
        $this->playStationId = $data->playStationId;
        $this->raceNumber = $data->raceNumber;
        $this->steamId = $data->steamId;
      }
    }

    public static function get(int $id): object|null {
      return null;
    }

    public static function list(): array {
      return [];
    }

    public function getAvatarUrl() {
      return $this->avatarUrl ?? '';
    }

    public function setAvatarUrl(string $value): void {
      $this->avatarUrl = $value;
    }


    public function getCountryCode() {
      return $this->countryCode ?? '';
    }

    public function setCountryCode(string $value): void {
      $this->countryCode = $value;
    }

    public function getCountryId() {
      return $this->countryId ?? 0;
    }

    public function setCountryId(int $value): void {
      $this->countryId = $value;
    }

    public function getDisplayName() {
      return $this->displayName ?? '';
    }

    public function setDisplayName(string $value): void {
      $this->displayName = $value;
    }

    public function getFirstName() {
      return $this->firstName ?? '';
    }

    public function setFirstName(string $value): void {
      $this->firstName = $value;
    }

    public function getLastName() {
      return $this->lastName ?? '';
    }

    public function setLastName(string $value): void {
      $this->lastName = $value;
    }

    public function getPlayStationId() {
      return $this->playStationId ?? '';
    }

    public function setPlayStationId(string $value): void {
      $this->playStationId = $value;
    }

    public function getRaceNumber() {
      return $this->raceNumber ?? 999;
    }

    public function setRaceNumber(int $value): void {
      $this->raceNumber = $value;
    }

    public function getSteamId() {
      return $this->steamId ?? '';
    }

    public function setSteamId(string $value): void {
      $this->steamId = $value;
    }

    public function getUserId() {
      return $this->userId ?? 0;
    }

    public function setUserId(int $value): void {
      $this->userId = $value;
    }

    public function save(): bool {
      return false;
    }
  }