<?php

  namespace SLTK\Pages\Championships;

  use Exception;
  use SLTK\Core\AdminPageSlugs;
  use SLTK\Core\ListTableBase;
  use SLTK\Domain\Championship;

  class ChampionshipsTable extends ListTableBase {

    public function __construct() {
      parent::__construct(esc_html__('Championship', 'sim-league-toolkit'),
        esc_html__('Championships', 'sim-league-toolkit'),
        AdminPageSlugs::CHAMPIONSHIP,
        false,
        AdminPageSlugs::CREATE_CHAMPIONSHIP);
    }

    protected function getColumns(): array {
      return [
        'name' => esc_html__('Name', 'sim-league-toolkit'),
        'game' => esc_html__('Game', 'sim-league-toolkit'),
        'platform' => esc_html__('Platform', 'sim-league-toolkit'),
        'startDate' => esc_html__('Start Date', 'sim-league-toolkit'),
        'isActive' => esc_html__('Active', 'sim-league-toolkit'),
      ];
    }

    protected function getHiddenColumns(): array {
      return [];
    }

    /**
     * @throws Exception
     */
    protected function getItems(): array {
      $championships = Championship::list();

      $results = [];

      foreach ($championships as $championship) {
        $results[] = $championship->toTableItem();
      }

      return $results;
    }

    protected function getSortableColumns(): array {
      return array(
        'name' => ['name', true],
        'startDate' => ['startDate', true],
      );
    }


  }