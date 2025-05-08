<?php

  namespace SLTK\Menu;

  use SLTK\Core\AdminPageSlugs;
  use SLTK\Core\Constants;
  use SLTK\Pages\ScoringSets\ScoringSetAdminPage;
  use SLTK\Pages\ScoringSets\ScoringSetsAdminPage;

  class ScoringSetsMenu implements AdminMenu {

    public function init(?string $parentSlug = null): string {
      $pluralPageTitle = esc_html__('Scoring Sets', 'sim-league-toolkit');
      $singlePageTitle = esc_html__('Scoring Set', 'sim-league-toolkit');

      add_submenu_page(
        $parentSlug,
        $pluralPageTitle,
        $pluralPageTitle,
        Constants::MANAGE_OPTIONS_PERMISSION,
        AdminPageSlugs::SCORING_SETS,
        function() {
          (new ScoringSetsAdminPage())->render();
        }
      );

      add_submenu_page(
        '-',
        $singlePageTitle,
        $singlePageTitle,
        Constants::MANAGE_OPTIONS_PERMISSION,
        AdminPageSlugs::SCORING_SET,
        function() {
          (new ScoringSetAdminPage())->render();
        }
      );

      return AdminPageSlugs::SCORING_SETS;
    }
  }