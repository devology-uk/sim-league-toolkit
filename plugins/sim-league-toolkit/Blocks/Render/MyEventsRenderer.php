<?php

  namespace SLTK\Blocks\Render;

  use DateTime;
  use SLTK\Domain\Championship;
  use SLTK\Domain\ChampionshipEntry;
  use SLTK\Domain\StandaloneEvent;
  use SLTK\Domain\StandaloneEventEntry;
  use WP_Block;

  class MyEventsRenderer implements BlockRenderer {
    public function render(array $attributes, string $content, WP_Block $block): string {
      $userId = get_current_user_id();

      if ($userId <= 0) {
        return $this->placeholder(__('Log in to see your events.', 'sim-league-toolkit'));
      }

      $limit = (int)($attributes['limit'] ?? 0);
      $includePast = (bool)($attributes['includePast'] ?? false);

      $items = array_merge(
        $this->championshipItems($userId, $includePast),
        $this->eventItems($userId, $includePast)
      );

      if (empty($items)) {
        $emptyAttributes = get_block_wrapper_attributes(['class' => 'sltk-tile-list-empty']);

        return "<p {$emptyAttributes}>" . esc_html__("You haven't entered any events yet.", 'sim-league-toolkit') . '</p>';
      }

      usort($items, fn($a, $b) => $a['sortDate'] <=> $b['sortDate']);

      if ($limit > 0) {
        $items = array_slice($items, 0, $limit);
      }

      $tiles = array_map(fn($item) => $item['markup'], $items);
      $gridAttributes = get_block_wrapper_attributes(['class' => 'sltk-tile-grid']);

      return "<div {$gridAttributes}>" . implode('', $tiles) . '</div>';
    }

    private function championshipItems(int $userId, bool $includePast): array {
      $items = [];

      foreach (ChampionshipEntry::listByUserId($userId) as $entry) {
        $championship = Championship::get($entry->getChampionshipId());

        if ($championship === null || (!$includePast && !$championship->getIsActive())) {
          continue;
        }

        $items[] = [
          'sortDate' => $championship->getStartDate(),
          'markup' => render_block([
            'blockName' => 'sltk/championship-tile',
            'attrs' => ['championshipId' => $championship->getId()],
          ]),
        ];
      }

      return $items;
    }

    private function eventItems(int $userId, bool $includePast): array {
      $items = [];
      $today = new DateTime('today');

      foreach (StandaloneEventEntry::listByUserId($userId) as $entry) {
        $event = StandaloneEvent::get($entry->getStandaloneEventId());

        if ($event === null || (!$includePast && $event->getEventDate() < $today)) {
          continue;
        }

        $items[] = [
          'sortDate' => $event->getEventDate(),
          'markup' => render_block([
            'blockName' => 'sltk/event-tile',
            'attrs' => ['eventId' => $event->getId()],
          ]),
        ];
      }

      return $items;
    }

    private function placeholder(string $message): string {
      return '<p class="sltk-tile-placeholder">' . esc_html($message) . '</p>';
    }
  }
