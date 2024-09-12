<?php

  namespace SLTK\Core;

  use SLTK\Domain\RaceNumber;
  use WP_User;

  /**
   * Extends the user profile editing experience to capture properties that support Sim League Toolkit
   */
  class UserProfileExtension {
    private const string ERRORS_TRANSIENT_KEY = 'sltk-profile-error-key';
    private const string PLAYSTATION_ID_FIELD_NAME = 'sltk-playstation-id';
    private const string RACE_NUMBER_FIELD_NAME = 'sltk-race-number';
    private const string RACE_NUMBER_OVERRIDE_FIELD_NAME = 'sltk-race-number-override';
    private const string STEAM_ID_FIELD_NAME = 'sltk-steam-id';
    private const string WORDPRESS_NONCE_PREFIX = 'update-user_';
    private const string XBOX_ID_FIELD_NAME = 'sltk-xbox-id';

    public static function handleCustomFieldsError(): void {
      if(empty($_GET[Constants::WORDPRESS_UPDATED_KEY])) {
        return;
      }

      $errorMessages = get_transient(self::ERRORS_TRANSIENT_KEY) ?? [];
      if(!is_array($errorMessages) || empty($errorMessages)) {
        return;
      }

      foreach($errorMessages as $errorMessage) {
        HtmlTagProvider::theErrorMessage($errorMessage);
      }

      delete_transient(self::ERRORS_TRANSIENT_KEY);
    }

    /**
     * Initialises the extension to add custom fields to profile
     *
     * @return void
     */
    public static function init(): void {
      add_action('show_user_profile', [self::class, 'renderCustomFields']);
      add_action('edit_user_profile', [self::class, 'renderCustomFields']);
      add_action('personal_options_update', [self::class, 'saveChanges']);
      add_action('edit_user_profile_update', [self::class, 'saveChanges']);
      add_action('load-user-edit.php', [self::class, 'handleCustomFieldsError']);
      add_action('load-profile.php', [self::class, 'handleCustomFieldsError']);
    }

    public static function renderCustomFields(WP_User $user): void {
      $userData = get_user_meta($user->ID);
      $currentRaceNumber = (int)($userData[UserMetaKeys::RACE_NUMBER][0] ?? 0);

      ?>
      <h3><?= esc_html__('Sim League Toolkit', 'sim-league-tool-kit') ?></h3>
      <p><?= esc_html__('At least one of the following platform IDs must be provided.', 'sim-league-tool-kit') ?></p>
      <table class='form-table'>
        <tr>
          <th scope='row'><?= esc_html__('Steam ID', 'sim-league-tool-kit') ?></th>
          <td>
            <input type='text' name='<?= self::STEAM_ID_FIELD_NAME ?>' id='<?= self::STEAM_ID_FIELD_NAME ?>'
                   value='<?= $userData[UserMetaKeys::STEAM_ID][0] ?? '' ?>' />
          </td>
        </tr>
        <tr>
          <th scope='row'><?= esc_html__('PlayStation ID', 'sim-league-tool-kit') ?></th>
          <td>
            <input type='text' name='<?= self::PLAYSTATION_ID_FIELD_NAME ?>' id='<?= self::PLAYSTATION_ID_FIELD_NAME ?>'
                   value='<?= $userData[UserMetaKeys::PLAYSTATION_ID][0] ?? '' ?>' />
          </td>
        </tr>
        <tr>
          <th scope='row'><?= esc_html__('XBox ID', 'sim-league-tool-kit') ?></th>
          <td>
            <input type='text' name='<?= self::XBOX_ID_FIELD_NAME ?>' id='<?= self::XBOX_ID_FIELD_NAME ?>'
                   value='<?= $userData[UserMetaKeys::XBOX_ID][0] ?? '' ?>' />
          </td>
        </tr>
        <tr>
          <th scope='row'><?= esc_html__('Race Number', 'sim-league-tool-kit') ?></th>
          <td>
            <span><?= $currentRaceNumber ?></span>
          </td>
          <th scope='row'>Change To</th>
          <td>
            <select type='text' name='<?= self::RACE_NUMBER_FIELD_NAME ?>' id='<?= self::RACE_NUMBER_FIELD_NAME ?>'
                    title='<?= esc_html__('Available race numbers', 'sim-league-tool-kit') ?>
                                                                                                                      '>
              <option value='0'><?= esc_html__('Select an available race number...', 'sim-league-tool-kit') ?></option>
              <?php
                $availableRaceNumbers = RaceNumber::listAvailable();
                foreach($availableRaceNumbers as $raceNumber) {
                  ?>
                  <option value='<?= $raceNumber ?>' <?= selected($raceNumber, $currentRaceNumber, false) ?>><?= $raceNumber ?></option>
                  <?php
                }
              ?>
            </select>
          </td>
          <?php
            if(is_admin()) {
              ?>
              <th scope='row'><?= esc_html__('Admin Race Number Override', 'sim-league-tool-kit') ?></th>
              <td>
                <input type='text' name='<?= self::RACE_NUMBER_OVERRIDE_FIELD_NAME ?>'
                       id='<?= self::RACE_NUMBER_OVERRIDE_FIELD_NAME ?>'
                       title='<?= esc_html__('WARNING: If the number allocated here is already in use the existing allocation will be set to 0.', 'sim-league-tool-kit') ?>' />
              </td>
              <?php
            }
          ?>
        </tr>
      </table>
      <?php
    }

    public static function saveChanges(int $userId): void {
      delete_transient(self::ERRORS_TRANSIENT_KEY);

      if(empty($_POST[Constants::WORDPRESS_NONCE_NAME]) || !wp_verify_nonce($_POST[Constants::WORDPRESS_NONCE_NAME], self::WORDPRESS_NONCE_PREFIX . $userId)) {
        return;
      }

      if(!current_user_can(Constants::EDIT_USER_PERMISSION, $userId)) {
        return;
      }

      $userMeta = get_user_meta($userId);
      $steamId = $_POST[self::STEAM_ID_FIELD_NAME] ?? '';
      $playStationId = $_POST[self::PLAYSTATION_ID_FIELD_NAME] ?? '';
      $xboxId = $_POST[self::XBOX_ID_FIELD_NAME] ?? '';
      $raceNumber = (int)($_POST[self::RACE_NUMBER_FIELD_NAME] ?? 0);
      $raceNumberOverride = $_POST[self::RACE_NUMBER_OVERRIDE_FIELD_NAME];

      update_user_meta($userId, UserMetaKeys::STEAM_ID, $steamId, $userMeta[UserMetaKeys::STEAM_ID][0]);
      update_user_meta($userId, UserMetaKeys::PLAYSTATION_ID, $playStationId, $userMeta[UserMetaKeys::PLAYSTATION_ID][0]);
      update_user_meta($userId, UserMetaKeys::XBOX_ID, $xboxId, $userMeta[UserMetaKeys::XBOX_ID][0]);
      if($raceNumber !== 0) {
        update_user_meta($userId, UserMetaKeys::RACE_NUMBER, (int)$raceNumber, (int)$userMeta[UserMetaKeys::RACE_NUMBER][0]);
      }

      if(!empty($raceNumberOverride)) {
        RaceNumber::reset($raceNumberOverride);
        update_user_meta($userId, UserMetaKeys::RACE_NUMBER, (int)$raceNumberOverride, (int)$userMeta[UserMetaKeys::RACE_NUMBER][0]);
      }

      $errors = [];

      if(empty($steamId) && empty($playStationId) && empty($xboxId)) {
        $errors[] = esc_html__('At least one of the platform ID fields is required for Sim League Toolkit to function properly.', 'sim-league-toolkit');
      }

      if(!empty($steamId) && (!is_numeric($steamId) || mb_strlen($steamId) !== 17)) {
        $errors[] = esc_html__('Steam ID is not valid, it must be a 17 digit number without any prefix or suffix.', 'sim-league-toolkit');
      }

      if(!empty($playStationId) && (!is_numeric($playStationId) || mb_strlen($steamId) < 18) || mb_strlen($playStationId) > 19) {
        $errors[] = esc_html__('PlayStation ID is not valid, it must be an 18 or 19 digit number without any prefix or suffix.', 'sim-league-toolkit');
      }

      if(count($errors)) {
        set_transient(self::ERRORS_TRANSIENT_KEY, $errors, 15);
      }
    }

  }