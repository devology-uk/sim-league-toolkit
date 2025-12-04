<?php

  namespace SLTK\Pages\Championships;

  use SLTK\Pages\ControllerBase;

  class ChampionshipsAdminPageController extends ControllerBase {

    public function theTable(): void {
      $table = new ChampionshipsTable();
      $table->prepare_items();
      $table->display();
    }

    protected function handleGet(): void {

    }

    protected function handlePost(): void {

    }
  }