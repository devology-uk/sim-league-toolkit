<?php

  namespace SLTK\Pages\Rules;

  use SLTK\Core\AdminPageSlugs;
  use SLTK\Core\ListTableBase;
  use SLTK\Domain\RuleSet;

  class RuleSetsTable extends ListTableBase {

    public function __construct() {
      parent::__construct(esc_html__('Rule Set', 'sim-league-toolkit'),
        esc_html__('Rule Sets', 'sim-league-toolkit'),
        AdminPageSlugs::RULE_SET);
    }

    protected function getColumns(): array {
      return [
        'name' => esc_html__('Name', 'sim-league-toolkit'),
        'type' => esc_html__('Type', 'sim-league-toolkit'),
      ];
    }

    protected function getHiddenColumns(): array {
      return [];
    }

    protected function getItems(): array {
      $ruleSets = RuleSet::list();

      $results = [];

      foreach ($ruleSets as $ruleSet) {
        $results[] = $ruleSet->toTableItem();
      }

      return $results;
    }

    protected function getSortableColumns(): array {
      return array(
        'name' => ['name', true]
      );
    }
  }