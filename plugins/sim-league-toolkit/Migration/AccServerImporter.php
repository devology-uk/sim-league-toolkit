<?php

  namespace SLTK\Migration;

  use Exception;
  use SLTK\Core\Enums\GameKey;
  use SLTK\Database\Repositories\MigrationRecordsRepository;
  use SLTK\Domain\Server;
  use stdClass;

  class AccServerImporter implements MigrationImporter {
    private const string ENTITY_KEY = 'server-acc';

    public function getEntityKey(): string {
      return self::ENTITY_KEY;
    }

    public function getLabel(): string {
      return __('ACC servers', 'sim-league-toolkit');
    }

    public function run(): MigrationRunResult {
      $result = new MigrationRunResult();
      $support = new ServerImportSupport();
      $gameId = $support->getGameId(GameKey::AssettoCorsaCompetizione);

      foreach (AccltLegacyDatabase::getServers() as $legacyServer) {
        $this->migrateServer($legacyServer, $gameId, $support, $result);
      }

      return $result;
    }

    private function boolToSettingValue(mixed $value): string {
      return $value ? '1' : '0';
    }

    private function buildSettings(stdClass $legacyServer): array {
      return [
        'tcpPort' => $legacyServer->tcpPort,
        'udpPort' => $legacyServer->udpPort,
        'lanDiscovery' => $this->boolToSettingValue($legacyServer->lanDiscovery),
        'maxConnections' => $legacyServer->maxConnections,
        'publicIP' => $legacyServer->publicIp,
        'password' => $legacyServer->password,
        'adminPassword' => $legacyServer->adminPassword,
        'spectatorPassword' => $legacyServer->spectatorPassword,
        'centralEntryListPath' => $legacyServer->centralEntryListPath,
        'ignorePrematureDisconnects' => $this->boolToSettingValue($legacyServer->ignorePrematureDisconnects),
        'randomizeTrackWhenEmpty' => $this->boolToSettingValue($legacyServer->randomiseTrackWhenEmpty),
        'registerToLobby' => $this->boolToSettingValue($legacyServer->registerToLobby),
        'ftpHost' => $legacyServer->ftpHost,
        'ftpPort' => $legacyServer->ftpPort,
        'ftpUserName' => $legacyServer->ftpUserName,
        'ftpPassword' => $legacyServer->ftpPassword,
        'ftpResultsDirectory' => $legacyServer->ftpResultsDirectory,
      ];
    }

    private function migrateServer(stdClass $legacyServer, int $gameId, ServerImportSupport $support, MigrationRunResult $result): void {
      $legacyId = (int)$legacyServer->id;

      try {
        if (MigrationRecordsRepository::isMigrated(self::ENTITY_KEY, $legacyId)) {
          $result->recordSkipped();
          return;
        }

        $server = new Server();
        $server->setName($legacyServer->name);
        $server->setIsHostedServer((bool)$legacyServer->isHostedServer);
        $server->setGameId($gameId);
        $server->setPlatformId($support->getPlatformId((int)$legacyServer->platformId));
        $server->save();

        MigrationRecordsRepository::recordMigration(self::ENTITY_KEY, $legacyId, $server->getId());

        $support->saveSettings($server->getId(), $this->buildSettings($legacyServer));

        $result->recordMigrated();
      } catch (Exception $e) {
        $result->recordFailed(sprintf(__('ACC server %1$d (%2$s): %3$s', 'sim-league-toolkit'), $legacyId, $legacyServer->name ?? '', $e->getMessage()));
      }
    }
  }
