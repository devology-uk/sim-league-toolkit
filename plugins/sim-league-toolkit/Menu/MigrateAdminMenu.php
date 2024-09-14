<?php

  namespace SLTK\Menu;

  use SLTK\Core\AdminPageSlugs;
  use SLTK\Core\Constants;
  use SLTK\Pages\Migrate\MigrateAdminPage;

  class MigrateAdminMenu implements AdminMenu {

    private const string PAGE_TITLE = 'Migrate';

    /**
     * Adds the Migrate sub menu item to the custom admin menu for Sim League Toolkit
     *
     * @param string|null $parentSlug
     *
     * @return string
     */
    public function init(?string $parentSlug = null): string {
      add_submenu_page(
        $parentSlug,
        self::PAGE_TITLE,
        self::PAGE_TITLE,
        Constants::MANAGE_OPTIONS_PERMISSION,
        AdminPageSlugs::MIGRATE,
        function() {
          (new MigrateAdminPage())->render();
        }
      );

      return AdminPageSlugs::MIGRATE;
    }
  }