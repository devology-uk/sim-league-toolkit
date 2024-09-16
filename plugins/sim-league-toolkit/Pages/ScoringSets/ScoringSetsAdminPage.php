<?php

  namespace SLTK\Pages\ScoringSets;

  use SLTK\Pages\AdminPage;

  class ScoringSetsAdminPage implements AdminPage {

    private ScoringSetsAdminPageController $controller;

    public function __construct() {
      $this->controller = new ScoringSetsAdminPageController();
    }

    /**
     * @inheritDoc
     */
    public function render(): void {
      ?>
      <div class='wrap'>
        <h3><?= esc_html__('Scoring Sets', 'sim-league-toolkit') ?></h3>
        <?php
          $this->controller->theTable();
        ?>
      </div>
      <?php
    }
  }