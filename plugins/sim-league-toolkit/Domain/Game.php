<?php

  namespace SLTK\Domain;

  use SLTK\Database\Repositories\GamesRepository;
  use stdClass;

  class Game extends DomainBase {

    public string $latestVersion = '';
    public string $name = '';
    public bool $supportsResultUpload = false;

    public function __construct(stdClass $data = null) {
      if($data) {
        $this->name = $data->name ?? '';
        $this->latestVersion = $data->latestVersion ?? '';
        $this->supportsResultUpload = $data->supportsResultUpload;

        if(isset($data->id)) {
          $this->id = $data->id;
        }
      }
    }

    public static function get(int $id): Game {
      return new Game(GamesRepository::getGame($id));
    }

    /**
     * @return Game[]
     */
    public static function list(): array {
      $queryResults = GamesRepository::listAll();

      return self::mapGames($queryResults);
    }

    public function save(): bool {
      return false;
    }

    /**
     * @return string The well known key for the target game
     */
    public static function getGameKey(int $id): string {
      return GamesRepository::getGameKey($id);
    }

    private static function mapGames(array $queryResults): array {
      $results = array();

      foreach($queryResults as $item) {
        $results[] = new Game($item);
      }

      return $results;
    }
  }