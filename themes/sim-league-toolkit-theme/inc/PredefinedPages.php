<?php

  namespace SLTK\Theme;

  class PredefinedPages {
    public static function all(): array {
      return [
        new PredefinedPage('home', __('Home', 'sim-league-toolkit-theme')),
        new PredefinedPage('championships', __('Championships', 'sim-league-toolkit-theme')),
        new PredefinedPage('events', __('Events', 'sim-league-toolkit-theme')),
      ];
    }

    public static function homeSlug(): string {
      return 'home';
    }
  }
