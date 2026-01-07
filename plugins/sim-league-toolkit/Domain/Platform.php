<?php

  namespace SLTK\Domain;

  use Exception;
  use SLTK\Database\Repositories\PlatformRepository;
  use stdClass;

  class Platform extends DomainBase {

    private string $name = '';
    private string $playerIdPrefix = '';
    private string $shortName = '';

    public function __construct(stdClass $data = null) {
      parent::__construct($data);

      if ($data) {
        $this->name = $data->name;
        $this->shortName = $data->shortName;
        $this->playerIdPrefix = $data->playerIdPrefix;
      }
    }

    /**
     * @throws Exception
     */
    public static function get(int $id): Platform|null {
      $queryResult = PlatformRepository::get($id);

      return new Platform($queryResult);
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
      return false;
    }

    public function toDto(): array {
      return [
        'id' => $this->getId(),
        'name' => $this->getName(),
        'shortName' => $this->getShortName(),
        'playerIdPrefix' => $this->getPlayerIdPrefix(),
      ];
    }

    private static function mapPlatforms(array $queryResults): array {
      $results = array();

      foreach ($queryResults as $item) {
        $results[] = new Platform($item);
      }

      return $results;
    }
  }
