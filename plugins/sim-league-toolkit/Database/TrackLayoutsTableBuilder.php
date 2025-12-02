<?php

  namespace SLTK\Database;

  use SLTK\Core\Constants;

  class TrackLayoutsTableBuilder implements TableBuilder {

    public function addConstraints(string $tablePrefix): void {
      global $wpdb;
      $tableName = $this->tableName($tablePrefix);
      $gamesTableName = $tablePrefix . TableNames::GAMES;
      $tracksTableName = $tablePrefix . TableNames::TRACKS;

      $gameConstraintName = 'fk_track_layouts_gameId';
      $gameConstraintExistsCheckSql = "SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS
                        WHERE TABLE_NAME = '{$tableName}'
                        AND CONSTRAINT_NAME = '{$gameConstraintName}';";

      $gameConstraintExists = (int)$wpdb->get_var($gameConstraintExistsCheckSql);
      if (!$gameConstraintExists) {
        $fkSql = "ALTER TABLE {$tableName}
                    ADD CONSTRAINT {$gameConstraintName} 
                        FOREIGN KEY (gameId) REFERENCES {$gamesTableName}(id);";
        $wpdb->query($fkSql);
      }

      $tracksConstraintName = 'fk_track_layouts_trackId';
      $trackConstraintExistsCheckSql = "SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS
                        WHERE TABLE_NAME = '{$tableName}'
                        AND CONSTRAINT_NAME = '{$tracksConstraintName}';";

      $trackConstraintExists = (int)$wpdb->get_var($trackConstraintExistsCheckSql);
      if (!$trackConstraintExists) {
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
          layoutId tinyText NOT NULL,
          name tinytext NOT NULL,
          corners tinyint NOT NULL,
          length int NOT NULL,
          PRIMARY KEY  (gameId, trackId)
        ) {$charsetCollate};";
    }

    public function initialData(string $tablePrefix): void {
      $this->loadLayouts($tablePrefix, 'ams2-track-layouts.csv', 'AMS2');
      $this->loadLayouts($tablePrefix, 'lmu-track-layouts.csv', 'LMU');
    }

    public function loadLayouts(string $tablePrefix, string $fileName, string $gameKey): void {
      global $wpdb;
      $tableName = $this->tableName($tablePrefix);
      $gamesTableName = $tablePrefix . TableNames::GAMES;
      $tracksTableName = $tablePrefix . TableNames::TRACKS;

      $dataFilePath = Constants::PLUGIN_ROOT_DIR . 'data/' . $fileName;

      $handle = fopen($dataFilePath, 'r');
      if ($handle !== false) {

        while (($data = fgetcsv($handle, 1000, ',')) != false) {

          $trackKey = $data[0];
          $layoutId = $data[1];

          $gameId = $wpdb->get_var("SELECT id FROM {$gamesTableName} WHERE gameKey = '{$gameKey}'");
          $trackId = $wpdb->get_var("SELECT id FROM {$tracksTableName} WHERE trackId = '{$trackKey}'");

          $existingId = $wpdb->get_var("SELECT id FROM {$tableName} WHERE gameId = {$gameId} AND trackId = {$trackId} AND layoutId = '{$layoutId}';");

          if ($existingId == null) {
            $gameTrackLayout = array(
              'gameId' => $gameId,
              'trackId' => $trackId,
              'layoutId' => $layoutId,
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