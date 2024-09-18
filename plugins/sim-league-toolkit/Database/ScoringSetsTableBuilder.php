<?php

  namespace SLTK\Database;

  use SLTK\Core\BuiltinScoringSetNames;

  class ScoringSetsTableBuilder implements TableBuilder {

    public function applyAdjustments(string $tablePrefix): void {}

    public function definitionSql(string $tablePrefix, string $charsetCollate): string {
      $tableName = $this->tableName($tablePrefix);

      return "CREATE TABLE {$tableName} (
        id bigint NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        description text NOT NULL,
        pointsForFastestLap tinyint NOT NULL DEFAULT 0,
        pointsForPole tinyint NOT NULL DEFAULT 0,
        pointsForFinishing tinyint NOT NULL DEFAULT 0,
        PRIMARY KEY  (id)
      ) {$charsetCollate};";
    }

    public function initialData(string $tablePrefix): void {
      global $wpdb;
      $tableName = $this->tableName($tablePrefix);

      $data = [
        [
          'name'                => BuiltinScoringSetNames::F1,
          'description'         => esc_html__('The scoring system used by Formula 1', 'sim-league-toolkit'),
          'pointsForFastestLap' => 1,
          'pointsForPole'       => 0,
          'pointsForFinishing'  => 0,
        ],
        [
          'name'                => BuiltinScoringSetNames::F1_SPRINT,
          'description'         => esc_html__('The scoring system used by Formula 1 for Sprint races',
                                              'sim-league-toolkit'),
          'pointsForFastestLap' => 0,
          'pointsForPole'       => 0,
          'pointsForFinishing'  => 0,
        ],
        [
          'name'                => BuiltinScoringSetNames::WEC,
          'description'         => esc_html__('The scoring system used for World Endurance Championship races',
                                              'sim-league-toolkit'),
          'pointsForFastestLap' => 0,
          'pointsForPole'       => 1,
          'pointsForFinishing'  => 0,
        ],
        [
          'name'                => BuiltinScoringSetNames::GT_WORLD_CHALLENGE,
          'description'         => esc_html__('The scoring system used for GT World Challenge races',
                                              'sim-league-toolkit'),
          'pointsForFastestLap' => 0,
          'pointsForPole'       => 1,
          'pointsForFinishing'  => 0,
        ],
        [
          'name'                => BuiltinScoringSetNames::LE_MANS_24H,
          'description'         => esc_html__('The scoring system used for the Le Mans 24h race', 'sim-league-toolkit'),
          'pointsForFastestLap' => 0,
          'pointsForPole'       => 1,
          'pointsForFinishing'  => 1,
        ]
      ];

      foreach($data as $item) {
        $exists = $wpdb->get_var("SELECT COUNT(*) FROM {$tableName} WHERE name = '{$item['name']}';");

        if(!$exists) {
          $wpdb->insert($tableName, $item);
        }
      }
    }

    public function tableName(string $tablePrefix): string {
      return $tablePrefix . TableNames::SCORING_SETS;
    }
  }