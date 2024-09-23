<?php

  namespace SLTK\Pages\Server;

  use SLTK\Pages\AdminPage;

  class ServersAdminPage implements AdminPage {
    private ServersAdminPageController $controller;

    public function __construct() {
      $this->controller = new ServersAdminPageController();
    }

    public function render(): void {
      ?>
      <div class='wrap'>
        <h1><?= esc_html__('Servers', 'sim-league-toolkit') ?></h1>
        <p>
          <?= esc_html__('A server in Sim League Toolkit is a representation of a game server and the configuration settings to use when when generating configuration files for an event.',
                         'sim-league-toolkit') ?>
          <?= esc_html__('Server settings are used as defaults for any event that uses the server.  They are copied to any event created for the same game and platform, but can be overridden at the event level.',
                         'sim-league-toolkit') ?>
        </p>
        <?php
          $this->controller->theTable();
        ?>
      </div>
      <?php
    }
  }