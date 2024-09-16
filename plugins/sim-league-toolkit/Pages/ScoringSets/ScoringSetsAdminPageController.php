<?php

  namespace SLTK\Pages\ScoringSets;

  use SLTK\Pages\ControllerBase;

  class ScoringSetsAdminPageController extends ControllerBase {

    public function theTable(): void {
      $table = new ScoringSetsTable();
      $table->prepare_items();
      $table->display();
    }

    protected function handleGet(): void {}

    protected function handlePost(): void {}
  }