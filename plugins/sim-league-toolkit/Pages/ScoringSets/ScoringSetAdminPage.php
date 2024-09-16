<?php

  namespace SLTK\Pages\ScoringSets;

  use SLTK\Pages\AdminPage;

  class ScoringSetAdminPage implements AdminPage {

    private ScoringSetAdminPageController $controller;

    public function __construct() {
      $this->controller = new ScoringSetAdminPageController();
    }

    public function render(): void {
      ?>
      <div class='wrap'>
        <h3><?= esc_html__('Scoring Set Details', 'sim-league-toolkit') ?></h3>
        <?php
          $this->controller->theInstructions();
        ?>
        <form method='post'>
          <?php
            $this->controller->theHiddenFields();
          ?>
          <table class='form-table'>
            <?php
              $this->controller->theNameField();
              $this->controller->thePointsForFastestLapField();
              $this->controller->thePointsForFinishingField();
              $this->controller->thePointsForPoleField();
            ?>
          </table>
          <input type='submit' name='submit' id='submit' class='button button-primary' value='Save'>
        </form>
      </div>
      <?php
    }
  }