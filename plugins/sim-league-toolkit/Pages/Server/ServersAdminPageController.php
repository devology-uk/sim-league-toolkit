<?php

  namespace SLTK\Pages\Server;

  use SLTK\Pages\ControllerBase;

  /**
   * Provides members to support the Servers Admin Page
   */
  class ServersAdminPageController extends ControllerBase {

    /**
     * Renders the servers table
     *
     * @return void
     */
    public function theTable(): void {
      $table = new ServersTable();
      $table->prepare_items();
      $table->display();
    }

    protected function handleGet(): void {
      // TODO: Implement handleGet() method.
    }

    protected function handlePost(): void {
      // TODO: Implement handlePost() method.
    }
  }