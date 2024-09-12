<?php

  namespace SLTK\Database\Repositories;

  use SLTK\Core\Constants;
  use stdClass;

  /**
   * Base class for data access repositories
   */
  abstract class RepositoryBase {

    /**
     * @param string $tableNameWithoutPrefix The name of the target table, without the WordPres prefix
     * @param int $id The id of the target item
     *
     * @return void
     */
    protected static function deleteById(string $tableNameWithoutPrefix, int $id): void {
      global $wpdb;

      $wpdb->delete(self::prefixedTableName($tableNameWithoutPrefix), array('id' => $id));

      if($wpdb->last_error) { ?>
        <div class='alert alert-danger'>
          <p><?= esc_html(__('Unexpected error deleting data from database.', 'sim-league-toolkit')) ?></p>
          <p><?= $wpdb->last_error ?></p>
        </div>
        <?php
      }
    }

    /**
     * Executes arbitrary SQL statement, not intended for use to query data
     *
     * @param string $sql A SQL query to execute
     *
     * @return int Number of rows affected by the query
     */
    protected static function execute(string $sql): int {
      global $wpdb;

      $result = $wpdb->query($sql);
      if($wpdb->last_error) { ?>
        <div class='alert alert-danger'>
          <p><?= esc_html(__('Unexpected error executing SQL.', 'sim-league-toolkit')) ?></p>
          <p><?= $wpdb->last_error ?></p>
        </div>
        <?php

        return 0;
      }

      return $result;

    }

    /**
     * @param string $tableNameWithoutPrefix The name of the target table, without the WordPres prefix
     * @param string|null $filter Optional WHERE express to filter rows
     *
     * @return int Count of rows that match the filter
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

      if($wpdb->last_error) { ?>
        <div class='alert alert-danger'>
          <p><?= esc_html(__('Unexpected error getting count from database.', 'sim-league-toolkit')) ?></p>
          <p><?= $wpdb->last_error ?></p>
        </div>
        <?php

        return 0;
      }

      return $queryResult;
    }

    /**
     * @param string $query The SQL query to execute
     *
     * @return array Array of matching rows or an empty array
     */
    protected static function getResults(string $query): array {
      global $wpdb;
      $queryResults = $wpdb->get_results($query);

      if($wpdb->last_error) { ?>
        <div class='alert alert-danger'>
          <p><?= esc_html(__('Unexpected error fetching results from database.', 'sim-league-toolkit')) ?></p>
          <p><?= $wpdb->last_error ?></p>
        </div>
        <?php

        return array();
      }

      return $queryResults;
    }

    /**
     * @param string $tableNameWithoutPrefix The name of the target table, without the WordPres prefix
     * @param string|null $filter Optional WHERE clause to filter rows from the target table
     *
     * @return array Array of all columns from matching rows, otherwise an empty array
     */
    protected static function getResultsFromTable(string $tableNameWithoutPrefix, string $filter = null): array {
      $query = self::selectAllQuery($tableNameWithoutPrefix, $filter);

      return self::getResults($query);
    }

    /**
     * @param string $query A SQL statement that returns a single row
     *
     * @return stdClass|null The row as a stdClass if exists, otherwise null
     */
    protected static function getRow(string $query): stdClass|null {
      global $wpdb;
      $queryResult = $wpdb->get_row($query);

      if($wpdb->last_error) { ?>
        <div class='alert alert-danger'>
          <p><?= esc_html(__('Unexpected error fetching row from database.', 'sim-league-toolkit')) ?></p>
          <p><?= $wpdb->last_error ?></p>
        </div>
        <?php

        return null;
      }

      return $queryResult;
    }

    /**
     * @param string $tableNameWithoutPrefix The name of the target table, without the WordPres prefix
     * @param int $id The id of the row to get
     *
     * @return stdClass|null The row as a stdClass if exists otherwise null
     */
    protected static function getRowById(string $tableNameWithoutPrefix, int $id): stdClass|null {
      $tableName = self::prefixedTableName($tableNameWithoutPrefix);

      $query = "SELECT * FROM {$tableName} WHERE id = '{$id}';";

      return self::getRow($query);
    }

    /**
     * @param string $tableNameWithoutPrefix The name of the target table, without the WordPres prefix
     * @param string $name The value of a name column to match
     *
     * @return stdClass|null The row as stdClass if exists otherwise null
     */
    protected static function getRowByName(string $tableNameWithoutPrefix, string $name): stdClass|null {
      $tableName = self::prefixedTableName($tableNameWithoutPrefix);

      $query = "SELECT * FROM {$tableName} WHERE name = '{$name}';";

      return self::getRow($query);
    }

    /**
     * @param string $tableNameWithoutPrefix The name of the target table, without the WordPres prefix
     * @param string $filter A WHERE clause that returns a single row from the target table
     *
     * @return stdClass|null The target row as a stdClass or null if no match found
     */
    protected static function getRowFromTable(string $tableNameWithoutPrefix, string $filter): stdClass|null {
      $query = self::selectAllQuery($tableNameWithoutPrefix, $filter);

      return self::getRow($query);
    }

    /**
     * @param string $query The query to return a single column value from the database
     *
     * @return int|string|null
     */
    protected static function getValue(string $query): int|string|null {
      global $wpdb;

      $queryResult = $wpdb->get_var($query);

      if($wpdb->last_error) { ?>
        <div class='alert alert-danger'>
          <p><?= esc_html(__('Unexpected error getting value from database.', 'sim-league-toolkit')) ?></p>
          <p><?= $wpdb->last_error ?></p>
        </div>
        <?php

        return 0;
      }

      return $queryResult;
    }

    /**
     * @param string $tableNameWithoutPrefix The name of the target table, without the WordPres prefix
     * @param array $data The data to be inserted
     *
     * @return int The id of the row assigned by the database
     */
    protected static function insert(string $tableNameWithoutPrefix, array $data): int {
      global $wpdb;
      $tableNameWithPrefix = self::prefixedTableName($tableNameWithoutPrefix);

      $wpdb->insert($tableNameWithPrefix, $data);

      if($wpdb->last_error) { ?>
        <div class='alert alert-danger'>
          <p><?= esc_html(__('Unexpected error inserting data into database.', 'sim-league-toolkit')) ?></p>
          <p><?= $wpdb->last_error ?></p>
        </div>
        <?php

        return Constants::DEFAULT_ID;
      }

      return $wpdb->insert_id;
    }

    /**
     * @param string $tableName The name of the target table, without the WordPres prefix
     *
     * @return string The table name with the prefix configured for the current WordPress installation
     */
    protected static function prefixedTableName(string $tableName): string {
      global $wpdb;

      return $wpdb->prefix . $tableName;
    }

    /**
     * Outputs any error generated by the last database operation
     * @return void
     */
    protected static function printWpdbError(): void {
      global $wpdb;
      if($wpdb->last_error) { ?>
        <div class='alert alert-danger'>
          <p><?= esc_html(__('Unexpected error performing requested database operation.', 'sim-league-toolkit')) ?></p>
          <p><?= $wpdb->last_error ?></p>
        </div>
        <?php
      }
    }

    /**
     * @param string $tableNameWithoutPrefix The name of the target table, without the WordPres prefix
     * @param string|null $filter Optional WHERE clause to filter data
     *
     * @return string An SQL statement to select all data from the target table
     */
    protected static function selectAllQuery(string $tableNameWithoutPrefix, string $filter = null): string {
      $tableName = self::prefixedTableName($tableNameWithoutPrefix);
      $query = "SELECT * FROM {$tableName}";

      if(isset($filter)) {
        $query .= " WHERE {$filter};";
      }

      return $query . ';';
    }

    /**
     * @param string $tableNameWithoutPrefix The name of the target table, without the WordPres prefix
     * @param string $filter The WHERE clause that defines the existence check
     *
     * @return bool True if a match is found, otherwise false
     */
    protected static function simpleExists(string $tableNameWithoutPrefix, string $filter): bool {
      global $wpdb;

      $tableName = self::prefixedTableName($tableNameWithoutPrefix);

      $query = "SELECT COUNT(*) FROM {$tableName} WHERE {$filter};";
      $count = (int)$wpdb->get_var($query);

      if($wpdb->last_error) { ?>
        <div class='alert alert-danger'>
          <p><?= esc_html(__('Unexpected error inserting data into database.', 'sim-league-toolkit')) ?></p>
          <p><?= $wpdb->last_error ?></p>
        </div>
        <?php

        return false;
      }

      return $count > 0;
    }

    /**
     * @param string $tableNameWithoutPrefix The name of the target table, without the WordPres prefix
     * @param int $id The id of the object to update
     * @param array $updates Array of field/value pairs to update
     *
     * @return void
     */
    protected static function updateById(string $tableNameWithoutPrefix, int $id, array $updates): void {
      global $wpdb;

      $tableNameWithPrefix = self::prefixedTableName($tableNameWithoutPrefix);

      $wpdb->update($tableNameWithPrefix, $updates, compact('id'));
      if($wpdb->last_error) { ?>
        <div class='alert alert-danger'>
          <p><?= esc_html(__('Unexpected error updating table by id.', 'sim-league-toolkit')) ?></p>
          <p><?= $wpdb->last_error ?></p>
        </div>
        <?php
      }
    }
  }