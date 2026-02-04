<?php

  namespace SLTK\Database\Repositories;

  use Exception;
  use stdClass;

  abstract class DomainRepositoryBase extends RepositoryBase {

    private static string $tableName;

    /**
     * @throws Exception
     */
    public static function add(array $setting): int {
      return self::insert(self::getTableName(), $setting);
    }

    /**
     * @throws Exception
     */
    public static function delete(int $id): void {
      self::deleteById(self::getTableName(), $id);
    }

    /**
     * @throws Exception
     */
    public static function getById(int $id): ?stdClass {
      return self::getRowById(self::getTableName(), $id);
    }

    public static function init(string $tableName): void {
      if(!empty(self::$tableName)) {
        return;
      }

      self::$tableName = $tableName;
    }

    /**
     * @throws Exception
     */
    public static function update(int $id, array $updates): void {
      self::updateById(self::getTableName(), $id, $updates);
    }

    /**
     * @throws Exception
     */
    private static function getTableName(): string {
      if (!isset(self::$tableName)) {
        throw new Exception(esc_html__('Table name not set, did you forget to call init', 'sim-league-toolkit'));
      }

      return self::$tableName;
    }

  }