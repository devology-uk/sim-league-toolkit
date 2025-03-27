<?php

  namespace SLTK\Pages\Rules;

  use SLTK\Pages\AdminPage;

  class RuleSetAdminPage implements AdminPage {

    private RuleSetAdminPageController $controller;

    public function __construct() {
      $this->controller = new RuleSetAdminPageController();
    }

    public function render(): void { ?>
        <div class='wrap'>
            <h1><?= esc_html__('Rule Set Details', 'sim-league-toolkit') ?></h1>
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
                    $this->controller->theTypeSelector();
                  ?>
                </table>
              <?php
                $this->controller->theSaveDetailsButton();
              ?>
            </form>
          <?php
            if ($this->controller->showRules()) {
              ?>
                <hr/>
                <h2><?= esc_html__('Rules', 'sim-league-toolkit') ?></h2>
              <?php
              $this->controller->theRules();
              ?>
                <hr/>
                <h3><?= esc_html__('Rule Details', 'sim-league-toolkit') ?></h3>
                <form method='post'>
                  <?php
                    $this->controller->theHiddenFields();
                    $this->controller->theRuleSetIdField();
                  ?>
                    <table class='form-table'>
                      <?php
                        $this->controller->theRuleIndexField();
                        $this->controller->theRuleEditor();
                      ?>
                    </table>
                  <?php
                    $this->controller->theSaveRuleButton();
                  ?>
                </form>
              <?php
            }
            $this->controller->theBackButton();
          ?>
        </div>
      <?php
    }
  }