<?php

  namespace SLTK\Pages\Rules;

  use SLTK\Pages\AdminPage;

  class RuleSetsAdminPage implements AdminPage {

    private RuleSetsAdminPageController $controller;

    public function __construct() {
      $this->controller = new RuleSetsAdminPageController();
    }
    public function render(): void {
      ?>
      <div class='wrap'>
        <h1><?= esc_html__('Rule Sets', 'sim-league-toolkit') ?></h1>
        <p>
          <?= esc_html__('Rule Sets allow you to create re-usable sets of rules that can be assigned to specific Championships or Events.', 'sim-league-toolkit') ?>
        </p>
        <?php
          $this->controller->theTable();
        ?>
      </div>
      <?php
    }
  }