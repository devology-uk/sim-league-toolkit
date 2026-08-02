<?php

  namespace SLTK\Core;

  class AccLeagueToolsThemeDetector {
    private const string THEME_SLUG = 'acc-league-tools';

    public static function isInstalled(): bool {
      return wp_get_theme(self::THEME_SLUG)->exists();
    }

    public static function themeVersion(): ?string {
      if (!self::isInstalled()) {
        return null;
      }

      $version = wp_get_theme(self::THEME_SLUG)->get('Version');

      return $version ?: null;
    }
  }
