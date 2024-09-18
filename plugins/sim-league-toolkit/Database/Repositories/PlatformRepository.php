<?php

  namespace SLTK\Database\Repositories;

  use SLTK\Database\TableNames;
  use stdClass;

  class PlatformRepository extends RepositoryBase {

    public static function getName(int $id): string {
      $row = self::getRowById(TableNames::PLATFORMS, $id);

      return $row->name ?? '';
    }

    /**
     * @return stdClass[]
     */
    public static function listAll(): array {
      return self::getResultsFromTable(TableNames::PLATFORMS);
    }
  }