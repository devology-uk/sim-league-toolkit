<?php

  namespace SLTK\Pages\Championships;

  use SLTK\Pages\AdminPage;

  class ChampionshipsAdminPage implements AdminPage {

    private ChampionshipsAdminPageController $controller;

    public function __construct() {
      $this->controller = new ChampionshipsAdminPageController();
    }

    public function render(): void {
      ?>
        <div class='wrap'>
          <h1><?= esc_html__('Championships', 'sim-league-toolkit') ?></h1>
          <p>
            <?= esc_html__('Below are the championships you have created, championships allow you to create a series of events with points counting towards championship standings.', 'sim-league-toolkit') ?>
          </p>
          <?php
            $this->controller->theTable();
          ?>
        </div>
      <?php
    }
  }