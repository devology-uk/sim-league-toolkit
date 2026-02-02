<?php

  namespace SLTK\Domain;

  use SLTK\Core\Constants;
  use SLTK\Domain\Abstractions\ValueObject;
  use SLTK\Domain\Traits\HasIdentity;
  use stdClass;

  class Track implements ValueObject {
    use HasIdentity;

    private string $country = '';
    private string $countryCode = '';
    private string $fullName = '';
    private int $gameId = Constants::DEFAULT_ID;
    private float $latitude = 0;
    private float $longitude = 0;
    private string $shortName = '';
    private string $trackId = '';

    public static function fromStdClass(?stdClass $data): ?self {
      if (!$data) {
        return null;
      }

      $result = new self();

      $result->setId($data->id);
      $result->setGameId($data->gameId);
      $result->setShortName($data->shortName);
      $result->setFullName($data->fullName);
      $result->setCountry($data->country);
      $result->setCountryCode($data->countryCode);
      $result->setLatitude($data->latitude);
      $result->setLongitude($data->longitude);
      $result->setLongitude($data->longitude);
      $result->setTrackId($data->trackId);

      return $result;
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

    public function toDto(): array {
      return [
        'id' => $this->getId(),
        'gameId' => $this->gameId,
        'trackId' => $this->trackId,
        'shortName' => $this->shortName,
        'fullName' => $this->fullName,
        'country' => $this->country,
        'countryCode' => $this->countryCode,
        'latitude' => $this->latitude,
        'longitude' => $this->longitude,
      ];
    }

    private function setCountry(string $country): void {
      $this->country = $country;
    }

    private function setCountryCode(string $countryCode): void {
      $this->countryCode = $countryCode;
    }

    private function setFullName(string $fullName): void {
      $this->fullName = $fullName;
    }

    private function setGameId(int $gameId): void {
      $this->gameId = $gameId;
    }

    private function setLatitude(float $latitude): void {
      $this->latitude = $latitude;
    }

    private function setLongitude(float $longitude): void {
      $this->longitude = $longitude;
    }

    private function setShortName(string $shortName): void {
      $this->shortName = $shortName;
    }

    private function setTrackId(int $trackId): void {
      $this->trackId = $trackId;
    }

  }