<?php

  namespace SLTK\Pages\Events;

  use SLTK\Pages\ControllerBase;

  class EventsAdminPageController extends ControllerBase {

    public function theTable(): void {
      $table = new EventsTable();
      $table->prepare_items();
      $table->display();
    }

    protected function handleGet(): void {

    }

    protected function handlePost(): void {

    }
  }