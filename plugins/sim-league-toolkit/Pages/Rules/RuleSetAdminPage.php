<?php

namespace SLTK\Pages\Rules;

use SLTK\Pages\AdminPage;

class RuleSetAdminPage implements AdminPage {

    public function render(): void { ?>
        <div class='wrap'>
            <h2><?= esc_html__('Rule Set', 'sim-league-toolkit') ?></h2>
        </div>
        <?php
    }
}