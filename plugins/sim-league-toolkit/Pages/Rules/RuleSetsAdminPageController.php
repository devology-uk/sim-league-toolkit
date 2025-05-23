<?php

  namespace SLTK\Pages\Rules;

  use SLTK\Pages\ControllerBase;

  class RuleSetsAdminPageController extends ControllerBase {

    public function theTable(): void {
      $table = new RuleSetsTable();
      $table->prepare_items();
      $table->display();
    }

    protected function handleGet(): void {}

    protected function handlePost(): void {}
  }