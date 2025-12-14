<?php

  namespace SLTK\Menu;

  use SLTK\Core\AdminPageSlugs;
  use SLTK\Core\Constants;
  use SLTK\Pages\Championships\ChampionshipAdminPage;
  use SLTK\Pages\Championships\ChampionshipsAdminPage;
  use SLTK\Pages\Championships\CreateChampionshipPage;

  class ChampionshipsAdminMenu implements AdminMenu {

    public function init(?string $parentSlug = null): string {

      $pluralPageTitle = esc_html__('Championships', 'sim-league-toolkit');
      $singlePageTitle = esc_html__('Championship', 'sim-league-toolkit');
      $newPageTitle = esc_html__('New Championship', 'sim-league-toolkit');

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

      add_submenu_page(
        '-',
        $newPageTitle,
        $newPageTitle,
        Constants::MANAGE_OPTIONS_PERMISSION,
        AdminPageSlugs::CREATE_CHAMPIONSHIP,
        function() {
          (new CreateChampionshipPage())->render();
        }
      );

      return AdminPageSlugs::CHAMPIONSHIPS;
    }
  }
