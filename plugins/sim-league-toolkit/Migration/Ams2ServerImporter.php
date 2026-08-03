<?php

  namespace SLTK\Migration;

  use Exception;
  use SLTK\Core\Enums\GameKey;
  use SLTK\Database\Repositories\MigrationRecordsRepository;
  use SLTK\Domain\Server;
  use stdClass;

  class Ams2ServerImporter implements MigrationImporter {
    private const string ENTITY_KEY = 'server-ams2';

    public function getEntityKey(): string {
      return self::ENTITY_KEY;
    }

    public function getLabel(): string {
      return __('AMS2 servers', 'sim-league-toolkit');
    }

    public function run(): MigrationRunResult {
      $result = new MigrationRunResult();
      $support = new ServerImportSupport();
      $gameId = $support->getGameId(GameKey::AutoMobilista2);
      $platformId = $support->getPlatformId(null);

      foreach (AccltLegacyDatabase::getAms2Servers() as $legacyServer) {
        $this->migrateServer($legacyServer, $gameId, $platformId, $support, $result);
      }

      return $result;
    }

    private function boolToSettingValue(mixed $value): string {
      return $value ? '1' : '0';
    }

    private function buildSettings(stdClass $legacyServer): array {
      return [
        'password' => $legacyServer->password,
        'maxPlayerCount' => $legacyServer->maxPlayerCount,
        'bindIP' => $legacyServer->bindIP,
        'hostPort' => $legacyServer->hostPort,
        'queryPort' => $legacyServer->queryPort,
        'steamPort' => $legacyServer->steamPort,
        'controlGameSetup' => $this->boolToSettingValue($legacyServer->controlGameSetup),
        'sportsPlay' => $this->boolToSettingValue($legacyServer->sportsPlay),
        'allowEmptyJoin' => $this->boolToSettingValue($legacyServer->allowEmptyJoin),
        'enableHttpApi' => $this->boolToSettingValue($legacyServer->enableHttpApi),
        'httpApiPort' => $legacyServer->httpApiPort,
        'httpApiInterface' => $legacyServer->httpApiInterface,
      ];
    }

    private function migrateServer(stdClass $legacyServer, int $gameId, int $platformId, ServerImportSupport $support, MigrationRunResult $result): void {
      $legacyId = (int)$legacyServer->id;

      try {
        if (MigrationRecordsRepository::isMigrated(self::ENTITY_KEY, $legacyId)) {
          $result->recordSkipped();
          return;
        }

        $server = new Server();
        $server->setName($legacyServer->name);
        $server->setIsHostedServer(true);
        $server->setGameId($gameId);
        $server->setPlatformId($platformId);
        $server->save();

        MigrationRecordsRepository::recordMigration(self::ENTITY_KEY, $legacyId, $server->getId());

        $support->saveSettings($server->getId(), $this->buildSettings($legacyServer));

        $result->recordMigrated();
      } catch (Exception $e) {
        $result->recordFailed(sprintf(__('AMS2 server %1$d (%2$s): %3$s', 'sim-league-toolkit'), $legacyId, $legacyServer->name ?? '', $e->getMessage()));
      }
    }
  }
