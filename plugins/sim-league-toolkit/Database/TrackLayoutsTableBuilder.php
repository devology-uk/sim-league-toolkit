<?php

  namespace SLTK\Database;

  use SLTK\Core\Constants;

  class TrackLayoutsTableBuilder implements TableBuilder {

    public function addConstraints(string $tablePrefix): void {
      global $wpdb;
      $tableName = $this->tableName($tablePrefix);
      $gamesTableName = $tablePrefix . TableNames::GAMES;
      $tracksTableName = $tablePrefix . TableNames::TRACKS;

      $gameConstraintName = 'fk_game_track_layouts_gameId';
      $gameExistsCheckSql = "SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS
                        WHERE TABLE_NAME = '{$tableName}'
                        AND CONSTRAINT_NAME = '{$gameConstraintName}';";

      $gameExists = (int)$wpdb->get_var($gameExistsCheckSql);
      if (!$gameExists) {
        $fkSql = "ALTER TABLE {$tableName}
                    ADD CONSTRAINT {$gameConstraintName} 
                        FOREIGN KEY (gameId) REFERENCES {$gamesTableName}(id);";
        $wpdb->query($fkSql);
      }

      $tracksConstraintName = 'fk_game_track_layouts_trackId';
      $tracksExistsCheckSql = "SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS
                        WHERE TABLE_NAME = '{$tableName}'
                        AND CONSTRAINT_NAME = '{$tracksConstraintName}';";

      $trackExists = (int)$wpdb->get_var($tracksExistsCheckSql);
      if (!$trackExists) {
        $fkSql = "ALTER TABLE {$tableName}
                    ADD CONSTRAINT {$tracksConstraintName} 
                        FOREIGN KEY (trackId) REFERENCES {$tracksTableName}(id);";
        $wpdb->query($fkSql);
      }
    }

    public function applyAdjustments(string $tablePrefix): void {

    }

    public function definitionSql(string $tablePrefix, string $charsetCollate): string {
      $tableName = $this->tableName($tablePrefix);

      return "CREATE TABLE {$tableName} (
          gameId bigint NOT NULL,
          trackId bigint NOT NULL,
          name tinytext NOT NULL,
          corners tinyint NOT NULL,
          length int NOT NULL,
          PRIMARY KEY  (gameId, trackId)
        ) {$charsetCollate};";
    }

    public function initialData(string $tablePrefix): void {
      global $wpdb;
      $tableName = $this->tableName($tablePrefix);
      $gamesTableName = $tablePrefix . TableNames::GAMES;
      $tracksTableName = $tablePrefix . TableNames::TRACKS;

      $dataFilePath = Constants::PLUGIN_ROOT_DIR . 'data/game-track-layouts.csv';

      $handle = fopen($dataFilePath, 'r');
      if ($handle !== false) {

        while (($data = fgetcsv($handle, 1000, ',')) != false) {

          $gameKey = $data[0];
          $trackKey = $data[1];

          $gameId = $wpdb->get_var("SELECT id FROM {$gamesTableName} WHERE gameKey = '{$gameKey}'");
          $trackId = $wpdb->get_var("SELECT id FROM {$tracksTableName} WHERE trackKey = '{$trackKey}'");

          $existingId = $wpdb->get_var("SELECT gameId FROM {$tableName} WHERE gameId = {$gameId} and trackId = {$trackId};");

          if ($existingId == null) {
            $gameTrackLayout = array(
              'gameId' => $gameId,
              'trackId' => $trackId,
              'name' => $data[2],
              'corners' => $data[3],
              'length' => $data[4]
            );

            $wpdb->insert($tableName, $gameTrackLayout);
          }
        }

        fclose($handle);
      }
    }

    public function tableName(string $tablePrefix): string {
      return $tablePrefix . TableNames::TRACK_LAYOUTS;
    }
  }