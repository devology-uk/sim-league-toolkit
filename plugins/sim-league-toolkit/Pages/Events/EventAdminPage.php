<?php

  namespace SLTK\Pages\Events;
  use SLTK\Pages\AdminPage;

  class EventAdminPage implements AdminPage {
    private EventAdminPageController $controller;

    public function __construct() {
      $this->controller = new EventAdminPageController();
    }

    public function render(): void {
    ?>
<div class="wrap">
  <h1><?= esc_html__('Event Details', 'sim-league-toolkit') ?></h1>
  <nav class='nav-tab-wrapper'>
    <?php
//      $this->controller->theGeneralTab();
//      $this->controller->theCarClassesTab();
//      $this->controller->theDriverCategoriesTab();
//      $this->controller->theCarsTab();
//      $this->controller->theTracksTab();
    ?>
  </nav>
  <div class='tab-content'>
    <?php
//      $this->controller->theTabContent();
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