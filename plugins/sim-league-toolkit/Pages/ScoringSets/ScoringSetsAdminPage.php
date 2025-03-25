<?php

namespace SLTK\Pages\ScoringSets;

use SLTK\Pages\AdminPage;

class ScoringSetsAdminPage implements AdminPage {

    private ScoringSetsAdminPageController $controller;

    public function __construct() {
        $this->controller = new ScoringSetsAdminPageController();
    }

    public function render(): void {
        ?>
        <div class='wrap'>
            <h1><?= esc_html__('Scoring Sets', 'sim-league-toolkit') ?></h1>
            <p>
              <?= esc_html__('Scoring Sets allow you to create re-usable sets of scores that can be assigned to specific Championships or Events and used to assign points when results are entered or imported..', 'sim-league-toolkit') ?>
            </p>
            <?php
            $this->controller->theTable();
            ?>
        </div>
        <?php
    }
}