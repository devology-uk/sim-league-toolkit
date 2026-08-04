<?php

  namespace SLTK\Blocks\Patterns;

  final class PersonalDashboardPattern {
    public static function build(): string {
      return self::loggedOutContent() . self::loggedInContent();
    }

    private static function loggedOutContent(): string {
      $welcomeParagraph = '<!-- wp:paragraph -->'
        . '<p>' . __('Welcome — log in to see your personalized dashboard.', 'sim-league-toolkit') . '</p>'
        . '<!-- /wp:paragraph -->';

      return self::visibilityMarkup('loggedOut', $welcomeParagraph);
    }

    private static function loggedInContent(): string {
      $inner = self::selfClosingBlockMarkup('sltk/joinable-items', [])
        . '<!-- wp:sltk/tabs -->'
        . '<div class="wp-block-sltk-tabs sltk-tabs" data-active-tab-index="0">'
        . self::tabMarkup('my-events', __('My Events', 'sim-league-toolkit'), self::selfClosingBlockMarkup('sltk/my-events', []))
        . self::tabMarkup('my-results', __('My Results', 'sim-league-toolkit'), self::selfClosingBlockMarkup('sltk/my-results', []))
        . self::tabMarkup('my-trophies', __('My Trophies', 'sim-league-toolkit'), self::selfClosingBlockMarkup('sltk/my-trophies', []))
        . self::tabMarkup('latest-results', __('Latest Results', 'sim-league-toolkit'), self::selfClosingBlockMarkup('sltk/latest-results', []))
        . '</div>'
        . '<!-- /wp:sltk/tabs -->';

      return self::visibilityMarkup('loggedIn', $inner);
    }

    private static function visibilityMarkup(string $visibleTo, string $innerBlockMarkup): string {
      $attrs = wp_json_encode(['visibleTo' => $visibleTo]);

      return "<!-- wp:sltk/visibility {$attrs} -->"
        . $innerBlockMarkup
        . '<!-- /wp:sltk/visibility -->';
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

    private static function selfClosingBlockMarkup(string $blockName, array $attrs): string {
      if (empty($attrs)) {
        return '<!-- wp:' . $blockName . ' /-->';
      }

      return '<!-- wp:' . $blockName . ' ' . wp_json_encode($attrs) . ' /-->';
    }
  }
