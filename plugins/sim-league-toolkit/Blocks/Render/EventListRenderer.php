<?php

  namespace SLTK\Blocks\Render;

  use SLTK\Domain\StandaloneEvent;
  use WP_Block;

  class EventListRenderer implements BlockRenderer {
    use ParsesListingFilterAttributes;

    public function render(array $attributes, string $content, WP_Block $block): string {
      $events = StandaloneEvent::search($this->filterFromAttributes($attributes));

      if (empty($events)) {
        $emptyAttributes = get_block_wrapper_attributes(['class' => 'sltk-tile-list-empty']);

        return "<p {$emptyAttributes}>" . esc_html__('No events found.', 'sim-league-toolkit') . '</p>';
      }

      $tiles = array_map(
        fn($event) => render_block([
          'blockName' => 'sltk/event-tile',
          'attrs' => ['eventId' => $event->getId()],
        ]),
        $events
      );

      $gridAttributes = get_block_wrapper_attributes(['class' => 'sltk-tile-grid']);

      return "<div {$gridAttributes}>" . implode('', $tiles) . '</div>';
    }
  }
