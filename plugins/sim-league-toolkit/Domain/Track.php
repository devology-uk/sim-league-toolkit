<?php

  namespace SLTK\Domain;

  use Exception;
  use SLTK\Core\Constants;
  use SLTK\Database\Repositories\TrackRepository;
  use stdClass;

  class Track extends DomainBase {

    private string $country = '';
    private string $countryCode = '';
    private string $fullName = '';
    private int $gameId = Constants::DEFAULT_ID;
    private float $latitude = 0;
    private float $longitude = 0;
    private string $shortName = '';
    private string $trackId = '';

    public function __construct(stdClass $data = null) {
      parent::__construct($data);
      if ($data) {
        $this->gameId = $data->gameId;
        $this->trackId = $data->trackId;
        $this->shortName = $data->shortName;
        $this->fullName = $data->fullName;
        $this->country = $data->country;
        $this->countryCode = $data->countryCode;
        $this->latitude = $data->latitude;
        $this->longitude = $data->longitude;
      }
    }

    public static function get(int $id): Track|null {
      $queryResult = TrackRepository::getById($id);

      return new Track($queryResult);
    }

    /**
     * @return Track[]
     * @throws Exception
     */
    public static function list(): array {
      $queryResult = TrackRepository::list();

      return self::mapTracks($queryResult);
    }

    /**
     * @return Track[]
     * @throws Exception
     */
    public static function listForGame(int $gameId): array {
      $queryResult = TrackRepository::listForGame($gameId);

      return self::mapTracks($queryResult);
    }

    /**
     * @return TrackLayout[]
     * @throws Exception
     */
    public static function listLayoutsForTrack(int $trackId): array {
      $queryResult = TrackRepository::listLayoutsForTrack($trackId);

      return self::mapTrackLayouts($queryResult);
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

    public function save(): bool {
      return false;
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

      if ($this->getId() !== Constants::DEFAULT_ID) {
        $result['id'] = $this->getId();
      }

      return $result;
    }

    public function toDto(): array {
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

      if ($this->hasId()) {
        $result['id'] = $this->getId();
      }

      return $result;
    }

    private static function mapTracks(array $queryResults): array {
      $results = array();

      foreach ($queryResults as $item) {
        $results[] = new Track($item);
      }

      return $results;
    }

    private static function mapTrackLayouts(array $queryResults): array {
      $results = array();

      foreach($queryResults as $item) {
        $results[] = new TrackLayout($item);
      }

      return $results;
    }
  }