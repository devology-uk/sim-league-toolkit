<?php

  namespace SLTK\Core;

  class MenuManager {
    public static function init(): void {
      add_action('admin_menu', [MenuManager::class, 'registerMenu']);
    }

    public static function pageContent(): void {
      printf(
        '<div class="wrap" id="sltk-admin-root">%s</div>',
        esc_html__('Loading…', 'sim-league-toolkit')
      );
    }

    public static function registerMenu(): void {
      add_menu_page(
        __('Sim League Toolkit', 'sim-league-toolkit'),
        __('Sim League Toolkit', 'sim-league-toolkit'),
        'manage_options',
        'sim-league-toolkit',
        [MenuManager::class, 'pageContent'],
        'dashicons-welcome-widgets-menus',
      );
    }
  }


