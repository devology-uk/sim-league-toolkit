<?php

  namespace SLTK\Blocks\Patterns;

  final class CurrentAndPastTabsPattern {
    public static function forChampionships(int $lookbackDays = 14): string {
      return self::build('sltk/championship-list', $lookbackDays);
    }

    public static function forEvents(int $lookbackDays = 14): string {
      return self::build('sltk/event-list', $lookbackDays);
    }

    private static function build(string $listBlockName, int $lookbackDays): string {
      $currentListBlock = self::listBlockMarkup($listBlockName, [
        'showAll' => false,
        'hasStartLimit' => true,
        'startOffsetDays' => -$lookbackDays,
        'hasEndLimit' => false,
        'includeInactive' => false,
      ]);

      $pastListBlock = self::listBlockMarkup($listBlockName, [
        'showAll' => false,
        'hasStartLimit' => false,
        'hasEndLimit' => true,
        'endOffsetDays' => -$lookbackDays,
        'includeInactive' => false,
      ]);

      return '<!-- wp:sltk/tabs -->'
        . '<div class="wp-block-sltk-tabs sltk-tabs">'
        . self::tabMarkup('current', __('Current & Recent', 'sim-league-toolkit'), $currentListBlock)
        . self::tabMarkup('past', __('Past', 'sim-league-toolkit'), $pastListBlock)
        . '</div>'
        . '<!-- /wp:sltk/tabs -->';
    }

    private static function tabMarkup(string $tabId, string $label, string $innerBlockMarkup): string {
      $attrs = wp_json_encode(['label' => $label, 'tabId' => $tabId]);

      return "<!-- wp:sltk/tab {$attrs} -->"
        . '<div class="wp-block-sltk-tab sltk-tab">'
        . '<button type="button" class="sltk-tab-button" id="sltk-tab-' . $tabId . '" aria-controls="sltk-tab-panel-' . $tabId . '" role="tab">'
        . esc_html($label)
        . '</button>'
        . '<div class="sltk-tab-panel" id="sltk-tab-panel-' . $tabId . '" role="tabpanel" aria-labelledby="sltk-tab-' . $tabId . '">'
        . $innerBlockMarkup
        . '</div>'
        . '</div>'
        . '<!-- /wp:sltk/tab -->';
    }

    private static function listBlockMarkup(string $blockName, array $attrs): string {
      return '<!-- wp:' . $blockName . ' ' . wp_json_encode($attrs) . ' /-->';
    }
  }
