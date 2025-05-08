<?php

  namespace SLTK\Domain;

  use Exception;
  use SLTK\Core\Constants;
  use SLTK\Database\Repositories\PlatformRepository;
  use stdClass;

  class Platform extends DomainBase {

    private string $name = '';
    private string $playerIdPrefix = '';
    private string $shortName = '';

    public function __construct(stdClass $data = null) {
      if ($data) {
        $this->id = $data->id ?? Constants::DEFAULT_ID;
        $this->name = $data->name;
        $this->shortName = $data->shortName;
        $this->playerIdPrefix = $data->playerIdPrefix;
      }
    }

    public static function get(int $id): Platform|null {

    }

    /***
     * @return Platform[]
     */
    public static function list(): array {
      $queryResults = PlatformRepository::listAll();

      return self::mapPlatforms($queryResults);
    }

    /***
     * @return Platform[]
     * @throws Exception
     */
    public static function listForGame(int $gameId): array {
      $queryResults = PlatformRepository::listForGame($gameId);

      return self::mapPlatforms($queryResults);
    }

    /***
     * @return int[]
     * @throws Exception
     */
    public static function listIdsForGame(int $gameId): array {
      return PlatformRepository::listIdsForGame($gameId);
    }

    public function getName(): string {
      return $this->name;
    }

    public function getPlayerIdPrefix(): string {
      return $this->playerIdPrefix;
    }

    public function getShortName(): string {
      return $this->shortName;
    }

    public function save(): bool {

    }

    private static function mapPlatforms(array $queryResults): array {
      $results = array();

      foreach ($queryResults as $item) {
        $results[] = new Platform($item);
      }

      return $results;
    }
  }
