<?php

  namespace SLTK\Pages\Championships\Tabs;

  use Exception;
  use SLTK\Core\AdminPageSlugs;
  use SLTK\Core\ListTableBase;
  use SLTK\Domain\Championship;

  class ChampionshipEventClassesTable extends ListTableBase {
    private Championship $championship;

    public function __construct(Championship $championship) {
      parent::__construct(esc_html__('Event Class', 'sim-league-toolkit'),
        esc_html__('Event Classes', 'sim-league-toolkit'),
        AdminPageSlugs::EVENT_CLASS, false, AdminPageSlugs::CREATE_EVENT_CLASS);
      $this->championship = $championship;
    }

    /**
     * @inheritDoc
     */
    protected function getColumns(): array {
      return [
        'name' => esc_html__('Name', 'sim-league-toolkit'),
        'game' => esc_html__('Game', 'sim-league-toolkit'),
        'carClass' => esc_html__('Car Class', 'sim-league-toolkit'),
        'driverCategory' => esc_html__('Driver Category', 'sim-league-toolkit'),
        'isSingleCarClass' => esc_html__('Is Single Car Class', 'sim-league-toolkit'),
        'singleCarName' => esc_html__('Single Car Name', 'sim-league-toolkit'),
        'isBuiltIn' => esc_html__('Built In', 'sim-league-toolkit'),
      ];
    }

    /**
     * @inheritDoc
     */
    protected function getHiddenColumns(): array {
      return [];
    }

    /**
     * @throws Exception
     */
    protected function getItems(): array {
      $eventClasses = $this->championship->listEventClasses();

      $results = [];

      foreach ($eventClasses as $eventClass) {
        $results[] = $eventClass->toTableItem();
      }

      return $results;
    }

    /**
     * @inheritDoc
     */
    protected function getSortableColumns(): array {
      return [
        'name' => ['name', true],
        'game' => ['game', true],
        'carClass' => ['carClass', true],
        'driverCategory' => ['driverCategory', true]
      ];
    }
  }