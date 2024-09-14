<?php

  namespace SLTK\Menu;

  use SLTK\Core\AdminPageSlugs;
  use SLTK\Core\Constants;
  use SLTK\Pages\Migrate\MigrateAdminPage;

  class MigrateAdminMenu implements AdminMenu {
    /**
     * Adds the Migrate sub menu item to the custom admin menu for Sim League Toolkit
     *
     * @param string|null $parentSlug
     *
     * @return string
     */
    public function init(?string $parentSlug = null): string {
      $pageTitle = esc_html__('Migrate', 'sim-league-toolkit');
      add_submenu_page(
        $parentSlug,
        $pageTitle,
        $pageTitle,
        Constants::MANAGE_OPTIONS_PERMISSION,
        AdminPageSlugs::MIGRATE,
        function() {
          (new MigrateAdminPage())->render();
        }
      );

      return AdminPageSlugs::MIGRATE;
    }
  }