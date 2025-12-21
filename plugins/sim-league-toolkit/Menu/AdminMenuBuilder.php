<?php

  namespace SLTK\Menu;

  class AdminMenuBuilder {

    public static function build(): void {
      $adminMenu = new MainAdminMenu();
      $adminMenuSlug = $adminMenu->init();

      (new ChampionshipsAdminMenu())->init($adminMenuSlug);
      (new EventsAdminMenu())->init($adminMenuSlug);
      (new EventClassesAdminMenu())->init($adminMenuSlug);
      (new GamesAdminMenu())->init($adminMenuSlug);
      (new ImportAdminMenu())->init($adminMenuSlug);
      (new RaceNumbersMenu())->init($adminMenuSlug);
      (new RuleSetsAdminMenu())->init($adminMenuSlug);
      (new ScoringSetsMenu())->init($adminMenuSlug);
      (new ServersAdminMenu())->init($adminMenuSlug);
    }

    public static function init(): void {
      add_action('admin_menu', [self::class, 'build']);
    }
  }