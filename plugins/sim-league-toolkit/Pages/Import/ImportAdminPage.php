<?php

namespace SLTK\Pages\Import;

use SLTK\Pages\AdminPage;

class ImportAdminPage implements AdminPage {

    private ImportAdminPageController $controller;

    public function __construct() {
        $this->controller = new ImportAdminPageController();
    }

    public function render(): void {
        ?>
        <div class='wrap'>
            <h1><?= esc_html__('Import', 'sim-league-toolkit') ?></h1>
            <p><?= esc_html__('If you are using Sim League Toolkit to build a website for an existing league then the tools here will help you import your existing data to get up and running quickly.',
                    'sim-league-toolkit') ?></p>
            <nav class='nav-tab-wrapper'>
                <?php
                $this->controller->theMembersTab();
                ?>
            </nav>

            <div class="tab-content">
                <?php
                $this->controller->theTabContent();
                ?>
            </div>

        </div>
        <?php
    }
}