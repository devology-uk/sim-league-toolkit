<?php

  namespace SLTK\Menu;

  use SLTK\Core\AdminPageSlugs;
  use SLTK\Core\Constants;
  use SLTK\Pages\Game\GameAdminPage;
  use SLTK\Pages\Game\GamesAdminPage;

  class GamesAdminMenu implements AdminMenu {

    public function init(?string $parentSlug = null): string {
      $pluralPageTitle = esc_html__('Games', 'sim-league-toolkit');
      $singlePageTitle = esc_html__('Game', 'sim-league-toolkit');
      
      add_submenu_page(
        $parentSlug,
        $pluralPageTitle,
        $pluralPageTitle,
        Constants::MANAGE_OPTIONS_PERMISSION,
        AdminPageSlugs::GAMES,
        function() {
          (new GamesAdminPage())->render();
        }
      );

      add_submenu_page(
        '-',
        $singlePageTitle,
        $singlePageTitle,
        Constants::MANAGE_OPTIONS_PERMISSION,
        AdminPageSlugs::GAME,
        function() {
          (new GameAdminPage())->render();
        }
      );

      return AdminPageSlugs::GAMES;
    }
  }