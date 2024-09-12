<?php

  namespace SLTK\Menu;

  use SLTK\Core\AdminPageSlugs;
  use SLTK\Core\Constants;
  use SLTK\Pages\Server\ServerAdminPage;
  use SLTK\Pages\Server\ServersAdminPage;

  class ServersAdminMenu implements AdminMenu {

    private const string PAGE_TITLE = 'Servers';
    private const string SERVER_PAGE_TITLE = 'Server';

    /**
     * @inheritDoc
     */
    public function init(string|null $parentSlug = null): string {
      add_submenu_page(
        $parentSlug,
        self::PAGE_TITLE,
        self::PAGE_TITLE,
        Constants::MANAGE_OPTIONS_PERMISSION,
        AdminPageSlugs::SERVERS,
        function() {
          (new ServersAdminPage())->render();
        }
      );

      add_submenu_page(
        '',
        self::SERVER_PAGE_TITLE,
        self::SERVER_PAGE_TITLE,
        Constants::MANAGE_OPTIONS_PERMISSION,
        AdminPageSlugs::SERVER,
        function() {
          (new ServerAdminPage())->render();
        }
      );

      return AdminPageSlugs::SERVERS;
    }
  }