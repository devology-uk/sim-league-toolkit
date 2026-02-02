<?php

  namespace SLTK\Domain;

  use Exception;
  use SLTK\Database\Repositories\CarRepository;
  use SLTK\Database\Repositories\GameRepository;
  use SLTK\Database\Repositories\PlatformRepository;
  use SLTK\Database\Repositories\TrackRepository;
  use SLTK\Domain\Abstractions\AggregateRoot;
  use SLTK\Domain\Abstractions\Listable;
  use SLTK\Domain\Traits\HasIdentity;
  use stdClass;

  class Game implements AggregateRoot, Listable {
    use HasIdentity;

    private string $gameKey = '';
    private string $latestVersion = '';
    private string $name = '';
    private bool $published = false;
    private bool $supportsLayouts = false;
    private bool $supportsResultUpload = false;

    public static function fromStdClass(?stdClass $data): ?self {
      if (!$data) {
        return null;
      }

      $result = new self();

      $result->setGameKey($data->gameKey ?? '');
      $result->setName($data->name ?? '');
      $result->setLatestVersion($data->latestVersion ?? '');
      $result->setSupportsResultUpload($data->supportsResultUpload ?? false);
      $result->setPublished($data->published ?? false);
      $result->setSupportsLayouts($data->supportsLayouts ?? false);

      return $result;
    }

    public static function get(int $id): Game|null {
      return self::fromStdClass(GameRepository::getById($id));
    }

    /**
     * @return string[]
     * @throws Exception
     */
    public static function getCarClasses(int $gameId): array {
      return CarRepository::listCarClassesForGame($gameId);
    }

    /**
     * @return Car[]
     * @throws Exception
     */
    public static function getCars(int $gameId): array {
      $queryResults = CarRepository::listForGame($gameId);

      return array_map(function ($item) {
        return Car::fromStdClass($item);
      }, $queryResults);
    }

    /**
     * @throws Exception
     */
    public static function getGameKey(int $id): string {
      return GameRepository::getKey($id);
    }

    /**
     * @throws Exception
     */
    public static function getPlatformById(int $id): Platform {
      $queryResult = PlatformRepository::get($id);

      return Platform::fromStdClass($queryResult);
    }

    /**
     * @return Platform[]
     * @throws Exception
     */
    public static function getPlatforms(int $gameId): array {
      $queryResults = PlatformRepository::listForGame($gameId);

      return array_map(function ($item) {
        return Platform::fromStdClass($item);
      }, $queryResults);
    }

    /**
     * @throws Exception
     */
    public static function getTracks(int $gameId): array {
      $queryResult = TrackRepository::listForGame($gameId);

      return array_map(function ($item) {
        return new Track($item);
      }, $queryResult);
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
      return PlatformRepository::listIdsForGame($this->getId());
    }

    public function getSupportsLayouts(): bool {
      return $this->supportsLayouts;
    }

    public function getSupportsResultUpload(): bool {
      return $this->supportsResultUpload;
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
      return array_map(function ($item) {
        return Game::fromStdClass($item);
      }, $queryResults);
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

    private function setGameKey(string $value): void {
      $this->gameKey = $value;
    }

    private function setLatestVersion(string $value): void {
      $this->latestVersion = $value;
    }

    private function setName(string $value): void {
      $this->name = trim($value);
    }

    private function setPublished(bool $value): void {
      $this->published = $value;
    }

    private function setSupportsLayouts(bool $value): void {
      $this->supportsLayouts = $value;
    }

    private function setSupportsResultUpload(false $value): void {
      $this->supportsResultUpload = $value;
    }
  }