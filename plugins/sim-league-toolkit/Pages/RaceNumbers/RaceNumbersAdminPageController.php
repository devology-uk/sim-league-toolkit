<?php

  namespace SLTK\Pages\RaceNumbers;

  use SLTK\Components\MemberSelectorComponent;
  use SLTK\Core\HtmlTagProvider;
  use SLTK\Domain\Member;
  use SLTK\Domain\RaceNumber;
  use SLTK\Pages\ControllerBase;

  /**
   * Renders a page for managing member race numbers
   */
  class RaceNumbersAdminPageController extends ControllerBase {
    private const string RACE_NUMBER_FIELD_NAME = 'edit-race-number';

    private MemberSelectorComponent $memberSelector;

    /**
     * Renders a table of members with their allocated race numbers
     *
     * @return void
     */
    public function theAllocations(): void {
      $members = Member::list();
      foreach($members as $member) {
        ?>
        <tr>
          <td><?= $member->displayName ?></td>
          <td><?= $member->raceNumber ?></td>
        </tr>
        <?php
      }
    }

    /**
     * Renders hidden fields for the race number form
     *
     * @return void
     */
    public function theHiddenFields(): void {
      $this->theNonce();
    }

    /**
     * Renders the game selector component
     *
     * @return void
     */
    public function theMemberSelector(): void {
      ?>
      <tr>
        <th scope='row'>Member</th>
        <td><?php $this->memberSelector->render() ?></td>
      </tr>
      <?php
    }

    /**
     * Renders an input to capture the race number
     * @return void
     */
    public function theRaceNumberSelector(): void { ?>
      <tr>
        <th scope='row'>
          <?= esc_html__('Race Number', 'sim-league-tool-kit') ?>
        </th>
        <td>
          <select id='<?= self::RACE_NUMBER_FIELD_NAME ?>' name='<?= self::RACE_NUMBER_FIELD_NAME ?>'>
            <option value=''><?= esc_html__('Please select...', 'sim-league-tool-kit') ?></option>
            <?php
              $raceNumbers = range(1, 99);
              foreach($raceNumbers as $raceNumber) {
                ?>
                <option value='<?= $raceNumber ?>'><?= $raceNumber ?></option>
                <?php
              }
            ?>
          </select>
        </td>
      </tr>
      <?php
    }

    /**
     * @inheritDoc
     */
    protected function handleGet(): void {
      $this->memberSelector = new MemberSelectorComponent();
    }

    /**
     * @inheritDoc
     */
    protected function handlePost(): void {
      if(!$this->validateNonce()) {
        return;
      }

      $this->memberSelector = new MemberSelectorComponent();

      $userId = $this->memberSelector->getValue();
      $raceNumber = $this->getSanitisedFieldFromPost(self::RACE_NUMBER_FIELD_NAME);

      if(!isset($raceNumber)) {
        return;
      }

      RaceNumber::allocate($userId, $raceNumber);
      HtmlTagProvider::theSuccessMessage(esc_html__('The race number was allocated successfully', 'sim-league-tool-kit'));#
      $this->memberSelector->setValue(0);
    }
  }