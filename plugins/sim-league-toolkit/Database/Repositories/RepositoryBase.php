<?php

  namespace SLTK\Database\Repositories;

  use Exception;
  use stdClass;

  abstract class RepositoryBase {

    /**
     * @throws Exception
     */
    protected static function deleteById(string $tableNameWithoutPrefix, int $id): void {
      global $wpdb;

      $wpdb->delete(self::prefixedTableName($tableNameWithoutPrefix), ['id' => $id]);

      self::throwIfError(esc_html__('Unexpected error deleting data from database.', 'sim-league-toolkit'));
    }

    /**
     * @return int Number of rows affected by the query
     *
     * @throws Exception
     */
    protected static function execute(string $sql): int {
      global $wpdb;

      $result = $wpdb->query($sql);
      self::throwIfError(esc_html__('Unexpected error executing SQL.', 'sim-league-toolkit'));

      return $result;

    }

    /**
     * @param string|null $filter Optional WHERE expression to filter rows
     *
     * @return int Count of rows that match the filter
     *
     * @throws Exception
     */
    protected static function getCount(string $tableNameWithoutPrefix, string $filter = null): int {
      global $wpdb;
      $tableName = self::prefixedTableName($tableNameWithoutPrefix);

      $query = "SELECT COUNT(*) FROM {$tableName}";
      if(isset($filter)) {
        $query .= " WHERE {$filter}";
      }

      $query .= ';';

      $queryResult = $wpdb->get_var($query);

      self::throwIfError(esc_html__('Unexpected error getting count from database.', 'sim-league-toolkit'));

      return $queryResult;
    }

    /**
     * @return stdClass[]
     *
     * @throws Exception
     */
    protected static function getResults(string $query): array {
      global $wpdb;
      $queryResults = $wpdb->get_results($query);

      self::throwIfError(esc_html__('Unexpected error fetching results from database.', 'sim-league-toolkit'));

      return $queryResults;
    }

    /**
     * @return stdClass[]
     */
    protected static function getResultsFromTable(string $tableNameWithoutPrefix, string $filter = null, string $sortBy = null): array {
      $query = self::selectAllQuery($tableNameWithoutPrefix, $filter, $sortBy);

      return self::getResults($query);
    }

    /**
     * @throws Exception
     */
    protected static function getRow(string $query): stdClass|null {
      global $wpdb;
      $queryResult = $wpdb->get_row($query);

      self::throwIfError(esc_html__('Unexpected error fetching row from database.', 'sim-league-toolkit'));

      return $queryResult;
    }

    protected static function getRowById(string $tableNameWithoutPrefix, int $id): stdClass|null {
      $tableName = self::prefixedTableName($tableNameWithoutPrefix);

      $query = "SELECT * FROM {$tableName} WHERE id = '{$id}';";

      return self::getRow($query);
    }

    protected static function getRowByName(string $tableNameWithoutPrefix, string $name): stdClass|null {
      $tableName = self::prefixedTableName($tableNameWithoutPrefix);

      $query = "SELECT * FROM {$tableName} WHERE name = '{$name}';";

      return self::getRow($query);
    }

    protected static function getRowFromTable(string $tableNameWithoutPrefix, string $filter): stdClass|null {
      $query = self::selectAllQuery($tableNameWithoutPrefix, $filter);

      return self::getRow($query);
    }

    /**
     * @throws Exception
     */
    protected static function getValue(string $query): ?string {
      global $wpdb;

      $queryResult = $wpdb->get_var($query);

      self::throwIfError(esc_html__('Unexpected error getting value from database.', 'sim-league-toolkit'));

      return $queryResult;
    }

    /**
     * @return int The id of the row assigned by the database
     *
     * @throws Exception
     */
    protected static function insert(string $tableNameWithoutPrefix, array $data): int {
      global $wpdb;
      $tableNameWithPrefix = self::prefixedTableName($tableNameWithoutPrefix);

      $wpdb->insert($tableNameWithPrefix, $data);

      self::throwIfError(esc_html__('Unexpected error inserting data into database.', 'sim-league-toolkit'));

      return $wpdb->insert_id;
    }

    protected static function prefixedTableName(string $tableName): string {
      global $wpdb;

      return $wpdb->prefix . $tableName;
    }

    protected static function printWpdbError(string $message): void {
      global $wpdb;
      if($wpdb->last_error) { ?>
        <div class='alert alert-danger'>
          <p><?= $message; ?></p>
          <p><?= $wpdb->last_error ?></p>
        </div>
        <?php
      }
    }

    protected static function selectAllQuery(string $tableNameWithoutPrefix, string $filter = null, string $sortBy = null, string $sortOrder = null): string {
      $tableName = self::prefixedTableName($tableNameWithoutPrefix);
      $query = "SELECT * FROM {$tableName}";

      if(isset($filter)) {
        $query .= " WHERE {$filter}";
      }

      if(isset($sortBy)) {
        $query .= " ORDER BY {$sortBy} ";
      }

      if(isset($sortOrder)) {
          $query .= $sortOrder;
      }
      return $query . ';';
    }

    /**
     * @return bool True if the query finds match otherwise false
     *
     * @throws Exception
     */
    protected static function simpleExists(string $tableNameWithoutPrefix, string $filter): bool {
      global $wpdb;

      $tableName = self::prefixedTableName($tableNameWithoutPrefix);

      $query = "SELECT COUNT(*) FROM {$tableName} WHERE {$filter};";
      $count = (int)$wpdb->get_var($query);

      self::throwIfError(esc_html__('Unexpected error inserting data into database.', 'sim-league-toolkit'));

      return $count > 0;
    }

    /**
     * @throws Exception
     */
    protected static function throwIfError(string $message): void {
      global $wpdb;
      if($wpdb->last_error) {
        self::printWpdbError($message);
        throw new Exception($message . ': ' . $wpdb->last_error);
      }
    }

    protected static function updateById(string $tableNameWithoutPrefix, int $id, array $updates): void {
      global $wpdb;

      $tableNameWithPrefix = self::prefixedTableName($tableNameWithoutPrefix);

      $wpdb->update($tableNameWithPrefix, $updates, compact('id'));
      if($wpdb->last_error) { ?>
        <div class='alert alert-danger'>
          <p><?= esc_html__('Unexpected error updating table by id.', 'sim-league-toolkit'); ?></p>
          <p><?= $wpdb->last_error ?></p>
        </div>
        <?php
      }
    }
  }