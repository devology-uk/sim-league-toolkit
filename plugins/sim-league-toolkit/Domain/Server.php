<?php

  namespace SLTK\Domain;

  use Exception;
  use SLTK\Core\Constants;
  use SLTK\Database\Repositories\ServerRepository;
  use stdClass;

  class Server extends DomainBase {

    public int $gameId = Constants::DEFAULT_ID;
    public bool $isHostedServer = false;
    public string $name = '';
    public int $platformId = 1;
    private array $settings = [];

    public function __construct(?stdClass $data = null) {
      parent::__construct($data);
      if($data) {
        $this->name = $data->name ?? '';
        $this->isHostedServer = $data->isHostedServer ?? false;
        $this->gameId = $data->gameId ?? Constants::DEFAULT_ID;
        $this->platformId = $data->platformId ?? 1;
      }

      if($this->getId() !== Constants::DEFAULT_ID) {
        $this->settings = ServerRepository::listSettings($this->getId());
      }
    }

    public static function get(int $id): Server {
      return ServerRepository::getById($id);
    }

    public static function list(): array {
      return ServerRepository::list();
    }

    /**
     * @throws Exception
     */
    public function save(): bool {
      if($this->getId() === Constants::DEFAULT_ID) {
        $this->setId(ServerRepository::add($this));
      }

      return $this->getId() !== Constants::DEFAULT_ID;
    }

    public function applySetting(string $settingName, string $settingValue): void {
      $existing = $this->getSetting($settingName);
      if($existing) {
        $existing->settingValue = $settingValue;
        return;
      }

      $newSetting = new ServerSetting();
      $newSetting->serverId = $this->getId();
      $newSetting->settingName = $settingName;
      $newSetting->settingValue = $settingValue;

      $this->settings[] = $newSetting;
    }

    public function getSettingValue(string $settingName): ?string {
      $setting = $this->getSetting($settingName);

      return $setting?->settingValue;
    }


    public function getSetting(string $settingName): ServerSetting|null {
      foreach($this->settings as $setting) {
        if($setting->settingName === $settingName) {
          return $setting;
        }
      }
      return null;
    }

    /**
     * @return ServerSetting[]
     */
    public function getSettings(): array {
      return $this->settings;
    }

    public function saveSettings(): void {
      $serverSettings = $this->getSettings();
      foreach ($serverSettings as $setting) {
        $setting->save();
      }
    }

    /**
     * @return array{fieldName: string, value: mixed}
     */
    public function toArray(): array {
      return [
        'name'           => $this->name,
        'isHostedServer' => $this->isHostedServer,
        'gameId'         => $this->gameId,
        'platformId'     => $this->platformId,
      ];
    }
  }