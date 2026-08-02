<?php

  namespace SLTK\Database\Repositories;

  use Exception;
  use SLTK\Database\TableNames;
  use stdClass;

  class ChampionshipResultPenaltiesRepository extends RepositoryBase {
    /**
     * @throws Exception
     */
    public static function add(array $data): int {
      return self::insert(TableNames::CHAMPIONSHIP_RESULT_PENALTIES, $data);
    }

    /**
     * @throws Exception
     */
    public static function delete(int $id): void {
      self::deleteById(TableNames::CHAMPIONSHIP_RESULT_PENALTIES, $id);
    }

    /**
     * @throws Exception
     */
    public static function deleteByResultId(int $championshipSessionResultId): void {
      self::deleteFromTable(TableNames::CHAMPIONSHIP_RESULT_PENALTIES, "championshipSessionResultId = {$championshipSessionResultId}");
    }

    /**
     * @throws Exception
     */
    public static function getById(int $id): ?stdClass {
      return self::getRowById(TableNames::CHAMPIONSHIP_RESULT_PENALTIES, $id);
    }

    /**
     * @return stdClass[]
     * @throws Exception
     */
    public static function listByResultId(int $championshipSessionResultId): array {
      return self::getResultsFromTable(
        TableNames::CHAMPIONSHIP_RESULT_PENALTIES,
        "championshipSessionResultId = {$championshipSessionResultId}"
      );
    }

    /**
     * @throws Exception
     */
    public static function update(int $id, array $data): void {
      self::updateById(TableNames::CHAMPIONSHIP_RESULT_PENALTIES, $id, $data);
    }
  }
