<?php

  namespace SLTK\Menu;

  use SLTK\Core\AdminPageSlugs;
  use SLTK\Core\Constants;
  use SLTK\Pages\Rules\RuleSetAdminPage;
  use SLTK\Pages\Rules\RuleSetsAdminPage;

  class RuleSetsAdminMenu implements AdminMenu {

    public function init(?string $parentSlug = null): string {

      $pluralPageTitle = esc_html__('Rule Sets', 'sim-league-toolkit');
      $singlePageTitle = esc_html__('Rule Set', 'sim-league-toolkit');

      add_submenu_page(
        $parentSlug,
        $pluralPageTitle,
        $pluralPageTitle,
        Constants::MANAGE_OPTIONS_PERMISSION,
        AdminPageSlugs::RULE_SETS,
        function() {
          (new RuleSetsAdminPage())->render();
        }
      );

      add_submenu_page(
        '-',
        $singlePageTitle,
        $singlePageTitle,
        Constants::MANAGE_OPTIONS_PERMISSION,
        AdminPageSlugs::RULE_SET,
        function() {
          (new RuleSetAdminPage())->render();
        }
      );

      return AdminPageSlugs::RULE_SETS;
    }
  }