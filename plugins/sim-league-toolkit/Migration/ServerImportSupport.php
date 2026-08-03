<?php

  namespace SLTK\Migration;

  use Exception;
  use SLTK\Core\Enums\GameKey;
  use SLTK\Database\Repositories\PlatformRepository;
  use SLTK\Domain\Game;
  use SLTK\Domain\Server;
  use SLTK\Domain\ServerSetting;

  class ServerImportSupport {
    private const string DEFAULT_PLATFORM_SHORT_NAME = 'PC';

    private int $defaultPlatformId;
    private array $platformIdsByLegacyId;

    public function __construct() {
      $sltkPlatformIdsByShortName = $this->buildSltkPlatformIdsByShortName();

      $this->platformIdsByLegacyId = $this->buildLegacyPlatformIdMap($sltkPlatformIdsByShortName);
      $this->defaultPlatformId = $sltkPlatformIdsByShortName[self::DEFAULT_PLATFORM_SHORT_NAME] ?? 1;
    }

    /**
     * @throws Exception
     */
    public function getGameId(GameKey $gameKey): int {
      foreach (Game::list() as $game) {
        if ($game->getGameKey() === $gameKey->value) {
          return $game->getId();
        }
      }

      throw new Exception(sprintf('Game with key %s not found.', $gameKey->value));
    }

    public function getPlatformId(?int $legacyPlatformId): int {
      if ($legacyPlatformId === null) {
        return $this->defaultPlatformId;
      }

      return $this->platformIdsByLegacyId[$legacyPlatformId] ?? $this->defaultPlatformId;
    }

    /**
     * @throws Exception
     */
    public function saveSettings(int $serverId, array $settingValuesByName): void {
      foreach ($settingValuesByName as $settingName => $settingValue) {
        $setting = new ServerSetting();
        $setting->setServerId($serverId);
        $setting->setSettingName($settingName);
        $setting->setSettingValue((string)($settingValue ?? ''));

        Server::addSetting($setting);
      }
    }

    /**
     * @throws Exception
     */
    private function buildLegacyPlatformIdMap(array $sltkPlatformIdsByShortName): array {
      $lookup = [];

      foreach (AccltLegacyDatabase::getPlatforms() as $legacyPlatform) {
        if (isset($sltkPlatformIdsByShortName[$legacyPlatform->shortName])) {
          $lookup[(int)$legacyPlatform->id] = $sltkPlatformIdsByShortName[$legacyPlatform->shortName];
        }
      }

      return $lookup;
    }

    /**
     * @throws Exception
     */
    private function buildSltkPlatformIdsByShortName(): array {
      $lookup = [];

      foreach (PlatformRepository::listAll() as $platform) {
        $lookup[$platform->shortName] = (int)$platform->id;
      }

      return $lookup;
    }
  }
