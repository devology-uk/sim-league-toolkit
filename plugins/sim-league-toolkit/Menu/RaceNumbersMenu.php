<?php

  namespace SLTK\Menu;

  use SLTK\Core\AdminPageSlugs;
  use SLTK\Core\Constants;
  use SLTK\Pages\RaceNumbers\RaceNumbersAdminPage;

  /**
   * Adds the Race Numbers sub menu item to the custom admin menu for Sim League Toolkit
   */
  class RaceNumbersMenu implements AdminMenu {
    private const string PAGE_TITLE = 'Race Numbers';

    /**
     * @inheritDoc
     */
    public function init(?string $parentSlug = null): string {
      add_submenu_page(
        $parentSlug,
        self::PAGE_TITLE,
        self::PAGE_TITLE,
        Constants::MANAGE_OPTIONS_PERMISSION,
        AdminPageSlugs::RACE_NUMBERS,
        function() {
          (new RaceNumbersAdminPage())->render();
        }
      );

      return AdminPageSlugs::RACE_NUMBERS;
    }
  }