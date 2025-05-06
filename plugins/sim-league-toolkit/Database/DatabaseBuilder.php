<?php

  namespace SLTK\Database;

  class DatabaseBuilder {

    /**
     * var TableBuilder[]
     */
    private static array $builders = [];

    public static function init(): void {
      self::$builders[] = new CountriesTableBuilder();
      self::$builders[] = new GamesTableBuilder();
      self::$builders[] = new PlatformsTableBuilder();
      self::$builders[] = new GamePlatformsTableBuilder();
      self::$builders[] = new RuleSetsTableBuilder();
      self::$builders[] = new RuleSetRulesTableBuilder();
      self::$builders[] = new ServersTableBuilder();
      self::$builders[] = new ServerSettingsTableBuilder();
      self::$builders[] = new ScoringSetsTableBuilder();
      self::$builders[] = new ScoringSetScoresTableBuilder();
      self::$builders[] = new TracksTableBuilder();
      self::$builders[] = new TrackLayoutsTableBuilder();


//      self::$builders[] = new ChampionshipsTableBuilder();
    }

    public static function initialiseOrUpdate(): void {
      global $wpdb;
      $tablePrefix = $wpdb->prefix;
      $charsetCollate = $wpdb->get_charset_collate();

      require_once ABSPATH . 'wp-admin/includes/upgrade.php';

      $tableScripts = [];
      foreach(self::$builders as $builder) {
        $tableScripts[] = $builder->definitionSql($tablePrefix, $charsetCollate);
      }

      dbDelta($tableScripts);

      foreach(self::$builders as $builder) {
        $builder->addConstraints($tablePrefix);
        $builder->initialData($tablePrefix);
        $builder->applyAdjustments($tablePrefix);
      }
    }

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