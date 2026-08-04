<?php

  namespace SLTK\Theme;

  use SLTK\Blocks\Patterns\CurrentAndPastTabsPattern;

  class PredefinedPages {
    public static function all(): array {
      return [
        new PredefinedPage('home', __('Home', 'sim-league-toolkit-theme')),
        new PredefinedPage('championships', __('Championships', 'sim-league-toolkit-theme'), self::championshipsContent()),
        new PredefinedPage('events', __('Events', 'sim-league-toolkit-theme'), self::eventsContent()),
      ];
    }

    public static function homeSlug(): string {
      return 'home';
    }

    private static function championshipsContent(): string {
      if (!class_exists(CurrentAndPastTabsPattern::class)) {
        return '';
      }

      return CurrentAndPastTabsPattern::forChampionships();
    }

    private static function eventsContent(): string {
      if (!class_exists(CurrentAndPastTabsPattern::class)) {
        return '';
      }

      return CurrentAndPastTabsPattern::forEvents();
    }
  }
