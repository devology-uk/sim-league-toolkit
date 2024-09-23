<?php

  namespace SLTK\Menu;

  use SLTK\Core\AdminPageSlugs;
  use SLTK\Core\Constants;
  use SLTK\Pages\Import\ImportAdminPage;

  class ImportAdminMenu implements AdminMenu {
    public function init(?string $parentSlug = null): string {
      $pageTitle = esc_html__('Import', 'sim-league-toolkit');
      add_submenu_page(
        $parentSlug,
        $pageTitle,
        $pageTitle,
        Constants::MANAGE_OPTIONS_PERMISSION,
        AdminPageSlugs::MIGRATE,
        function() {
          (new ImportAdminPage())->render();
        }
      );

      return AdminPageSlugs::MIGRATE;
    }
  }