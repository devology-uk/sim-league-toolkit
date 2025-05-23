<?php

  namespace SLTK\Pages\Game;


  use SLTK\Pages\AdminPage;

  class GameAdminPage implements AdminPage {
    private GameAdminPageController $controller;

    public function __construct() {
      $this->controller = new GameAdminPageController();
    }

    public function render(): void { ?>
        <div class="wrap">
            <h1><?= esc_html__('Game Manager', 'sim-league-toolkit') ?></h1>
             <nav class='nav-tab-wrapper'>
               <?php
                    $this->controller->theGeneralTab();
                    $this->controller->theCarClassesTab();
                    $this->controller->theDriverCategoriesTab();
                    $this->controller->theCarsTab();
               ?>
             </nav>
             <div class='tab-content'>
               <?php
                $this->controller->theTabContent();
               ?>
             </div>
            <p>
              <?php
                $this->controller->theBackButton();
              ?>
            </p>
        </div>
      <?php

    }
  }