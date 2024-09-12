<?php

  namespace SLTK\Database\Repositories;

  use SLTK\Database\TableNames;

  /**
   * Provides database access for platforms
   */
  class PlatformRepository extends RepositoryBase {
    
    /**
     * @param int $id The id of the target platform
     *
     * @return string The name of the target platform
     */
    public static function getName(int $id): string {
      $row = self::getRowById(TableNames::PLATFORMS, $id);

      return $row->name ?? '';
    }

    /**
     * Gets all platforms defined in the database
     *
     * @return array
     */
    public static function listAll(): array {
      return self::getResultsFromTable(TableNames::PLATFORMS);
    }
  }