<?php

  namespace SLTK\Pages;

  /**
   * Defines the members required for a type representing a WordPress admin page
   */
  interface AdminPage {
    /**
     * Renders the page output
     *
     * @return void
     */
    public function render(): void;
  }