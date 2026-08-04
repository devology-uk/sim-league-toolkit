<?php

  namespace SLTK\Blocks\Render;

  use SLTK\Core\Constants;
  use SLTK\Core\Enums\TrophyScope;
  use SLTK\Domain\Championship;
  use SLTK\Domain\StandaloneEvent;
  use SLTK\Domain\Trophy;
  use WP_Block;

  class MyTrophiesRenderer implements BlockRenderer {
    public function render(array $attributes, string $content, WP_Block $block): string {
      $userId = get_current_user_id();

      if ($userId <= 0) {
        return $this->placeholder(__('Log in to see your trophies.', 'sim-league-toolkit'));
      }

      $limit = (int)($attributes['limit'] ?? 0);
      $trophies = Trophy::listByMemberId($userId);

      if (empty($trophies)) {
        $emptyAttributes = get_block_wrapper_attributes(['class' => 'sltk-trophy-list-empty']);

        return "<p {$emptyAttributes}>" . esc_html__("You haven't won any trophies yet.", 'sim-league-toolkit') . '</p>';
      }

      if ($limit > 0) {
        $trophies = array_slice($trophies, 0, $limit);
      }

      $items = array_map(fn($trophy) => $this->trophyItem($trophy), $trophies);
      $listAttributes = get_block_wrapper_attributes(['class' => 'sltk-trophy-list']);

      return "<ul {$listAttributes}>" . implode('', $items) . '</ul>';
    }

    private function trophyItem(Trophy $trophy): string {
      $scopeName = $this->resolveScopeName($trophy);
      $awardedDate = $trophy->getAwardedDate()->format(Constants::STANDARD_DATE_DISPLAY_FORMAT);

      return '<li class="sltk-trophy-list-item">'
        . '<span class="sltk-trophy-award-type">' . esc_html($trophy->getAwardType()->label()) . '</span>'
        . '<span class="sltk-trophy-scope">' . esc_html($scopeName) . '</span>'
        . '<span class="sltk-trophy-date">' . esc_html($awardedDate) . '</span>'
        . '</li>';
    }

    private function resolveScopeName(Trophy $trophy): string {
      return match ($trophy->getScope()) {
        TrophyScope::Championship => Championship::get($trophy->getScopeId())?->getName() ?? '',
        TrophyScope::ChampionshipEvent => Championship::getEventById($trophy->getScopeId())?->getName() ?? '',
        TrophyScope::StandaloneEvent => StandaloneEvent::get($trophy->getScopeId())?->getName() ?? '',
      };
    }

    private function placeholder(string $message): string {
      return '<p class="sltk-tile-placeholder">' . esc_html($message) . '</p>';
    }
  }
