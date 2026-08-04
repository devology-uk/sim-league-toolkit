<?php

  namespace SLTK\Blocks\Render;

  use SLTK\Domain\Championship;
  use SLTK\Domain\ChampionshipEntry;
  use SLTK\Domain\StandaloneEvent;
  use SLTK\Domain\StandaloneEventEntry;
  use WP_Block;

  class JoinableItemsRenderer implements BlockRenderer {
    use ParsesListingFilterAttributes;

    public function render(array $attributes, string $content, WP_Block $block): string {
      $userId = get_current_user_id();

      if ($userId <= 0) {
        return '';
      }

      $filter = $this->filterFromAttributes($attributes);

      $enteredChampionshipIds = array_map(
        fn($entry) => $entry->getChampionshipId(),
        ChampionshipEntry::listByUserId($userId)
      );
      $enteredEventIds = array_map(
        fn($entry) => $entry->getStandaloneEventId(),
        StandaloneEventEntry::listByUserId($userId)
      );

      $joinableChampionships = array_filter(
        Championship::search($filter),
        fn($championship) => !in_array($championship->getId(), $enteredChampionshipIds, true)
      );
      $joinableEvents = array_filter(
        StandaloneEvent::search($filter),
        fn($event) => !in_array($event->getId(), $enteredEventIds, true)
      );

      if (empty($joinableChampionships) && empty($joinableEvents)) {
        return '';
      }

      $tiles = array_merge(
        array_map(
          fn($championship) => render_block([
            'blockName' => 'sltk/championship-tile',
            'attrs' => ['championshipId' => $championship->getId()],
          ]),
          $joinableChampionships
        ),
        array_map(
          fn($event) => render_block([
            'blockName' => 'sltk/event-tile',
            'attrs' => ['eventId' => $event->getId()],
          ]),
          $joinableEvents
        )
      );

      $gridAttributes = get_block_wrapper_attributes(['class' => 'sltk-tile-grid']);

      return "<div {$gridAttributes}>" . implode('', $tiles) . '</div>';
    }
  }
