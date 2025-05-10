<?php

  namespace SLTK\Domain;

  use Exception;
  use SLTK\Database\Repositories\CarClassRepository;
  use SLTK\Database\Repositories\GameRepository;
  use stdClass;

  class Game extends DomainBase {
    public final const string CAR_CLASSES_TAB = 'classes';
    public final const string IS_BUILTIN_FIELD_NAME = 'sltk_is_builtin';
    public final const string IS_PUBLISHED_FIELD_NAME = 'sltk_is_published';
    public final const string LATEST_VERSION_FIELD_NAME = 'sltk_lastest_version';
    public final const string NAME_FIELD_NAME = 'sltk_name';
    public final const string PLATFORMS_FIELD_NAME = 'sltk_platforms[]';
    public final const string SUPPORTS_RESULT_UPLOAD_FIELD_NAME = 'sltk_supportS_result_upload';

    private bool $builtIn = false;
    private string $latestVersion = '';
    private string $name = '';
    private bool $published = false;
    private bool $supportsResultUpload = false;

    public function __construct(stdClass $data = null) {
      if ($data) {
        $this->name = $data->name ?? '';
        $this->latestVersion = $data->latestVersion ?? '';
        $this->supportsResultUpload = $data->supportsResultUpload;
        $this->published = $data->published ?? false;
        $this->builtIn = $data->builtIn ?? false;

        if (isset($data->id)) {
          $this->id = $data->id;
        }
      }
    }

    public static function get(int $id): Game {
      return new Game(GameRepository::getById($id));
    }

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

    /***
     * @return CarClass[]
     */
    public function getCarClasses(): array {
      $queryResult = CarClassRepository::listForGame($this->id);

      return $this->mapCarClasses($queryResult);
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

    /***
     * @return int[]
     * @throws Exception
     */
    public function getPlatformIds(): array {
      return Platform::listIdsForGame($this->id);
    }

    public function getSupportsResultUpload(): bool {
      return $this->supportsResultUpload;
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
      ];
    }

    private static function mapCarClasses(array $queryResults): array {
      $results = array();

      foreach ($queryResults as $item) {
        $results[] = new CarClass($item);
      }

      return $results;
    }

    private static function mapGames(array $queryResults): array {
      $results = array();

      foreach ($queryResults as $item) {
        $results[] = new Game($item);
      }

      return $results;
    }
  }