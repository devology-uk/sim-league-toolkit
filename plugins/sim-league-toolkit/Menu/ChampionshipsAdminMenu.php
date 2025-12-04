<?php

  namespace SLTK\Menu;

  use SLTK\Core\AdminPageSlugs;
  use SLTK\Core\Constants;
  use SLTK\Pages\Championships\ChampionshipAdminPage;
  use SLTK\Pages\Championships\ChampionshipsAdminPage;

  class ChampionshipsAdminMenu implements AdminMenu {

    public function init(?string $parentSlug = null): string {

      $pluralPageTitle = esc_html__('Championships', 'sim-league-toolkit');
      $singlePageTitle = esc_html__('Championship', 'sim-league-toolkit');

      add_submenu_page(
        $parentSlug,
        $pluralPageTitle,
        $pluralPageTitle,
        Constants::MANAGE_OPTIONS_PERMISSION,
        AdminPageSlugs::CHAMPIONSHIPS,
        function() {
          (new ChampionshipsAdminPage())->render();
        }
      );

      add_submenu_page(
        '-',
        $singlePageTitle,
        $singlePageTitle,
        Constants::MANAGE_OPTIONS_PERMISSION,
        AdminPageSlugs::CHAMPIONSHIP,
        function() {
          (new ChampionshipAdminPage())->render();
        }
      );

      return AdminPageSlugs::CHAMPIONSHIPS;
    }
  }
