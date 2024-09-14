<?php

  namespace SLTK\Menu;

  use SLTK\Core\AdminPageSlugs;
  use SLTK\Core\Constants;
  use SLTK\Pages\Game\GamesAdminPage;

  /**
   * An admin menu item to load a page for managing the Games supported by the site.
   */
  class GamesAdminMenu implements AdminMenu {

    /**
     * Adds the Games sub menu item to the custom admin menu for Sim League Toolkit
     *
     * @param string|null $parentSlug
     *
     * @return string
     */
    public function init(string|null $parentSlug = null): string {
      $pageTitle = esc_html__('Games', 'sim-league-toolkit');
      
      add_submenu_page(
        $parentSlug,
        $pageTitle,
        $pageTitle,
        Constants::MANAGE_OPTIONS_PERMISSION,
        AdminPageSlugs::GAMES,
        function() {
          (new GamesAdminPage())->render();
        }
      );

      return AdminPageSlugs::GAMES;
    }
  }