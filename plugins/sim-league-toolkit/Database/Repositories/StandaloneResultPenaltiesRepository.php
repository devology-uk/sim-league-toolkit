<?php

  namespace SLTK\Database\Repositories;

  use Exception;
  use SLTK\Database\TableNames;
  use stdClass;

  class StandaloneResultPenaltiesRepository extends RepositoryBase {
    /**
     * @throws Exception
     */
    public static function add(array $data): int {
      return self::insert(TableNames::STANDALONE_RESULT_PENALTIES, $data);
    }

    /**
     * @throws Exception
     */
    public static function delete(int $id): void {
      self::deleteById(TableNames::STANDALONE_RESULT_PENALTIES, $id);
    }

    /**
     * @throws Exception
     */
    public static function deleteByResultId(int $standaloneSessionResultId): void {
      self::deleteFromTable(TableNames::STANDALONE_RESULT_PENALTIES, "standaloneSessionResultId = {$standaloneSessionResultId}");
    }

    /**
     * @throws Exception
     */
    public static function getById(int $id): ?stdClass {
      return self::getRowById(TableNames::STANDALONE_RESULT_PENALTIES, $id);
    }

    /**
     * @return stdClass[]
     * @throws Exception
     */
    public static function listByResultId(int $standaloneSessionResultId): array {
      return self::getResultsFromTable(
        TableNames::STANDALONE_RESULT_PENALTIES,
        "standaloneSessionResultId = {$standaloneSessionResultId}"
      );
    }

    /**
     * @throws Exception
     */
    public static function update(int $id, array $data): void {
      self::updateById(TableNames::STANDALONE_RESULT_PENALTIES, $id, $data);
    }
  }
