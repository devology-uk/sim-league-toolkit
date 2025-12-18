<?php

  namespace SLTK\Database;


  if (!defined('ABSPATH')) {
    die;
  }

  class DriverCategoriesTableBuilder extends TableBuilder {
    public function addConstraints(string $tablePrefix): void {
    }

    public function applyAdjustments(string $tablePrefix): void {
    }

    public function definitionSql(string $tablePrefix, string $charsetCollate): string {
      $tableName = $this->tableName($tablePrefix);

      return "CREATE TABLE " . $tableName . " (
        id bigint NOT NULL AUTO_INCREMENT,
          name tinytext NOT NULL,
          plaque tinytext NOT NULL,
          participationRequirement tinyint NOT NULL DEFAULT 0,
        PRIMARY KEY  (id)
      ) " . $charsetCollate . ";";
    }

    public function initialData(string $tablePrefix): void {
      global $wpdb;
      $tableName = $this->tableName($tablePrefix);

      $data = [
        [
          'name' => 'Bronze',
          'plaque' => 'AM',
          'participationRequirement' => 0,
        ],
        [
          'name' => 'Silver',
          'plaque' => 'PRO/AM',
          'participationRequirement' => 10,
        ],
        [
          'name' => 'Gold',
          'plaque' => 'PRO',
          'participationRequirement' => 25,
        ],
        [
          'name' => 'Platinum',
          'plaque' => 'PRO',
          'participationRequirement' => 50,
        ]
      ];

      foreach ($data as $item) {
        $exists = $wpdb->get_var("SELECT COUNT(*) FROM {$tableName} WHERE name = '{$item['name']}';");

        if (!$exists) {
          $newItem = [
            'name' => $item['name'],
            'plaque' => $item['plaque'],
            'participationRequirement' => $item['participationRequirement'],
          ];
          $wpdb->insert($tableName, $newItem);
        }
      }
    }

    public function tableName(string $tablePrefix): string {
      return $tablePrefix . TableNames::DRIVER_CATEGORIES;
    }
  }