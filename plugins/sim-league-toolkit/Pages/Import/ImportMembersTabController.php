<?php

namespace SLTK\Pages\Import;

use SLTK\Core\HtmlTagProvider;
use SLTK\Core\UserMetaKeys;
use SLTK\Domain\RaceNumber;
use SLTK\Pages\ControllerBase;

class ImportMembersTabController extends ControllerBase {
    private const int ACTION_INDEX = 9;
    private const string EMAIL_HEADER = 'Email';
    private const int EMAIL_INDEX = 0;
    private const string FIRSTNAME_HEADER = 'FirstName';
    private const int FIRSTNAME_INDEX = 2;
    private const string IMPORT_FILE_FIELD_NAME = 'sltk-import-file';
    private const string LASTNAME_HEADER = 'LastName';
    private const int LASTNAME_INDEX = 3;
    private const string PLAYSTATION_ID_HEADER = 'PlayStationID';
    private const int PLAYSTATION_ID_INDEX = 5;
    private const string RACE_NUMBER_HEADER = 'RaceNumber';
    private const int RACE_NUMBER_INDEX = 7;
    private const int ROW_ERRORS_INDEX = 8;
    private const string STEAM_ID_HEADER = 'SteamID';
    private const int STEAM_ID_INDEX = 4;
    private const string USERNAME_HEADER = 'Username';
    private const int USERNAME_INDEX = 1;
    private const string XBOX_ID_HEADER = 'XBoxID';
    private const int XBOX_ID_INDEX = 6;
    private array $processedRows = [];

    public function theFileSelector(): void { ?>
        <input type='file' name='<?= self::IMPORT_FILE_FIELD_NAME ?>' accept='text/csv'
               title='<?= esc_html__('Select the file to be imported', 'sim-league-toolkit') ?>'/>
        <?php
    }

    public function theHiddenFields(): void {
        $this->theNonce();
    }

