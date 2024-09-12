<?php

  namespace SLTK\Domain;

  use SLTK\Core\Constants;
  use SLTK\Database\Repositories\GamesRepository;
  use SLTK\Database\Repositories\PlatformRepository;
  use SLTK\Database\Repositories\ServerRepository;
  use stdClass;

  class Server extends DomainBase {

    public int $gameId = Constants::DEFAULT_ID;
    public bool $isHostedServer = false;
    public string $name = '';
    public int $platformId = 1;
    private array $settings = [];

    public function __construct(stdClass $data = null) {
      if($data) {
        $this->id = $data->id;
        $this->name = $data->name ?? '';
        $this->isHostedServer = $data->isHostedServer ?? false;
        $this->gameId = $data->gameId ?? Constants::DEFAULT_ID;
        $this->platformId = $data->platformId ?? 1;
      }

      if($this->id !== Constants::DEFAULT_ID) {
        $this->settings = ServerRepository::listSettings($this->id);
      }
    }

    /**
     * @inheritDoc
     */
    public static function get(int $id): Server {
      return ServerRepository::getById($id);
    }

    /**
     * @inheritDoc
     */
    public static function list(): array {
      return ServerRepository::list();
    }

    /**
     * @inheritDoc
     */
    public function save(): bool {
      if($this->id === Constants::DEFAULT_ID) {
        $this->id = ServerRepository::add($this);
      }

      return $this->id !== Constants::DEFAULT_ID;
    }

    /**
     * @param string $settingName The name of the setting to add or update
     * @param string $settingValue The value of the setting
     *
     * @return void
     */
    public function applySetting(string $settingName, string $settingValue): void {
      $this->settings[$settingName] = $settingValue;
    }

    /**
     * @param string $settingName The name of the setting to get
     *
     * @return string|null The current value of the setting or null
     */
    public function getSetting(string $settingName): ?string {
      return $this->settings[$settingName] ?? null;
    }

    /**
     * @return array Collection of settings for this server
     */
    public function getSettings(): array {
      return $this->settings;
    }

    /**
     * @return array Associative array of properties
     */
    public function toArray(): array {
      return [
        'name'           => $this->name,
        'isHostedServer' => $this->isHostedServer,
        'gameId'         => $this->gameId,
        'platformId'     => $this->platformId,
      ];
    }

    /**
     * @return array Associative array of properties for transfer to client
     */
    public function toDto(): array {
      return $this->toTableItem();
    }

    /**
     * @return array Associative array of property for display in admin table
     */
    public function toTableItem(): array {
      return [
        'id'             => $this->id,
        'name'           => $this->name,
        'game'           => GamesRepository::getName($this->gameId),
        'isHostedServer' => $this->isHostedServer ? 'Yes' : 'No',
        'platform'       => PlatformRepository::getName($this->platformId)
      ];
    }
  }