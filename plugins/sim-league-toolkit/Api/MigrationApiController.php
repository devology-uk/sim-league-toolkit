<?php

  namespace SLTK\Api;

  use SLTK\Core\AccLeagueToolsThemeDetector;
  use SLTK\Core\Constants;
  use SLTK\Database\Repositories\MigrationRecordsRepository;
  use SLTK\Migration\MigrationImporter;
  use SLTK\Migration\MigrationRegistry;
  use WP_REST_Response;

  class MigrationApiController extends ApiController {

    public function __construct() {
      parent::__construct(ResourceNames::MIGRATION);
    }

    public function registerRoutes(): void {
      $this->registerRoute('/' . ResourceNames::MIGRATION . '/status', 'GET', [$this, 'canManage'], [$this, 'status']);
      $this->registerRoute('/' . ResourceNames::MIGRATION . '/run-all', 'POST', [$this, 'canManage'], [$this, 'runAll']);
    }

    public function canManage(): bool {
      return current_user_can(Constants::MANAGE_OPTIONS_PERMISSION);
    }

    public function runAll(): WP_REST_Response {
      return $this->execute(function () {
        return ApiResponse::success(array_map(
          fn(MigrationImporter $importer) => array_merge(
            ['entityKey' => $importer->getEntityKey(), 'label' => $importer->getLabel()],
            $importer->run()->toDto()
          ),
          MigrationRegistry::all()
        ));
      });
    }

    public function status(): WP_REST_Response {
      return $this->execute(function () {
        return ApiResponse::success([
          'legacyThemeDetected' => AccLeagueToolsThemeDetector::isInstalled(),
          'legacyThemeVersion' => AccLeagueToolsThemeDetector::themeVersion(),
          'importers' => array_map(fn(MigrationImporter $importer) => [
            'entityKey' => $importer->getEntityKey(),
            'label' => $importer->getLabel(),
            'migratedCount' => MigrationRecordsRepository::countMigrated($importer->getEntityKey()),
          ], MigrationRegistry::all()),
        ]);
      });
    }
  }
