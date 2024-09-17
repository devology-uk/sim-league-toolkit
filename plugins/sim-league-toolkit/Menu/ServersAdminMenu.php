<?php

  namespace SLTK\Menu;

  use SLTK\Core\AdminPageSlugs;
  use SLTK\Core\Constants;
  use SLTK\Pages\Server\ServerAdminPage;
  use SLTK\Pages\Server\ServersAdminPage;

  class ServersAdminMenu implements AdminMenu {
    /**
     * @inheritDoc
     */
    public function init(string|null $parentSlug = null): string {
      $pluralPateTitle = esc_html__('Servers', 'sim-league-toolkit');
      $singlePageTitle = esc_html__('Server', 'sim-league-toolkit');

      add_submenu_page(
        $parentSlug,
        $pluralPateTitle,
        $pluralPateTitle,
        Constants::MANAGE_OPTIONS_PERMISSION,
        AdminPageSlugs::SERVERS,
        function() {
          (new ServersAdminPage())->render();
        }
      );

      add_submenu_page(
        '-',
        $singlePageTitle,
        $singlePageTitle,
        Constants::MANAGE_OPTIONS_PERMISSION,
        AdminPageSlugs::SERVER,
        function() {
          (new ServerAdminPage())->render();
        }
      );

      return AdminPageSlugs::SERVERS;
    }
  }