<?php

  namespace SLTK\Pages\Championships;

  use SLTK\Core\AdminPageSlugs;
  use SLTK\Core\ListTableBase;

  class ChampionshipsTable extends ListTableBase {

    public function __construct() {
      parent::__construct(esc_html__('Championship', 'sim-league-toolkit'),
        esc_html__('Championships', 'sim-league-toolkit'),
        AdminPageSlugs::CHAMPIONSHIP);
    }

    protected function getColumns(): array {
      return [
        'name' => esc_html__('Name', 'sim-league-toolkit'),
        'startDate' => esc_html__('Start Date', 'sim-league-toolkit'),
      ];
    }

    protected function getHiddenColumns(): array {
      return [];
    }

    protected function getItems(): array {
      $results = [];

      return $results;
    }

    protected function getSortableColumns(): array {
      return array(
        'name' => ['name', true],
        'startDate' => ['startDate', true],
      );
    }


  }