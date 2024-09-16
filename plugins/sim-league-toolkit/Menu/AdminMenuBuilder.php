<?php

  namespace SLTK\Menu;

  /**
   * Builds the custom admin menu for Sim League Toolkit
   */
  class AdminMenuBuilder {

    /**
     * Builds the menu
     *
     * @return void
     */
    public static function build(): void {
      $adminMenu = new MainAdminMenu();
      $adminMenuSlug = $adminMenu->init();

      (new GamesAdminMenu())->init($adminMenuSlug);
      (new MigrateAdminMenu())->init($adminMenuSlug);
      (new RaceNumbersMenu())->init($adminMenuSlug);
      (new ScoringSetsMenu())->init($adminMenuSlug);
      (new ServersAdminMenu())->init($adminMenuSlug);

    }

    /**
     * Registers the build method to be called when the WordPress admin_menu hook is triggered
     *
     * @return void
     */
    public static function init(): void {
      add_action('admin_menu', [self::class, 'build']);
    }
  }