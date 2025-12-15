<?php

  namespace SLTK\Domain;

  use Exception;
  use SLTK\Database\Repositories\CarRepository;
  use SLTK\Database\Repositories\DriverCategoryRepository;
  use SLTK\Database\Repositories\GameRepository;
  use SLTK\Database\Repositories\TrackRepository;
  use stdClass;

  class Game extends DomainBase {
    public final const string CARS_TAB = 'cars';
    public final const string CAR_CLASSES_TAB = 'car_classes';
    public final const string DRIVER_CATEGORIES_TAB = 'driver_categories';
    public final const string IS_BUILTIN_FIELD_NAME = 'sltk_is_builtin';
    public final const string IS_PUBLISHED_FIELD_NAME = 'sltk_is_published';
    public final const string NAME_FIELD_NAME = 'sltk_name';
    public final const string PLATFORMS_FIELD_NAME = 'sltk_platforms[]';
    public final const string SUPPORTS_RESULT_UPLOAD_FIELD_NAME = 'sltk_supportS_result_upload';
    public final const string TRACKS_TAB = 'tracks';

    private bool $builtIn = false;
    private string $latestVersion = '';
    private string $name = '';
    private bool $published = false;
    private bool $supportsLayouts = false;
    private bool $supportsResultUpload = false;

    public function __construct(stdClass $data = null) {
      if ($data) {
        $this->name = $data->name ?? '';
        $this->latestVersion = $data->latestVersion ?? '';
        $this->supportsResultUpload = $data->supportsResultUpload;
        $this->published = $data->published ?? false;
        $this->builtIn = $data->builtIn ?? false;
        $this->supportsLayouts = $data->supportsLayouts ?? false;

        if (isset($data->id)) {
          $this->id = $data->id;
        }
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
     * @return CarClass[]
     * @throws Exception
     */
    public function getCarClasses(): array {
      $queryResult = CarRepository::listCarClassesForGame($this->id);

      return $this->mapCarClasses($queryResult);
    }

    /**
     * @return Car[]
     * @throws Exception
     */
    public function getCars(): array {
      $queryResults = CarRepository::listForGame($this->id);

      return $this->mapCars($queryResults);
    }

    /**
     * @return DriverCategory[]
     */
    public function getDriverCategories(): array {
      $queryResult = DriverCategoryRepository::listForGame($this->id);

      return $this->mapDriverCategories($queryResult);
    }

    public function getIsBuiltin(): bool {
      return $this->builtIn;
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
      return Platform::listIdsForGame($this->id);
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
      $queryResult = TrackRepository::listForGame($this->id);

      return $this->mapTracks($queryResult);
    }

    public function save(): bool {
      return false;
    }

    /**
     * @return array{columnName: string, value: mixed}
     */
    public function toTableItem(): array {
      return [
        'id' => $this->id,
        'name' => $this->name,
        'latestVersion' => $this->latestVersion,
        'supportsResultUpload' => $this->supportsResultUpload ? 'Yes' : 'No',
        'published' => $this->published ? 'Yes' : 'No',
        'builtIn' => $this->builtIn ? 'Yes' : 'No',
        'supportsLayouts' => $this->supportsLayouts ? 'Yes' : 'No',
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
        $results[] = new CarClass($item);
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

    private function mapDriverCategories(array $queryResults): array {
      $results = array();

      foreach ($queryResults as $item) {
        $results[] = new DriverCategory($item);
      }

      return $results;
    }
  }