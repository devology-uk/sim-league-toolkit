<?php

  namespace SLTK\Domain;

  use Exception;
  use SLTK\Core\Constants;
  use SLTK\Database\Repositories\ServerRepository;
  use SLTK\Database\Repositories\ServerSettingRepository;
  use SLTK\Domain\Abstractions\AggregateRoot;
  use SLTK\Domain\Abstractions\Deletable;
  use SLTK\Domain\Abstractions\Listable;
  use SLTK\Domain\Abstractions\Saveable;
  use SLTK\Domain\Traits\HasIdentity;
  use stdClass;

  class Server implements AggregateRoot, Deletable, Listable, Saveable {
    use HasIdentity;

    private string $game = '';
    private int $gameId = Constants::DEFAULT_ID;
    private string $gameKey = '';
    private bool $isHostedServer = false;
    private string $name = '';
    private string $platform = '';
    private int $platformId = 1;

    /**
     * @throws Exception
     */
    public static function addSetting(ServerSetting $serverSetting): int {
      return ServerSettingRepository::add($serverSetting->toArray());
    }

    /**
     * @throws Exception
     */
    public static function delete(int $id): void {
      ServerSettingRepository::deleteByServerId($id);
      ServerRepository::delete($id);
    }

    /**
     * @throws Exception
     */
    public static function deleteSetting(int $id): void {
      ServerSettingRepository::delete($id);
    }

    public static function fromStdClass(?stdClass $data): ?self {

      if (!$data) {
        return null;
      }

      $result = new self();

      $result->setId((int) $data->id);
      $result->setGame($data->gameName ?? '');
      $result->setGameKey($data->gameKey);
      $result->setName($data->name ?? '');
      $result->setIsHostedServer($data->isHostedServer ?? false);
      $result->setGameId($data->gameId ?? Constants::DEFAULT_ID);
      $result->setPlatform($data->platformName ?? '');
      $result->setPlatformId($data->platformId ?? 1);

      return $result;
    }

    /**
     * @throws Exception
     */
    public static function get(int $id): Server|null {
      $queryResult = ServerRepository::getById($id);

      return self::fromStdClass($queryResult);
    }

    /**
     * @throws Exception
     */
    public static function getSetting(int $serverId, string $settingName): ServerSetting|null {
      $queryResult = ServerSettingRepository::getByName($serverId, $settingName);

      return ServerSetting::fromStdClass($queryResult);
    }

    /**
     * @throws Exception
     */
    public static function getSettingById(int $id): ServerSetting|null {
      $queryResult = ServerSettingRepository::getById($id);

      return ServerSetting::fromStdClass($queryResult);
    }

    /**
     * @return Server[]
     * @throws Exception
     */
    public static function list(): array {
      $queryResults = ServerRepository::list();

      return self::mapServers($queryResults);
    }

    /**
     * @return ServerSetting[]
     * @throws Exception
     */
    public static function listSettings(int $serverId): array {
      $queryResults = ServerSettingRepository::listByServerId($serverId);

      return self::mapServerSettings($queryResults);
    }

    /**
     * @throws Exception
     */
    public static function saveSetting(ServerSetting $entity): ServerSetting {
      if (!$entity->hasId()) {
        $entity->setId(ServerSettingRepository::add($entity->toArray()));
      } else {
        ServerRepository::update($entity->getId(), $entity->toArray());
      }

      return $entity;
    }

    public function getGame(): string {
      return $this->game ?? '';
    }

    public function setGame(string $game): void {
      $this->game = $game;
    }

    public function getGameId(): int {
      return $this->gameId ?? Constants::DEFAULT_ID;
    }

    public function setGameId(int $value): void {
      $this->gameId = $value;
    }

    public function getGameKey(): string {
      return $this->gameKey;
    }

    public function setGameKey(string $value): void {
      $this->gameKey = $value;
    }

    public function getIsHostedServer(): bool {
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

    public function setPlatform(string $platform): void {
      $this->platform = $platform;
    }

    public function getPlatformId(): int {
      return $this->platformId ?? Constants::DEFAULT_ID;
    }

    public function setPlatformId(int $value): void {
      $this->platformId = $value;
    }

    /**
     * @return ServerSetting[]
     * @throws Exception
     */
    public function getSettings(): array {
      $queryResult = ServerSettingRepository::listByServerId($this->getId());

      return self::mapServerSettings($queryResult);
    }

    /**
     * @throws Exception
     */
    public function save(): self {
      if (!$this->hasId()) {
        $this->setId(ServerRepository::add($this->toArray()));
      } else {
        ServerRepository::update($this->getId(), $this->toArray());
      }

      return $this;
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
      return [
        'id' => $this->getId(),
        'name' => $this->getName(),
        'isHostedServer' => $this->getIsHostedServer(),
        'game' => $this->getGame(),
        'gameId' => $this->getGameId(),
        'gameKey' => $this->getGameKey(),
        'platform' => $this->getPlatform(),
        'platformId' => $this->getPlatformId(),
      ];
    }

    private static function mapServerSettings(array $queryResults): array {
      $results = array();

      foreach ($queryResults as $item) {
        $results[] = ServerSetting::fromStdClass($item);
      }

      return $results;
    }

    private static function mapServers(array $queryResults): array {
      $results = array();

      foreach ($queryResults as $item) {
        $results[] = self::fromStdClass($item);
      }

      return $results;
    }
  }