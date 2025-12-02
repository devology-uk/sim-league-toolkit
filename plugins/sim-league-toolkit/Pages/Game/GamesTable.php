<?php

  namespace SLTK\Pages\Game;

  use SLTK\Core\AdminPageSlugs;
  use SLTK\Core\ListTableBase;
  use SLTK\Domain\Game;

  class GamesTable extends ListTableBase {

    public function __construct() {

      parent::__construct(esc_html__('Game', 'sim-league-toolkit'),
        esc_html__('Games', 'sim-league-toolkit'),
        AdminPageSlugs::GAME,
        true);
    }

    protected function getColumns(): array {
      return [
        'name' => esc_html__('Name', 'sim-league-toolkit'),
        'latestVersion' => esc_html__('Latest Version', 'sim-league-toolkit'),
        'supportsResultUpload' => esc_html__('Supports Result Upload', 'sim-league-toolkit'),
        'builtIn' => esc_html__('Built In', 'sim-league-toolkit'),
        'published' => esc_html__('Published', 'sim-league-toolkit'),
      ];
    }

    protected function getHiddenColumns(): array {
      return [];
    }

    protected function getItems(): array {
      $games = Game::list();

      $results = [];
      foreach ($games as $game) {
        $results[] = $game->toTableItem();
      }

      return $results;
    }

    protected function getSortableColumns(): array {
      return array(
        'name' => ['name', true],
        'published' => ['published', true],
      );
    }
  }