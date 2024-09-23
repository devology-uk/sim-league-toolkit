<?php

  namespace SLTK\Pages\Game;

  use SLTK\Pages\AdminPage;

  class GamesAdminPage implements AdminPage {

    private GamesAdminPageController $controller;

    public function __construct() {
      $this->controller = new GamesAdminPageController();
    }

    public function init(): void {}

    public function render(): void { ?>
      <div class='wrap'>
        <h1><?= esc_html__('Games', 'sim-league-toolkit') ?></h1>
        <p><?= esc_html__('The sims/games that are supported by Sim League Toolkit are listed below.',
                          'sim-league-toolkit') ?></p>
        <table class='admin-table'>
          <thead>
          <tr>
            <th><?= esc_html__('Name', 'sim-league-toolkit') ?></th>
            <th><?= esc_html__('Supported Version', 'sim-league-toolkit') ?></th>
            <th><?= esc_html__('Supports Result Import', 'sim-league-toolkit') ?></th>
          </tr>
          </thead>
          <tbody>
          <?php $this->controller->theGamesRows() ?>
          </tbody>
        </table>

        <p>
          <?= esc_html__('If a sim/game is not supported, you can use the form below to submit a request for it to be added.',
                         'sim-league-toolkit') ?>
          <br />
          <?= esc_html__('In the first instance the sim/game will be added, but will only support manual entry of results and standings.',
                         'sim-league-toolkit') ?>
          <br />
          <?= esc_html__('The team will then investigate and if possible add support for importing results and calculating standings.',
                         'sim-league-toolkit') ?>
        </p>
        <form method='post'>
          <?php
            $this->controller->theHiddenFields();
          ?>
          <table class='form-table'>
            <?php
              $this->controller->theUnsupportedGameField();
            ?>
          </table>
          <?php
            submit_button('Send Request');
          ?>
        </form>

      </div>
      <?php
    }
  }