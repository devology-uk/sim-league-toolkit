<?php

  namespace SLTK\Theme;

  class ThemeActivation {
    public static function init(): void {
      add_action('after_switch_theme', [self::class, 'run']);
    }

    public static function run(): void {
      PageProvisioningService::provision();
      NavigationProvisioningService::provision();
    }
  }
