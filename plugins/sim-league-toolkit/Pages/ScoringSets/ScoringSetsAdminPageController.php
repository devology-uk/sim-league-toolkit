<?php

  namespace SLTK\Pages\ScoringSets;

  use SLTK\Pages\ControllerBase;

  class ScoringSetsAdminPageController extends ControllerBase {

    public function theTable(): void {
      $table = new ScoringSetsTable();
      $table->prepare_items();
      $table->display();
    }

    /**
     * @inheritDoc
     */
    protected function handleGet(): void {
      // TODO: Implement handleGet() method.
    }

    /**
     * @inheritDoc
     */
    protected function handlePost(): void {
      // TODO: Implement handlePost() method.
    }
  }