<?php

  namespace SLTK\Menu;

  use SLTK\Core\AdminPageSlugs;
  use SLTK\Core\Constants;
  use SLTK\Pages\Game\GamesAdminPage;

  /**
   * An admin menu item to load a page for managing the Games supported by the site.
   */
  class GamesAdminMenu implements AdminMenu {

    private const PAGE_TITLE = 'Games';

    /**
     * Adds the Games sub menu item to the custom admin menu for Sim League Toolkit
     *
     * @param string|null $parentSlug
     *
     * @return string
     */
    public function init(string|null $parentSlug = null): string {
      add_submenu_page(
        $parentSlug,
        self::PAGE_TITLE,
        self::PAGE_TITLE,
        Constants::MANAGE_OPTIONS_PERMISSION,
        AdminPageSlugs::GAMES,
        function() {
          (new GamesAdminPage())->render();
        }
      );

      return AdminPageSlugs::GAMES;
    }
  }