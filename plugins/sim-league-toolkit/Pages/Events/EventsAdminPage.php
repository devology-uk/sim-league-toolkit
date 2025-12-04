<?php

  namespace SLTK\Pages\Events;

  use SLTK\Pages\AdminPage;

  class EventsAdminPage implements AdminPage {

    private EventsAdminPageController $controller;

    public function __construct() {
      $this->controller = new EventsAdminPageController();
    }

    public function render(): void {
      ?>
        <div class='wrap'>
          <h1><?= esc_html__('Individual Events', 'sim-league-toolkit') ?></h1>
          <p>
            <?= esc_html__('Below are the individual events you have created, these are events that are not part of a championship.', 'sim-league-toolkit') ?>
          </p>
          <?php
            $this->controller->theTable();
          ?>
        </div>
      <?php
    }
  }