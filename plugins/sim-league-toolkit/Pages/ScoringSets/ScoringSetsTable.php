<?php

  namespace SLTK\Pages\ScoringSets;

  use SLTK\Core\AdminPageSlugs;
  use SLTK\Core\ListTableBase;
  use SLTK\Domain\ScoringSet;

  class ScoringSetsTable extends ListTableBase {

    public function __construct() {
      parent::__construct(esc_html__('Scoring Set', 'sim-league-toolkit'),
                          esc_html__('Scoring Sets', 'sim-league-toolkit'),
                          AdminPageSlugs::SCORING_SET);
    }

    protected function getColumns(): array {
      return [
        'name'                => esc_html__('Name', 'sim-league-toolkit'),
        'pointsForFastestLap' => esc_html__('Points for Fastest Lap', 'sim-league-toolkit'),
        'pointsForFinishing'  => esc_html__('Points for Finishing', 'sim-league-toolkit'),
        'pointsForPole'       => esc_html__('Points for Pole', 'sim-league-toolkit'),
      ];
    }

    protected function getHiddenColumns(): array {
      return [];
    }

    protected function getItems(): array {
      $scoringSets = ScoringSet::list();

      $results = [];

      foreach($scoringSets as $scoringSet) {
        $results[] = $scoringSet->toTableItem();
      }

      return $results;
    }

    protected function getSortableColumns(): array {
      return array(
        'name' => ['name', true]
      );
    }
  }