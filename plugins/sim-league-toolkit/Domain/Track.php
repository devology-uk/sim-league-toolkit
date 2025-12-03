<?php

  namespace SLTK\Domain;

  use SLTK\Core\Constants;
  use stdClass;

  class Track {

    private string $country = '';
    private string $countryCode = '';
    private string $fullName = '';
    private int $gameId = Constants::DEFAULT_ID;
    private int $id = Constants::DEFAULT_ID;
    private float $latitude = 0;
    private float $longitude = 0;
    private string $shortName = '';
    private string $trackId = '';

    public function __construct(stdClass $data = null) {
      if ($data) {
        $this->gameId = $data->gameId;
        $this->trackId = $data->trackId;
        $this->shortName = $data->shortName;
        $this->fullName = $data->fullName;
        $this->country = $data->country;
        $this->countryCode = $data->countryCode;
        $this->latitude = $data->latitude;
        $this->longitude = $data->longitude;

        if (isset($data->id)) {
          $this->id = $data->id;
        }
      }
    }

    public function getCountry(): string {
      return $this->country;
    }

    public function getCountryCode(): string {
      return $this->countryCode;
    }

    public function getFullName(): string {
      return $this->fullName;
    }

    public function getGameId(): int {
      return $this->gameId;
    }

    public function getId(): int {
      return $this->id;
    }

    public function getLatitude(): float {
      return $this->latitude;
    }

    public function getLongitude(): float {
      return $this->longitude;
    }

    public function getShortName(): string {
      return $this->shortName;
    }

    public function getTrackId(): string {
      return $this->trackId;
    }

    public function toArray(): array {
      $result = [
        'gameId' => $this->gameId,
        'trackId' => $this->trackId,
        'shortName' => $this->shortName,
        'fullName' => $this->fullName,
        'country' => $this->country,
        'countryCode' => $this->countryCode,
        'latitude' => $this->latitude,
        'longitude' => $this->longitude,
      ];

      if ($this->id !== Constants::DEFAULT_ID) {
        $result['id'] = $this->id;
      }

      return $result;
    }
  }