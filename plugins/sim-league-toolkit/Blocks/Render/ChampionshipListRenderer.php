<?php

  namespace SLTK\Blocks\Render;

  use SLTK\Domain\Championship;
  use WP_Block;

  class ChampionshipListRenderer implements BlockRenderer {
    use ParsesListingFilterAttributes;

    public function render(array $attributes, string $content, WP_Block $block): string {
      $championships = Championship::search($this->filterFromAttributes($attributes));

      if (empty($championships)) {
        return '<p class="sltk-tile-list-empty">' . esc_html__('No championships found.', 'sim-league-toolkit') . '</p>';
      }

      $tiles = array_map(
        fn($championship) => render_block([
          'blockName' => 'sltk/championship-tile',
          'attrs' => ['championshipId' => $championship->getId()],
        ]),
        $championships
      );

      return '<div class="sltk-tile-grid">' . implode('', $tiles) . '</div>';
    }
  }
