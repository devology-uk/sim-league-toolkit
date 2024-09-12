<?php

  namespace SLTK\Pages;

  /**
   * Defines the members that must be implemented by a tab within an admin page
   */
  interface AdminTab {
    /**
     * Renders the content of the tab
     *
     * @return void
     */
    public function render(): void;
  }