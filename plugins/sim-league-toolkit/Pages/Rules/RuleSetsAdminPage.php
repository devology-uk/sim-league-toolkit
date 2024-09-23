<?php

  namespace SLTK\Pages\Rules;

  use SLTK\Pages\AdminPage;

  class RuleSetsAdminPage implements AdminPage {
    public function render(): void {
      ?>
      <div class='wrap'>
        <h2><?= esc_html__('Rule Sets', 'sim-league-toolkit') ?></h2>
      </div>
      <?php
    }
  }