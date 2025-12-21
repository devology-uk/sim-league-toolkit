<?php

  namespace SLTK\Pages\EventClasses;

  use SLTK\Pages\AdminPage;

  class EventClassAdminPage implements AdminPage {

    public function render(): void {
      ?>
        <div class='wrap'>
            <h1><?= esc_html__('Rule Set Details', 'sim-league-toolkit') ?></h1>

        </div>
      <?php
    }
  }