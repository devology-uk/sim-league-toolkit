<?php

  namespace SLTK\Database;

  use SLTK\Core\BuiltinScoringSetNames;

  class ScoringSetScoresTableBuilder implements TableBuilder {

    public function applyAdjustments(string $tablePrefix): void {}

    public function definitionSql(string $tablePrefix, string $charsetCollate): string {
      $tableName = $this->tableName($tablePrefix);
      $parentTableName = $tablePrefix . TableNames::SCORING_SETS;

      return "CREATE TABLE {$tableName} (
        id bigint NOT NULL AUTO_INCREMENT,
        scoringSetId bigint NOT NULL,
        position tinyint NOT NULL DEFAULT 0,
        points tinyint NOT NULL DEFAULT 0,
        PRIMARY KEY  (id),
        FOREIGN KEY (scoringSetId) REFERENCES {$parentTableName}(id)
      ) {$charsetCollate};";
    }

    public function initialData(string $tablePrefix): void {
      global $wpdb;
      $tableName = $this->tableName($tablePrefix);
      $parentTableName = $tablePrefix . TableNames::SCORING_SETS;

      $data = [
        BuiltinScoringSetNames::F1                 => [
          [
            'position' => 1,
            'points'   => 25,
          ],
          [
            'position' => 2,
            'points'   => 18,
          ],
          [
            'position' => 3,
            'points'   => 15,
          ],
          [
            'position' => 4,
            'points'   => 12,
          ],
          [
            'position' => 5,
            'points'   => 10,
          ],
          [
            'position' => 6,
            'points'   => 8,
          ],
          [
            'position' => 7,
            'points'   => 6,
          ],
          [
            'position' => 8,
            'points'   => 4,
          ],
          [
            'position' => 9,
            'points'   => 2,
          ],
          [
            'position' => 10,
            'points'   => 1,
          ]
        ],
        BuiltinScoringSetNames::F1_SPRINT          => [
          [
            'position' => 1,
            'points'   => 8,
          ],
          [
            'position' => 2,
            'points'   => 7,
          ],
          [
            'position' => 3,
            'points'   => 6,
          ],
          [
            'position' => 4,
            'points'   => 5,
          ],
          [
            'position' => 5,
            'points'   => 4,
          ],
          [
            'position' => 6,
            'points'   => 3,
          ],
          [
            'position' => 7,
            'points'   => 2,
          ],
          [
            'position' => 8,
            'points'   => 1,
          ]
        ],
        BuiltinScoringSetNames::WEC                => [
          [
            'position' => 1,
            'points'   => 25,
          ],
          [
            'position' => 2,
            'points'   => 18,
          ],
          [
            'position' => 3,
            'points'   => 15,
          ],
          [
            'position' => 4,
            'points'   => 12,
          ],
          [
            'position' => 5,
            'points'   => 10,
          ],
          [
            'position' => 6,
            'points'   => 8,
          ],
          [
            'position' => 7,
            'points'   => 6,
          ],
          [
            'position' => 8,
            'points'   => 4,
          ],
          [
            'position' => 9,
            'points'   => 2,
          ],
          [
            'position' => 10,
            'points'   => 1,
          ]
        ],
        BuiltinScoringSetNames::GT_WORLD_CHALLENGE => [
          [
            'position' => 1,
            'points'   => 16.5,
          ],
          [
            'position' => 2,
            'points'   => 12,
          ],
          [
            'position' => 3,
            'points'   => 9.5,
          ],
          [
            'position' => 4,
            'points'   => 7.5,
          ],
          [
            'position' => 5,
            'points'   => 6,
          ],
          [
            'position' => 6,
            'points'   => 4.5,
          ],
          [
            'position' => 7,
            'points'   => 3,
          ],
          [
            'position' => 8,
            'points'   => 2,
          ],
          [
            'position' => 9,
            'points'   => 1,
          ],
          [
            'position' => 10,
            'points'   => 0.5,
          ]
        ],
        BuiltinScoringSetNames::LE_MANS_24H        => [
          [
            'position' => 1,
            'points'   => 50,
          ],
          [
            'position' => 2,
            'points'   => 36,
          ],
          [
            'position' => 3,
            'points'   => 30,
          ],
          [
            'position' => 4,
            'points'   => 24,
          ],
          [
            'position' => 5,
            'points'   => 20,
          ],
          [
            'position' => 6,
            'points'   => 16,
          ],
          [
            'position' => 7,
            'points'   => 12,
          ],
          [
            'position' => 8,
            'points'   => 8,
          ],
          [
            'position' => 9,
            'points'   => 4,
          ],
          [
            'position' => 10,
            'points'   => 2,
          ]
        ]
      ];

      foreach($data as $key => $scores) {
        $parentId = (int)$wpdb->get_var("SELECT id FROM {$parentTableName} WHERE name = '{$key}'");
        foreach($scores as $score) {

          $exists = $wpdb->get_var("SELECT COUNT(*) FROM {$tableName} WHERE scoringSetId = {$parentId} AND position = {$score['position']};");

          if(!$exists) {
            $score['scoringSetId'] = $parentId;
            $wpdb->insert($tableName, $score);
          }
        }
      }
    }

    public function tableName(string $tablePrefix): string {
      return $tablePrefix . TableNames::SCORING_SET_SCORES;
    }
  }