    public function theResults(): void {
        if (count($this->processedRows) < 1) {
            return;
        }

        ?>
        <h3>Import Result</h3>
        <table class='admin-table'>
            <thead>
            <tr>
                <th>Email</th>
                <th>Username</th>
                <th>FirstName</th>
                <th>LastName</th>
                <th>SteamID</th>
                <th>PlayStationID</th>
                <th>XBoxID</th>
                <th>RaceNumber</th>
                <th>Errors</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($this->processedRows as $row) {
                ?>
                <tr>
                    <td><?= $row[self::EMAIL_INDEX] ?></td>
                    <td><?= $row[self::USERNAME_INDEX] ?></td>
                    <td><?= $row[self::FIRSTNAME_INDEX] ?></td>
                    <td><?= $row[self::LASTNAME_INDEX] ?></td>
                    <td><?= $row[self::STEAM_ID_INDEX] ?></td>
                    <td><?= $row[self::PLAYSTATION_ID_INDEX] ?></td>
                    <td><?= $row[self::XBOX_ID_INDEX] ?></td>
                    <td><?= $row[self::RACE_NUMBER_INDEX] ?></td>
                    <td><?php
                        $rowErrors = $row[self::ROW_ERRORS_INDEX] ?? [];
                        echo count($rowErrors) > 0 ? '<span class="sltk-error-text">' . implode('<br />',
                                $rowErrors) . '</span>' : '';
                        ?></td>
                    <td><?= $row[self::ACTION_INDEX] ?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
        <?php
    }

    public function theTemplateLink(): void {
        ?>
        <a class='button button-secondary' href='<?= SLTK_PLUGIN_ROOT_URL . '/assets/migrate.csv' ?>'
           target='_blank'
           title='<?= esc_html__('Download a template for the CSV file',
               'sim-league-toolkit') ?>'><?= esc_html__('Download Template',
                'sim-league-toolkit') ?></a>
        <?php
    }

    protected function handleGet(): void {
    }

    protected function handlePost(): void {
        if (!$this->validateNonce()) {
            return;
        }

        $this->processUpload();
    }

    private function processUpload(): void {
        $csvFile = $this->getFile(self::IMPORT_FILE_FIELD_NAME);
        if (empty($csvFile['tmp_name'])) {
            HtmlTagProvider::theErrorMessage(esc_html__('A CSV file to import is required.', 'sim-league-toolkit'));

            return;
        }

        $this->importFile($csvFile['tmp_name']);
    }

    private function importFile($filePath): void {
        $csvAsArray = array_map('str_getcsv', file($filePath));

        $headers = $csvAsArray[0];
        if (!$this->validateHeaders($headers)) {
            return;
        }
        $totalRows = count($csvAsArray);
        $this->processedRows = [];
        for ($i = 1; $i < $totalRows; $i++) {
            $row = $csvAsArray[$i];

            if ($this->validateImportRow($row)) {
                $this->processRow($row);
            } else {
                $row[self::ACTION_INDEX] = esc_html__('None', 'sim-league-toolkit');
            }
            $this->processedRows[] = $row;
        }
    }

    private function validateHeaders(array $headers): bool {
        if (count($headers) < 8
            || $headers[self::EMAIL_INDEX] !== self::EMAIL_HEADER
            || $headers[self::USERNAME_INDEX] !== self::USERNAME_HEADER
            || $headers[self::FIRSTNAME_INDEX] !== self::FIRSTNAME_HEADER
            || $headers[self::LASTNAME_INDEX] !== self::LASTNAME_HEADER
            || $headers[self::STEAM_ID_INDEX] !== self::STEAM_ID_HEADER
            || $headers[self::PLAYSTATION_ID_INDEX] !== self::PLAYSTATION_ID_HEADER
            || $headers[self::XBOX_ID_INDEX] !== self::XBOX_ID_HEADER
            || $headers[self::RACE_NUMBER_INDEX] !== self::RACE_NUMBER_HEADER
        ) {
            HtmlTagProvider::theErrorMessage(esc_html__('The file is not in the correct format, please check the format of the file matches the specification shown.',
                'sim-league-toolkit'));

            return false;
        }

        return true;
    }

    private function validateImportRow(array &$row): bool {
        $rowErrors = [];
        $hasValidEmail = true;
        $emailAddress = $row[self::EMAIL_INDEX];
        $userName = $row[self::USERNAME_INDEX];
        $firstName = $row[self::FIRSTNAME_INDEX];
        $lastName = $row[self::LASTNAME_INDEX];
        $steamId = $row[self::STEAM_ID_INDEX];
        $playStationId = $row[self::PLAYSTATION_ID_INDEX];
        $xboxId = $row[self::XBOX_ID_INDEX];
        $raceNumber = $row[self::RACE_NUMBER_INDEX];

        if (empty($emailAddress)) {
            $rowErrors[] = esc_html__('Email address is missing.');
            $hasValidEmail = false;
        } else if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
            $rowErrors[] = esc_html__('Invalid email address.');
            $hasValidEmail = false;
        }

        if ($hasValidEmail && !email_exists($emailAddress) && empty($userName)) {
            $rowErrors[] = esc_html__('Missing Username for new user.');
        }

        if (!$this->validateNotNumeric($firstName)) {
            $rowErrors[] = esc_html__('FirstName cannot be a number.');
        }

        if (!$this->validateNotNumeric($lastName)) {
            $rowErrors[] = esc_html__('LastName cannot be a number.');
        }

        if (!$this->validateSteamId($steamId)) {
            $rowErrors[] = esc_html__('SteamID must be a 17 digit number without a prefix or suffix.');
        }

        if (!$this->validatePlayStationId($playStationId)) {
            $rowErrors[] = esc_html__('PlayStationId must be an 18 or 19 digit number without a prefix or suffix.');
        }

        if (!$this->validateXBoxId($xboxId)) {
            $rowErrors[] = esc_html__('XBoxID (gamertag) must be 12 characters or less and cannot start with a number.');
        }

        if (!$this->validateRaceNumber($raceNumber)) {
            $rowErrors[] = esc_html__('RaceNumber must be a number between 1 and 998 inclusive.');
        }

        $row[self::ROW_ERRORS_INDEX] = $rowErrors;

        return count($rowErrors) === 0;
    }

    private function validateNotNumeric(mixed $firstName): bool {
        return empty($firstName) || !is_numeric($firstName);
    }

    private function validateSteamId(mixed $steamId): bool {
        return empty($steamId) || (strlen($steamId) === 17 && is_numeric($steamId));
    }

    private function validatePlayStationId(mixed $playStationId): bool {
        return empty($playStationId) || (strlen($playStationId) >= 18 && strlen($playStationId) <= 19 && is_numeric($playStationId));
    }

    private function validateXBoxId(mixed $xBoxId): bool {
        return empty($xBoxId) || (strlen($xBoxId) <= 12 || !is_numeric(substr($xBoxId, 0, 1)));
    }

    private function validateRaceNumber(mixed $raceNumber): bool {
        return empty($raceNumber) || (is_numeric($raceNumber) && RaceNumber::isValid((int)$raceNumber));
    }

    private function processRow(array &$row): void {
        if (email_exists($row[self::EMAIL_INDEX])) {
            $this->processExistingUser($row);
        } else {
            $this->processNewUser($row);
        }
    }

    private function processExistingUser(array &$row): void {

        $emailAddress = $row[self::EMAIL_INDEX];
        $firstName = $row[self::FIRSTNAME_INDEX];
        $lastName = $row[self::LASTNAME_INDEX];
        $steamId = $row[self::STEAM_ID_INDEX];
        $playStationId = $row[self::PLAYSTATION_ID_INDEX];
        $xboxId = $row[self::XBOX_ID_INDEX];
        $raceNumber = $row[self::RACE_NUMBER_INDEX];

        $user = get_user_by('email', $emailAddress);

        $userUpdate = [
            'ID' => $user->ID,
            'meta_input' => []
        ];

        $requiresUpdate = false;

        if (!empty($firstName) && $user->first_name != $firstName) {
            $userUpdate['first_name'] = $firstName;
            $requiresUpdate = true;
        }

        if (!empty($lastName) && $user->last_name != $lastName) {
            $userUpdate['last_name'] = $lastName;
            $requiresUpdate = true;
        }

        if (!empty($steamId)) {
            $userUpdate['meta_input'][UserMetaKeys::STEAM_ID] = $steamId;
            $requiresUpdate = true;
        }

        if (!empty($playStationId)) {
            $userUpdate['meta_input'][UserMetaKeys::PLAYSTATION_ID] = $playStationId;
            $requiresUpdate = true;
        }

        if (!empty($xboxId)) {
            $userUpdate['meta_input'][UserMetaKeys::XBOX_ID] = $xboxId;
            $requiresUpdate = true;
        }

        if (!empty($raceNumber)) {
            $userUpdate['meta_input'][UserMetaKeys::RACE_NUMBER] = (int)$raceNumber;
            $requiresUpdate = true;
            RaceNumber::reset((int)$raceNumber);
        }

        $row[self::ROW_ERRORS_INDEX] = [];
        if ($requiresUpdate) {
            $updateResult = wp_update_user($userUpdate);
            if (is_wp_error($updateResult)) {
                $row[self::ROW_ERRORS_INDEX] = $updateResult->get_error_messages();
                $row[self::ACTION_INDEX] = esc_html__('None', 'sim-league-toolkit');
            } else {
                $row[self::ACTION_INDEX] = esc_html__('Update User', 'sim-league-toolkit');
            }
        } else {
            $row[self::ACTION_INDEX] = esc_html__('Nothing to change', 'sim-league-toolkit');
        }
    }

    private function processNewUser(array &$row): void {

        $emailAddress = $row[self::EMAIL_INDEX];
        $username = $row[self::USERNAME_INDEX];
        $firstName = $row[self::FIRSTNAME_INDEX] ?? '';
        $lastName = $row[self::LASTNAME_INDEX] ?? '';
        $steamId = $row[self::STEAM_ID_INDEX] ?? '';
        $playStationId = $row[self::PLAYSTATION_ID_INDEX] ?? '';
        $xboxId = $row[self::XBOX_ID_INDEX] ?? '';
        $raceNumber = (int)($row[self::RACE_NUMBER_INDEX] ?? 0);

        $userdata = [
            'user_email' => $emailAddress,
            'user_login' => sanitize_user($username),
            'first_name' => $firstName,
            'last_name' => $lastName,
            'password' => wp_generate_password(),
            'show_admin_bar_front' => 'false',
            'meta_input' => [
                UserMetaKeys::STEAM_ID => $steamId,
                UserMetaKeys::PLAYSTATION_ID => $playStationId,
                UserMetaKeys::XBOX_ID => $xboxId,
                UserMetaKeys::RACE_NUMBER => $raceNumber,
            ]
        ];

        if ($raceNumber !== 0) {
            RaceNumber::reset($raceNumber);
        }

        $row[self::ROW_ERRORS_INDEX] = [];
        $insertResult = wp_insert_user($userdata);
        if (is_wp_error($insertResult)) {
            $row[self::ROW_ERRORS_INDEX] = $insertResult->get_error_messages();
            $row[self::ACTION_INDEX] = esc_html__('None', 'sim-league-toolkit');
        } else {
            $row[self::ACTION_INDEX] = esc_html__('New User', 'sim-league-toolkit');
            wp_send_new_user_notifications($insertResult, 'user');
        }
    }
}