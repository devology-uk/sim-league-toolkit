<?php

  namespace SLTK\Menu;

  /**
   * Defines the members required for a type representing a menu item on the WordPress admin menu
   */
  interface AdminMenu {

    /**
     * Initialises the menu item
     *
     * @param string|null $parentSlug Options slug of the parent admin menu for use with sub menu items
     *
     * @return string The slug of the menu item
     */
    public function init(string|null $parentSlug = null): string;
  }