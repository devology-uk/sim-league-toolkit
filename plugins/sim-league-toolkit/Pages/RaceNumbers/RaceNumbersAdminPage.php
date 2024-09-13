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
          <?= esc_html__('Some games supported by Sim League Toolkit allow specific race numbers to be allocated to drivers or cars.', 'sim-league-tool-kit') ?>
          <?= esc_html__('Here you can view and manage allocation of race numbers to drivers.', 'sim-league-tool-kit') ?>
          <?= esc_html__('When you generate configuration files for a game server the files will include allocation of these numbers to drivers if the game supports this feature, otherwise they will only be displayed on the website when you use our blocks.', 'sim-league-tool-kit') ?>
        </p>
        <p>
          <?= esc_html__('Use the form below to allocate or change a members race number. ', 'sim-league-tool-kit') ?>
          <?= esc_html__('Be aware that allocating a race number that is already allocated will result in the original allocation being set to 0 (zero).', 'sim-league-tool-kit') ?>
        </p>
        <form method='post'>
          <?php
            $this->controller->theHiddenFields();
          ?>
          <table class='form-table'>
            <?php
              $this->controller->theMemberSelector();
              $this->controller->theRaceNumberSelector();
            ?>
          </table>
          <input type='submit' value='Allocate' class='button button-primary' />
        </form>
        <p>
          <?= esc_html__('The table below shows members who have been allocated a race number and what that number is. ', 'sim-league-tool-kit') ?>
        </p>
        <table class='admin-table'>
          <thead>
          <tr>
            <th><?= esc_html__('Member', 'sim-league-tool-kit') ?></th>
            <th>#</th>
          </tr>
          </thead>
          <tbody>
          <?php
            $this->controller->theAllocations();
          ?>
          </tbody>
        </table>
      </div>

      <?php
    }
  }