<?php

  namespace SLTK\Pages\Game;

  use SLTK\Pages\AdminPage;

  class GamesAdminPage implements AdminPage {

    private GamesAdminPageController $controller;

    public function __construct() {
      $this->controller = new GamesAdminPageController();
    }

    public function init(): void {
    }

    public function render(): void { ?>
        <div class='wrap'>
            <h1><?= esc_html__('Games', 'sim-league-toolkit') ?></h1>
            <p>
              <?= esc_html__('Sim League Toolkit comes with built-in support for several racing games, but allows you to add the data for other games.', 'sim-league-toolkit') ?>
                <br/>
              <?= esc_html__('Adding a game is not a trivial task, you will need to provide a significant amount of information before you can publish and use the game.', 'sim-league-toolkit') ?>
            </p>
          <?php
            $this->controller->theTable();
          ?>

            <p>
              <?= esc_html__('If you prefer you can use the form below to submit a request for a game to be added.',
                'sim-league-toolkit') ?>
                <br/>
              <?= esc_html__('In the first instance the sim/game will be added, but will only support manual entry of results and standings, this may take some time as the team need to gather all the information needed to support the game and release an update.',
                'sim-league-toolkit') ?>
                <br/>
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