<?php

  namespace SLTK\Menu;

  use SLTK\Core\AdminPageSlugs;
  use SLTK\Core\Constants;
  use SLTK\Pages\RaceNumbers\RaceNumbersAdminPage;

  /**
   * Adds the Race Numbers sub menu item to the custom admin menu for Sim League Toolkit
   */
  class RaceNumbersMenu implements AdminMenu {

    /**
     * @inheritDoc
     */
    public function init(?string $parentSlug = null): string {
      $pageTitle = esc_html__('Race Numbers', 'sim-league-toolkit');
      add_submenu_page(
        $parentSlug,
        $pageTitle,
        $pageTitle,
        Constants::MANAGE_OPTIONS_PERMISSION,
        AdminPageSlugs::RACE_NUMBERS,
        function() {
          (new RaceNumbersAdminPage())->render();
        }
      );

      return AdminPageSlugs::RACE_NUMBERS;
    }
  }