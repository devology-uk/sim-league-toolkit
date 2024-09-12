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
        <!--        --><?php
          //          if($this->controller->isEditing()) {
          //            ?>
        <!--            <p>--><?php //= esc_html__('Please note: If the race number you set here is already allocated the existing allocation will be set
          //               to 0, resulting in that member receiving a game assigned number when they participate in events.', 'sim-league-tool-kit') ?>
        <!--            </p>-->
        <!--            <form method='post'>-->
        <!--              --><?php
          //                $this->controller->theEditHiddenFields();
          //                $this->controller->theRaceNumberField();
          //              ?>
        <!--              <input type='submit' class='button button-small button-primary'-->
        <!--                     value='--><?php //= esc_html__('Update', 'sim-league-tool-kit') ?><!--'-->
        <!--                     style='margin-top: .125rem'>-->
        <!--            </form>-->
        <!--            --><?php
          //          } else {
          //            $this->controller->theEditCompletedMessage();
          //            ?>
        <!--            <p>-->
        <!--              --><?php //= esc_html__('Below is the list of members who have an allocated race number. It is likely to be long so we recommend
          //              using the search feature provided to find what you are looking for.', 'sim-league-tool-kit') ?>
        <!--            </p>-->
        <!---->
        <!--            <h3>--><?php //= esc_html__('Search', 'sim-league-tool-kit') ?><!--</h3>-->
        <!--            <p>-->
        <!--              --><?php //= esc_html__('Enter some characters in the field below then click the Search button. The table below will be filtered
          //              to show only rows where the Name columns contain the search term, the Steam ID starts with the search
          //              term or the Race Number is an exact match.', 'sim-league-tool-kit') ?>
        <!--            </p>-->
        <!--            <form method='post'>-->
        <!--              --><?php
          //                $this->controller->theSearchHiddenFields();
          //                $this->controller->theSearchTermField();
          //              ?>
        <!--              <input type='submit' class='button button-small button-primary'-->
        <!--                     value='--><?php //= esc_html__('Search', 'sim-league-tool-kit') ?><!--'-->
        <!--                     style='margin-top: .125rem'>-->
        <!--            </form>-->
        <!---->
        <!--            <h2>--><?php //= esc_html__('Allocations', 'sim-league-tool-kit') ?><!--</h2>-->
        <!--            <table class='admin-table'>-->
        <!--              <thead>-->
        <!--              <tr>-->
        <!--                <th>--><?php //= esc_html__('First Name', 'sim-league-tool-kit') ?><!--</th>-->
        <!--                <th>--><?php //= esc_html__('Last Name', 'sim-league-tool-kit') ?><!--</th>-->
        <!--                <th>--><?php //= esc_html__('Username', 'sim-league-tool-kit') ?><!--</th>-->
        <!--                <th>--><?php //= esc_html__('Race Number', 'sim-league-tool-kit') ?><!--</th>-->
        <!--                <th></th>-->
        <!--              </tr>-->
        <!--              </thead>-->
        <!--              <tbody>-->
        <!--              --><?php //= $this->controller->theAllocationRows() ?>
        <!--              </tbody>-->
        <!--            </table>-->
        <!--            --><?php
          //          }
          //        ?>
      </div>
      <?php
    }
  }