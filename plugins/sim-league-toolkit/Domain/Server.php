<?php

  namespace SLTK\Domain;

  use Exception;
  use SLTK\Core\Constants;
  use SLTK\Database\Repositories\ServerRepository;
  use stdClass;

  class Server extends DomainBase {
    private string $game = '';
    private int $gameId = Constants::DEFAULT_ID;
    private string $gameKey = '';
    private bool $isHostedServer = false;
    private string $name = '';
    private string $platform = '';
    private int $platformId = 1;

    public function __construct(?stdClass $data = null) {
      parent::__construct($data);
      if ($data) {
        $this->game = $data->gameName ?? '';
        $this->gameKey = $data->gameKey;
        $this->name = $data->name ?? '';
        $this->isHostedServer = $data->isHostedServer ?? false;
        $this->gameId = $data->gameId ?? Constants::DEFAULT_ID;
        $this->platform = $data->platformName ?? '';
        $this->platformId = $data->platformId ?? 1;
      }
    }

    /**
     * @throws Exception
     */
    public static function addSetting(ServerSetting $serverSetting): int {
      return ServerRepository::addSetting($serverSetting->toArray(false));
    }

    /**
     * @throws Exception
     */
    public static function delete(int $id): void {
      ServerRepository::delete($id);
    }

    /**
     * @throws Exception
     */
    public static function deleteSetting(int $id): void {
      ServerRepository::deleteSetting($id);
    }

    /**
     * @throws Exception
     */
    public static function get(int $id): Server {
      $queryResult = ServerRepository::getById($id);

      return new Server($queryResult);
    }

    /**
     * @throws Exception
     */
    public static function getSetting(int $serverId, string $settingName): ServerSetting|null {
      $queryResult = ServerRepository::getSettingByName($serverId, $settingName);

      return new ServerSetting($queryResult);
    }

    /**
     * @throws Exception
     */
    public static function getSettingById(int $id): ServerSetting {
      $queryResult = ServerRepository::getSettingById($id);

      return new ServerSetting($queryResult);
    }

    /**
     * @throws Exception
     */
    public static function list(): array {
      $queryResults = ServerRepository::list();

      return self::mapServers($queryResults);
    }

    public function getGame(): string {
      return $this->game ?? '';
    }

    public function getGameId() {
      return $this->gameId ?? Constants::DEFAULT_ID;
    }

    public function setGameId(int $value): void {
      $this->gameId = $value;
    }

    public function getGameKey(): string {
      return $this->gameKey;
    }

    public function getIsHostedServer() {
      return $this->isHostedServer ?? false;
    }

    public function setIsHostedServer(bool $value): void {
      $this->isHostedServer = $value;
    }

    public function getName(): string {
      return $this->name ?? '';
    }

    public function setName(string $name): void {
      $this->name = $name;
    }

    public function getPlatform(): string {
      return $this->platform ?? '';
    }

    public function getPlatformId() {
      return $this->platformId ?? Constants::DEFAULT_ID;
    }

    public function setPlatformId(int $value): void {
      $this->platformId = $value;
    }

    /**
     * @return ServerSetting[]
     */
    public function getSettings(): array {
      $queryResult = ServerRepository::getSettings($this->getId());

      return self::mapServerSettings($queryResult);
    }

    /**
     * @throws Exception
     */
    public function save(): void {
      if ($this->getId() === Constants::DEFAULT_ID) {
        $this->setId(ServerRepository::add($this->toArray()));
      } else {
        ServerRepository::update($this->getId(), $this->toArray());
      }
    }

    /**
     * @throws Exception
     */
    public function saveSetting(ServerSetting $entity): ServerSetting {
      if (!$entity->hasId()) {
        $entity->setId(ServerRepository::addSetting($entity->toArray(false)));
      } else {
        ServerRepository::update($entity->getId(), $entity->toArray(false));
      }

      return $entity;
    }

    /**
     * @return array{fieldName: string, value: mixed}
     */
    public function toArray(): array {
      return [
        'name' => $this->getName(),
        'isHostedServer' => $this->getIsHostedServer(),
        'gameId' => $this->getGameId(),
        'platformId' => $this->getPlatformId(),
      ];
    }

    /**
     * @return array{fieldName: string, value: mixed}
     */
    public function toDto(): array {
      $result = [
        'name' => $this->getName(),
        'isHostedServer' => $this->getIsHostedServer(),
        'game' => $this->getGame(),
        'gameId' => $this->getGameId(),
        'gameKey' => $this->getGameKey(),
        'platform' => $this->getPlatform(),
        'platformId' => $this->getPlatformId(),
      ];

      if ($this->hasId()) {
        $result['id'] = $this->getId();
      }
      return $result;

    }


    private static function mapServerSettings(array $queryResults): array {
      $results = array();

      foreach ($queryResults as $item) {
        $results[] = new ServerSetting($item);
      }

      return $results;
    }

    private static function mapServers(array $queryResults): array {
      $results = array();

      foreach ($queryResults as $item) {
        $results[] = new Server($item);
      }

      return $results;
    }
  }