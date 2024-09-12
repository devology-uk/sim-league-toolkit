<?php

  namespace SLTK\Menu;

  use SLTK\Core\AdminPageSlugs;
  use SLTK\Core\Constants;
  use SLTK\Pages\MainAdminPage;

  /**
   * Adds the top level menu for Sim League Toolkit
   */
  class MainAdminMenu implements AdminMenu {
    private const string SUB_MENU_TITLE = 'General';
    private const string TOP_LEVEL_SLUG = AdminPageSlugs::HOME;
    private const string TOP_LEVEL_TITLE = 'Sim League Toolkit';

    private MainAdminPage $adminHomePage;

    /**
     * @inheritDoc
     */
    public function init(string|null $parentSlug = null): string {
      add_menu_page(
        self::TOP_LEVEL_TITLE,
        self::TOP_LEVEL_TITLE,
        Constants::MANAGE_OPTIONS_PERMISSION,
        self::TOP_LEVEL_SLUG,
        function() {
          if(!isset($this->adminHomePage)) {
            $this->adminHomePage = new MainAdminPage();
            $this->adminHomePage->render();
          }
        },
        'dashicons-welcome-widgets-menus',
        1000
      );

      add_submenu_page(
        self::TOP_LEVEL_SLUG,
        self::TOP_LEVEL_TITLE,
        self::SUB_MENU_TITLE,
        Constants::MANAGE_OPTIONS_PERMISSION,
        self::TOP_LEVEL_SLUG,
        function() {
          if(!isset($this->adminHomePage)) {
            $this->adminHomePage = new MainAdminPage();
            $this->adminHomePage->render();
          }
        }
      );

      return self::TOP_LEVEL_SLUG;
    }
  }