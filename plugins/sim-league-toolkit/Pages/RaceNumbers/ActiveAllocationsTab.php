<?php

  namespace SLTK\Pages\RaceNumbers;

  use SLTK\Pages\AdminTab;

  /**
   * Renders the content for the active race number allocations tab
   */
  class ActiveAllocationsTab implements AdminTab {

    public final const string NAME = 'active-allocations';

    private ActiveAllocationsTabController $controller;

    public function __construct() {
      $this->controller = new ActiveAllocationsTabController();
    }

    /**
     * @inheritDoc
     */
    public function render(): void { ?>
      <div class='wrap'>
        <p>
          <?= esc_html__('The table below shows members who have been allocated a race number and what that number is. ', 'sim-league-tool-kit') ?>
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