<?php

    namespace SLTK\Core;

    use SLTK\Domain\Country;
    use SLTK\Domain\RaceNumber;
    use WP_User;

    class UserProfileExtension {
        private const string COUNTRY_ID_FIELD_NAME = 'sltk-country-id';
        private const string ERRORS_TRANSIENT_KEY = 'sltk-profile-error-key';
        private const string PLAYSTATION_ID_FIELD_NAME = 'sltk-playstation-id';
        private const string RACE_NUMBER_FIELD_NAME = 'sltk-race-number';
        private const string RACE_NUMBER_OVERRIDE_FIELD_NAME = 'sltk-race-number-override';
        private const string STEAM_ID_FIELD_NAME = 'sltk-steam-id';
        private const string WORDPRESS_NONCE_PREFIX = 'update-user_';
        private const string XBOX_ID_FIELD_NAME = 'sltk-xbox-id';

        public static function handleErrors(): void {
            if (empty($_GET[Constants::WORDPRESS_UPDATED_KEY])) {
                return;
            }

            $errorMessages = get_transient(self::ERRORS_TRANSIENT_KEY) ?? [];
            if (!is_array($errorMessages) || empty($errorMessages)) {
                return;
            }

            foreach ($errorMessages as $errorMessage) {
                HtmlTagProvider::theErrorMessage($errorMessage);
            }

            delete_transient(self::ERRORS_TRANSIENT_KEY);
        }

        public static function init(): void {
            add_action('show_user_profile', [self::class, 'renderUserProfileExtension']);
            add_action('edit_user_profile', [self::class, 'renderUserProfileExtension']);
            add_action('personal_options_update', [self::class, 'saveChanges']);
            add_action('edit_user_profile_update', [self::class, 'saveChanges']);
            add_action('load-user-edit.php', [self::class, 'handleErrors']);
            add_action('load-profile.php', [self::class, 'handleErrors']);
        }

        public static function renderUserProfileExtension(WP_User $user): void {
            $userData = get_user_meta($user->ID);
            $currentRaceNumber = (int)($userData[UserMetaKeys::RACE_NUMBER][0] ?? 0);
            $currentCountry = (int)($userData[UserMetaKeys::COUNTRY_ID][0] ?? 0);

            ?>
            <section class='sltk-user-profile-extension'>
                <header>
                    <img src='<?= SLTK_PLUGIN_ROOT_URL . '/assets/images/logo-small.png' ?>'
                         alt='Sim League Toolkit logo'/>
                    <h3><?= esc_html__('Sim League Toolkit', 'sim-league-toolkit') ?></h3>
                </header>
                <p>
                    <?= esc_html__('This extension to the user profile is provided by Sim League Toolkit to capture settings that are needed for the plugin to provide full functionality. ', 'sim-league-toolkit') ?>
                    <?= esc_html__('These settings are optional, however if your league runs events for games that run on Steam (PC), PlayStation or XBox then the relevant ID will be needed to support automated features like result import..', 'sim-league-toolkit') ?>
                </p>
                <?php
                    if (is_admin()) {
                        ?>
                        <p><?= esc_html__('As an administrator you have the ability to override the selected race number.  If you use this feature and enter a race number that is already allocated to a member, their race number will be set to 0 (zero).', 'sim-league-toolkit') ?></p>
                        <?php
                    }
                ?>
                <table class='form-table'>
                    <tr>
                        <th scope='row'><?= esc_html__('Nationality', 'sim-league-toolkit') ?></th>
                        <td>
                            <select name='<?= self::COUNTRY_ID_FIELD_NAME ?>'
                                    id='<?= self::COUNTRY_ID_FIELD_NAME ?>'
                                    title='<?= esc_html__('Select your nationality', 'sim-league-toolkit') ?>
                                                                                                                      '>
                                <option value='0'<?= selected(0, $currentCountry, false) ?>><?= esc_html__('Please select...', 'sim-league-toolkit') ?></option>
                                <?php
                                    $countries = Country::list();
                                    foreach ($countries as $country) {
                                        ?>
                                        <option value='<?= $country->id ?>' <?= selected($country->id, $currentCountry, false) ?>><?= $country->name ?></option>
                                        <?php
                                    }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope='row'><?= esc_html__('Steam ID', 'sim-league-toolkit') ?></th>
                        <td>
                            <input type='text' name='<?= self::STEAM_ID_FIELD_NAME ?>'
                                   id='<?= self::STEAM_ID_FIELD_NAME ?>'
                                   value='<?= $userData[UserMetaKeys::STEAM_ID][0] ?? '' ?>'/>
                        </td>
                    </tr>
                    <tr>
                        <th scope='row'><?= esc_html__('PlayStation ID', 'sim-league-toolkit') ?></th>
                        <td>
                            <input type='text' name='<?= self::PLAYSTATION_ID_FIELD_NAME ?>'
                                   id='<?= self::PLAYSTATION_ID_FIELD_NAME ?>'
                                   value='<?= $userData[UserMetaKeys::PLAYSTATION_ID][0] ?? '' ?>'/>
                        </td>
                    </tr>
                    <tr>
                        <th scope='row'><?= esc_html__('XBox ID', 'sim-league-toolkit') ?></th>
                        <td>
                            <input type='text' name='<?= self::XBOX_ID_FIELD_NAME ?>'
                                   id='<?= self::XBOX_ID_FIELD_NAME ?>'
                                   value='<?= $userData[UserMetaKeys::XBOX_ID][0] ?? '' ?>'/>
                        </td>
                    </tr>
                    <tr>
                        <th scope='row'><?= esc_html__('Race Number', 'sim-league-toolkit') ?></th>
                        <td>
                            <span><?= $currentRaceNumber ?></span>
                        </td>
                        <th scope='row'>Change To</th>
                        <td>
                            <select name='<?= self::RACE_NUMBER_FIELD_NAME ?>'
                                    id='<?= self::RACE_NUMBER_FIELD_NAME ?>'
                                    title='<?= esc_html__('Available race numbers', 'sim-league-toolkit') ?>
                                                                                                                      '>
                                <option value='0'><?= esc_html__('Select an available race number...', 'sim-league-toolkit') ?></option>
                                <?php
                                    $countries = RaceNumber::listAvailable();
                                    foreach ($countries as $raceNumber) {
                                        ?>
                                        <option value='<?= $raceNumber ?>' <?= selected($raceNumber, $currentRaceNumber, false) ?>><?= $raceNumber ?></option>
                                        <?php
                                    }
                                ?>
                            </select>
                        </td>
                        <?php
                            if (is_admin()) {
                                ?>
                                <th scope='row'><?= esc_html__('Admin Race Number Override', 'sim-league-toolkit') ?></th>
                                <td>
                                    <input type='text' name='<?= self::RACE_NUMBER_OVERRIDE_FIELD_NAME ?>'
                                           id='<?= self::RACE_NUMBER_OVERRIDE_FIELD_NAME ?>'
                                           title='<?= esc_html__('WARNING: If the number allocated here is already in use the existing allocation will be set to 0.', 'sim-league-toolkit') ?>'
                                           min='<?= RaceNumber::MINIMUM ?>' max='<?= RaceNumber::MAXIMUM ?>'/>
                                </td>
                                <?php
                            }
                        ?>
                    </tr>
                </table>

            </section>
            <?php
        }

        public static function saveChanges(int $userId): void {
            delete_transient(self::ERRORS_TRANSIENT_KEY);

            if (empty($_POST[Constants::WORDPRESS_NONCE_NAME]) || !wp_verify_nonce($_POST[Constants::WORDPRESS_NONCE_NAME], self::WORDPRESS_NONCE_PREFIX . $userId)) {
                return;
            }

            if (!current_user_can(Constants::EDIT_USER_PERMISSION, $userId)) {
                return;
            }

            $userMeta = get_user_meta($userId);
            $countryId = (int)$_POST[self::COUNTRY_ID_FIELD_NAME] ?? 0;
            $steamId = $_POST[self::STEAM_ID_FIELD_NAME] ?? '';
            $playStationId = $_POST[self::PLAYSTATION_ID_FIELD_NAME] ?? '';
            $xboxId = $_POST[self::XBOX_ID_FIELD_NAME] ?? '';
            $raceNumber = (int)($_POST[self::RACE_NUMBER_FIELD_NAME] ?? 0);
            $raceNumberOverride = $_POST[self::RACE_NUMBER_OVERRIDE_FIELD_NAME];

            update_user_meta($userId, UserMetaKeys::COUNTRY_ID, $countryId, $userMeta[UserMetaKeys::COUNTRY_ID][0]);
            update_user_meta($userId, UserMetaKeys::STEAM_ID, $steamId, $userMeta[UserMetaKeys::STEAM_ID][0]);
            update_user_meta($userId, UserMetaKeys::PLAYSTATION_ID, $playStationId, $userMeta[UserMetaKeys::PLAYSTATION_ID][0]);
            update_user_meta($userId, UserMetaKeys::XBOX_ID, $xboxId, $userMeta[UserMetaKeys::XBOX_ID][0]);
            if ($raceNumber !== 0) {
                update_user_meta($userId, UserMetaKeys::RACE_NUMBER, (int)$raceNumber, (int)$userMeta[UserMetaKeys::RACE_NUMBER][0]);
            }

            if (!empty($raceNumberOverride)) {
                RaceNumber::reset($raceNumberOverride);
                update_user_meta($userId, UserMetaKeys::RACE_NUMBER, (int)$raceNumberOverride, (int)$userMeta[UserMetaKeys::RACE_NUMBER][0]);
            }

            $errors = [];

            if (!empty($steamId) && (!is_numeric($steamId) || mb_strlen($steamId) !== 17)) {
                $errors[] = esc_html__('Steam ID is not valid, it must be a 17 digit number without any prefix or suffix.', 'sim-league-toolkit');
            }

            if (!empty($playStationId) && (!is_numeric($playStationId) || mb_strlen($steamId) < 18) || mb_strlen($playStationId) > 19) {
                $errors[] = esc_html__('PlayStation ID is not valid, it must be an 18 or 19 digit number without any prefix or suffix.', 'sim-league-toolkit');
            }

            if (count($errors)) {
                set_transient(self::ERRORS_TRANSIENT_KEY, $errors, 15);
            }
        }

    }