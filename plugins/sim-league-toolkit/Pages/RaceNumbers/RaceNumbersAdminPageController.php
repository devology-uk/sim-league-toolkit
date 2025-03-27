<?php

  namespace SLTK\Pages\RaceNumbers;

  use SLTK\Components\MemberSelectorComponent;
  use SLTK\Core\HtmlTagProvider;
  use SLTK\Domain\Member;
  use SLTK\Domain\RaceNumber;
  use SLTK\Pages\ControllerBase;

  class RaceNumbersAdminPageController extends ControllerBase {
    private const string RACE_NUMBER_FIELD_NAME = 'edit-race-number';

    private MemberSelectorComponent $memberSelector;

    public function theAllocations(): void {
      $members = Member::list();
      foreach ($members as $member) {
        ?>
          <tr>
              <td><?= $member->displayName ?></td>
              <td><?= $member->raceNumber ?></td>
          </tr>
        <?php
      }
    }

    public function theHiddenFields(): void {
      $this->theNonce();
    }

    public function theMemberSelector(): void {
      ?>
        <tr>
            <th scope='row'>Member</th>
            <td><?php $this->memberSelector->render() ?></td>
        </tr>
      <?php
    }

    public function theRaceNumberSelector(): void { ?>
        <tr>
            <th scope='row'>
                <label for='<?= self::RACE_NUMBER_FIELD_NAME ?>'>
                  <?= esc_html__('Race Number', 'sim-league-toolkit') ?>
                </label>
            </th>
            <td>
                <select id='<?= self::RACE_NUMBER_FIELD_NAME ?>' name='<?= self::RACE_NUMBER_FIELD_NAME ?>'>
                    <option value=''><?= esc_html__('Please select...', 'sim-league-toolkit') ?></option>
                  <?php
                    $raceNumbers = RaceNumber::getRange();
                    foreach ($raceNumbers as $raceNumber) {
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

    protected function handleGet(): void {
      $this->memberSelector = new MemberSelectorComponent();
    }

    protected function handlePost(): void {
      if (!$this->validateNonce()) {
        return;
      }

      $this->memberSelector = new MemberSelectorComponent();

      $userId = $this->memberSelector->getValue();
      $raceNumber = $this->getSanitisedFieldFromPost(self::RACE_NUMBER_FIELD_NAME);

      if (!isset($raceNumber)) {
        return;
      }

      RaceNumber::allocate($userId, $raceNumber);
      HtmlTagProvider::theSuccessMessage(esc_html__('The race number was allocated successfully', 'sim-league-toolkit'));#
      $this->memberSelector->setValue(0);
    }
  }