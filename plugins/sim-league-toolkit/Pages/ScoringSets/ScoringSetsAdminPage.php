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
            <?php
            $this->controller->theTable();
            ?>
        </div>
        <?php
    }
}