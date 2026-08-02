<?php

  namespace SLTK\Database\Repositories;

  use Exception;
  use stdClass;

  abstract class DomainRepositoryBase extends RepositoryBase {

    /**
     * @var array<string, string>
     */
    private static array $tableNamesByClass = [];

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
      if(!empty(self::$tableNamesByClass[static::class])) {
        return;
      }

      self::$tableNamesByClass[static::class] = $tableName;
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
      if (empty(self::$tableNamesByClass[static::class])) {
        throw new Exception(esc_html__('Table name not set, did you forget to call init', 'sim-league-toolkit'));
      }

      return self::$tableNamesByClass[static::class];
    }

  }