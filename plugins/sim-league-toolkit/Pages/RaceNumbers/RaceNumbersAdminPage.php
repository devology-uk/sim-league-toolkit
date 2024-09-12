<?php

  namespace SLTK\Pages\RaceNumbers;

  use SLTK\Pages\AdminPage;

  /**
   * Renders a page for managing race number allocations
   */
  class RaceNumbersAdminPage implements AdminPage {

    private RaceNumbersAdminPageController $controller;

    public function __construct() {
      $this->controller = new RaceNumbersAdminPageController();
    }

    /**
     * @inheritDoc
     */
    public function render(): void {
      ?>
      <div class='wrap'>
        <h1><?= esc_html__('Race Numbers', 'sim-league-tool-kit') ?></h1>
        <p>
          <?= esc_html__('Members can select from available race numbers in their User Profile. However, you may need to override their
          selection particularly if you are migrating from another platform to Sim League Toolkit. Here you will find the
          tools to manage race numbers for members.', 'sim-league-tool-kit') ?>
        </p>
        <p><?= esc_html__('If a game supports custom race numbers through configuration these numbers will be applied in generated config files, otherwise they will only be displayed in front end components.', 'sim-league-tool-kit') ?></p>
      </div>

      <nav class='nav-tab-wrapper'>
        <?php
          $this->controller->theActiveAllocationsTab();
          $this->controller->thePreAllocationsTab();
        ?>
      </nav>

      <div class='tab-content'>
        <?php
          $this->controller->theTabContent();
        ?>
      </div>

      <?php
    }
  }