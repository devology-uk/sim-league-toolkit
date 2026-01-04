<?php

  namespace SLTK\Domain;

  use Exception;
  use SLTK\Database\Repositories\CarRepository;
  use SLTK\Database\Repositories\GameRepository;
  use SLTK\Database\Repositories\TrackRepository;
  use stdClass;

  class Game extends DomainBase {
    private string $latestVersion = '';
    private string $name = '';
    private bool $published = false;
    private bool $supportsLayouts = false;
    private bool $supportsResultUpload = false;

    public function __construct(stdClass $data = null) {
      parent::__construct($data);
      if ($data) {
        $this->name = $data->name ?? '';
        $this->latestVersion = $data->latestVersion ?? '';
        $this->supportsResultUpload = $data->supportsResultUpload;
        $this->published = $data->published ?? false;
        $this->supportsLayouts = $data->supportsLayouts ?? false;
      }
    }

    public static function get(int $id): Game {
      return new Game(GameRepository::getById($id));
    }

    /**
     * @throws Exception
     */
    public static function getGameKey(int $id): string {
      return GameRepository::getKey($id);
    }

    /**
     * @return Game[]
     */
    public static function list(): array {
      $queryResults = GameRepository::listAll();

      return self::mapGames($queryResults);
    }

    /**
     * @return Game[]
     */
    public static function listPublished(): array {
      $queryResults = GameRepository::listPublished();

      return self::mapGames($queryResults);
    }

    /**
     * @return string[]
     * @throws Exception
     */
    public function getCarClasses(): array {
      $queryResult = CarRepository::listCarClassesForGame($this->getId());

      return $this->mapCarClasses($queryResult);
    }

    /**
     * @return Car[]
     * @throws Exception
     */
    public function getCars(): array {
      $queryResults = CarRepository::listForGame($this->getId());

      return $this->mapCars($queryResults);
    }

    public function getIsPublished(): bool {
      return $this->published;
    }

    public function getLatestVersion(): string {
      return $this->latestVersion;
    }

    public function getName(): string {
      return trim($this->name ?? '');
    }

    /**
     * @return int[]
     * @throws Exception
     */
    public function getPlatformIds(): array {
      return Platform::listIdsForGame($this->getId());
    }

    public function getSupportsLayouts(): bool {
      return $this->supportsLayouts;
    }

    public function getSupportsResultUpload(): bool {
      return $this->supportsResultUpload;
    }

    /**
     * @throws Exception
     */
    public function getTracks(): array {
      $queryResult = TrackRepository::listForGame($this->getId());

      return $this->mapTracks($queryResult);
    }

    public function save(): bool {
      return false;
    }

    /**
     * @return array{columnName: string, value: mixed}
     */
    public function toDto(): array {
      return [
        'id' => $this->getId(),
        'name' => $this->getName(),
        'latestVersion' => $this->getLatestVersion(),
        'supportsResultUpload' => $this->getSupportsResultUpload(),
        'published' => $this->getIsPublished(),
        'supportsLayouts' => $this->getSupportsLayouts(),
      ];
    }

    private static function mapGames(array $queryResults): array {
      $results = array();

      foreach ($queryResults as $item) {
        $results[] = new Game($item);
      }

      return $results;
    }

    private static function mapTracks(array $queryResults): array {
      $results = array();

      foreach ($queryResults as $item) {
        $results[] = new Track($item);
      }

      return $results;
    }

    private function mapCarClasses(array $queryResults): array {
      $results = array();

      foreach ($queryResults as $item) {
        $results[] = $item->carClass;
      }

      return $results;
    }

    private function mapCars(array $queryResults): array {
      $results = array();

      foreach ($queryResults as $item) {
        $results[] = new Car($item);
      }

      return $results;
    }
  }