<?php

  namespace SLTK\Database;

  class DatabaseBuilder {
    private static array $builders = [];

    /**
     * Initialises the database builder
     *
     * @return void
     */
    public static function init(): void {
      self::$builders[] = new GamesTableBuilder();
      self::$builders[] = new PlatformsTableBuilder();
      self::$builders[] = new RaceNumbersTableBuilder();
      self::$builders[] = new ServersTableBuilder();
      self::$builders[] = new ServerSettingsTableBuilder();
    }

    /**
     * Creates or alters custom tables required by Sim League Toolkit during activation of the plugin
     *
     * @return void
     */
    public static function initialiseOrUpdate(): void {
      global $wpdb;
      $tablePrefix = $wpdb->prefix;
      $charsetCollate = $wpdb->get_charset_collate();

      require_once ABSPATH . 'wp-admin/includes/upgrade.php';

      $tableScripts = array();
      foreach(self::$builders as $builder) {
        $tableScripts[] = $builder->definitionSql($tablePrefix, $charsetCollate);
      }

      dbDelta($tableScripts);

      foreach(self::$builders as $builder) {
        $builder->initialData($tablePrefix);
        $builder->applyAdjustments($tablePrefix);
      }
    }

    /**
     * Removes database tables created by the plugin when the plugin is deleted
     *
     * @return void
     */
    public static function uninstall(): void {
      global $wpdb;
      $tablePrefix = $wpdb->prefix;
      $tableNames = [];

      foreach(self::$builders as $builder) {
        $tableNames[] = $builder->tableName($tablePrefix);
      }

      $tableNameString = implode(',', $tableNames);
      $dropScript = "SET foreign_key_checks = 0;
                    DROP TABLE IF EXISTS {$tableNameString};
                    SET foreign_key_checks = 1;";

      $wpdb->query($dropScript);
    }
  }