<?php

  namespace SLTK\Pages\Server\Settings;

  use SLTK\Core\HtmlTagProvider;
  use SLTK\Core\HtmlTagProviderInputConfig;

  class AccServerSettingsProvider extends ServerSettingsProvider {
    private const string ADMIN_PASSWORD_KEY = 'acc-admin-password';
    private const string ALLOW_AUTO_DQ_KEY = 'acc-allow-auto-dq';
    private const string CENTRAL_ENTRY_LIST_PATH_KEY = 'acc-entry-list-path';
    private const string DUMP_ENTRY_LIST_KEY = 'acc-dump-entry-list';
    private const string DUMP_LEADER_BOARDS_KEY = 'acc-dump-leader-boards';
    private const string FORMATION_LAP_TYPE_KEY = 'acc-formation-lap-type';
    private const string IGNORE_PREMATURE_DISCONNECTS_KEY = 'acc-ignore-premature-disconnects';
    private const string IS_RACE_LOCKED_KEY = 'acc-is-race-locked';
    private const string LAN_DISCOVERY_KEY = 'acc-lan-discovery';
    private const string MAX_CAR_SLOTS_KEY = 'acc-max-car-slots';
    private const string MAX_CONNECTIONS_KEY = 'acc-max-connections';
    private const string PASSWORD_KEY = 'acc-password';
    private const string PUBLIC_IP_KEY = 'acc-public-ip';
    private const string RACE_CRAFT_REQUIREMENT_KEY = 'acc-race-craft-requirement';
    private const string RANDOMIZE_TRACK_WHEN_EMPTY_KEY = 'acc-randomize-track-when-empty';
    private const string REGISTER_TO_LOBBY_KEY = 'acc-register-to-lobby';
    private const string SAFETY_RATING_REQUIREMENT_KEY = 'acc-safety-rating-requirement';
    private const string SERVER_NAME_KEY = 'acc-server-name';
    private const string SHORT_FORMATION_LAP_KEY = 'acc-short-formation';
    private const string SPECTATOR_PASSWORD_KEY = 'acc-spectator-password';
    private const string TCP_PORT_KEY = 'acc-tcp-port';
    private const string TRACK_MEDALS_REQUIREMENT_KEY = 'acc-track-medals-requirement';
    private const string UDP_PORT_KEY = 'acc-udp-port';

    public function render(): void {
      ?>
        <p><?= esc_html__('ACC Servers are configured and controlled by several JSON files as described in the ', 'sim-league-toolkit') ?>
            <a
                    href='https://www.acc-wiki.info/wiki/Server_Configuration' target='_blank'
                    title='<?= esc_html__('ACC Server Admin Handbook Wiki', 'sim-league-toolkit') ?>'><?= esc_html__('ACC Server Admin Handbook', 'sim-league-toolkit') ?>
                .</a>
          <?= esc_html__('Sim League Toolkit can generate these files for you when you are ready to start an event.  This section covers two of these files.', 'sim-league-toolkit') ?>
        </p>
        <p><?= esc_html__('Some settings will be disabled if the server is hosted as they will be set by the hosting provider.  Others can be overridden by Championship or Event settings.', 'sim-league-toolkit') ?></p>

        <fieldset>
            <legend>configuration.json <?= esc_html__('Settings', 'sim-league-toolkit') ?></legend>
            <table class='form-table'>
              <?php
                $tcpPortConfig = new HtmlTagProviderInputConfig(self::TCP_PORT_KEY,
                  esc_html__('TCP Port', 'sim-league-toolkit'),
                  $this->server->getSettingValue(self::TCP_PORT_KEY) ?? 9201,
                  $this->getError(self::TCP_PORT_KEY),
                  type: 'number');
                $tcpPortConfig->disabled = $this->server->isHostedServer;
                $tcpPortConfig->required = true;
                HtmlTagProvider::theAdminInputField($tcpPortConfig);

                $udpPortConfig = new HtmlTagProviderInputConfig(self::UDP_PORT_KEY,
                  esc_html__('UDP Port', 'sim-league-toolkit'),
                  $this->server->getSettingValue(self::UDP_PORT_KEY) ?? 9201,
                  $this->getError(self::UDP_PORT_KEY),
                  type: 'number');
                $udpPortConfig->disabled = $this->server->isHostedServer;
                $udpPortConfig->required = true;
                HtmlTagProvider::theAdminInputField($udpPortConfig);

                HtmlTagProvider::theAdminCheckboxInput(esc_html__('Register to Lobby', 'sim-league-toolkit'),
                  self::REGISTER_TO_LOBBY_KEY,
                  $this->server->getSettingValue(self::REGISTER_TO_LOBBY_KEY) ?? true,
                  $this->server->isHostedServer);

                $maxConnectionsConfig = new HtmlTagProviderInputConfig(self::MAX_CONNECTIONS_KEY,
                  esc_html__('Max Connections', 'sim-league-toolkit'),
                  $this->server->getSettingValue(self::MAX_CONNECTIONS_KEY) ?? 9201,
                  $this->getError(self::MAX_CONNECTIONS_KEY),
                  type: 'number');
                $maxConnectionsConfig->disabled = $this->server->isHostedServer;
                $maxConnectionsConfig->required = true;
                HtmlTagProvider::theAdminInputField($maxConnectionsConfig);

                HtmlTagProvider::theAdminCheckboxInput(esc_html__('Lan Discovery', 'sim-league-toolkit'),
                  self::LAN_DISCOVERY_KEY,
                  $this->server->getSettingValue(self::LAN_DISCOVERY_KEY) ?? false,
                  $this->server->isHostedServer);

                $publicIpConfig = new HtmlTagProviderInputConfig(self::PUBLIC_IP_KEY,
                  esc_html__('Public IP', 'sim-league-toolkit'),
                  $this->server->getSettingValue(self::PUBLIC_IP_KEY) ?? '',
                  $this->getError(self::PUBLIC_IP_KEY));
                $publicIpConfig->disabled = $this->server->isHostedServer;
                HtmlTagProvider::theAdminInputField($publicIpConfig);
              ?>
            </table>
        </fieldset>

        <fieldset>
            <legend>settings.json <?= esc_html__('Settings', 'sim-league-toolkit') ?></legend>
            <table class='form-table'>
                <?php

                  $serverDisplayNameConfig = new HtmlTagProviderInputConfig(self::SERVER_NAME_KEY,
                    esc_html__('Lobby Display Name', 'sim-league-toolkit'),
                    $this->server->getSettingValue(self::SERVER_NAME_KEY) ?? null,
                    $this->getError(self::SERVER_NAME_KEY),
                    esc_html__('Override default display name for server', 'sim-league-toolkit')
                  );
                  $serverDisplayNameConfig->size = 100;

                  HtmlTagProvider::theAdminInputField($serverDisplayNameConfig);

                  $passwordConfig = new HtmlTagProviderInputConfig(self::PASSWORD_KEY,
                    esc_html__('Password', 'sim-league-toolkit'),
                    $this->server->getSettingValue(self::PASSWORD_KEY) ?? '',
                    $this->getError(self::PASSWORD_KEY)
                  );

                  HtmlTagProvider::theAdminInputField($passwordConfig);

                  $adminPasswordConfig = new HtmlTagProviderInputConfig(self::ADMIN_PASSWORD_KEY,
                    esc_html__('Admin Password', 'sim-league-toolkit'),
                    $this->server->getSettingValue(self::ADMIN_PASSWORD_KEY) ?? '',
                    $this->getError(self::ADMIN_PASSWORD_KEY)
                  );

                  HtmlTagProvider::theAdminInputField($adminPasswordConfig);

                  $spectatorPasswordConfig = new HtmlTagProviderInputConfig(self::SPECTATOR_PASSWORD_KEY,
                    esc_html__('Spectator Password', 'sim-league-toolkit'),
                    $this->server->getSettingValue(self::SPECTATOR_PASSWORD_KEY) ?? '',
                    $this->getError(self::SPECTATOR_PASSWORD_KEY)
                  );

                  HtmlTagProvider::theAdminInputField($spectatorPasswordConfig);

                  $trackMedalsRequirementConfig = new HtmlTagProviderInputConfig(self::TRACK_MEDALS_REQUIREMENT_KEY,
                    esc_html__('Track Medals Requirement', 'sim-league-toolkit'),
                    $this->server->getSettingValue(self::TRACK_MEDALS_REQUIREMENT_KEY) ?? 0,
                    $this->getError(self::TRACK_MEDALS_REQUIREMENT_KEY),
                    type: 'number'
                  );

                  $trackMedalsRequirementConfig->min = 0;
                  $trackMedalsRequirementConfig->max = 3;
                  $trackMedalsRequirementConfig->step = 1;
                  $trackMedalsRequirementConfig->size = 10;

                  HtmlTagProvider::theAdminInputField($trackMedalsRequirementConfig);

                  $safetyRatingRequirementConfig = new HtmlTagProviderInputConfig(self::SAFETY_RATING_REQUIREMENT_KEY,
                    esc_html__('Safety Rating Requirement', 'sim-league-toolkit'),
                    $this->server->getSettingValue(self::SAFETY_RATING_REQUIREMENT_KEY) ?? -1,
                    $this->getError(self::SAFETY_RATING_REQUIREMENT_KEY),
                    type: 'number'
                  );

                  $safetyRatingRequirementConfig->min = -1;
                  $safetyRatingRequirementConfig->max = 99;
                  $safetyRatingRequirementConfig->step = 1;
                  $safetyRatingRequirementConfig->size = 10;

                  HtmlTagProvider::theAdminInputField($safetyRatingRequirementConfig);

                  $raceCraftRequirementConfig = new HtmlTagProviderInputConfig(self::RACE_CRAFT_REQUIREMENT_KEY,
                    esc_html__('Race Craft Requirement', 'sim-league-toolkit'),
                    $this->server->getSettingValue(self::RACE_CRAFT_REQUIREMENT_KEY) ?? -1,
                    $this->getError(self::RACE_CRAFT_REQUIREMENT_KEY),
                    type: 'number'
                  );

                  $raceCraftRequirementConfig->min = -1;
                  $raceCraftRequirementConfig->max = 99;
                  $raceCraftRequirementConfig->step = 1;
                  $raceCraftRequirementConfig->size = 10;

                  HtmlTagProvider::theAdminInputField($raceCraftRequirementConfig);

                  $maxCarSlotsRequirementConfig = new HtmlTagProviderInputConfig(self::MAX_CAR_SLOTS_KEY,
                    esc_html__('Max Car Slots', 'sim-league-toolkit'),
                    $this->server->getSettingValue(self::MAX_CAR_SLOTS_KEY) ?? 30,
                    $this->getError(self::MAX_CAR_SLOTS_KEY),
                    type: 'number'
                  );

                  $maxCarSlotsRequirementConfig->min = 1;
                  $maxCarSlotsRequirementConfig->max = 80;
                  $maxCarSlotsRequirementConfig->step = 1;
                  $maxCarSlotsRequirementConfig->size = 10;

                  HtmlTagProvider::theAdminInputField($maxCarSlotsRequirementConfig);

                  HtmlTagProvider::theAdminCheckboxInput(esc_html__('Dump Leader Boards', 'sim-league-toolkit'),
                    self::DUMP_LEADER_BOARDS_KEY,
                    $this->server->getSettingValue(self::DUMP_LEADER_BOARDS_KEY) ?? true);

                  HtmlTagProvider::theAdminCheckboxInput(esc_html__('Is Race Locked', 'sim-league-toolkit'),
                    self::IS_RACE_LOCKED_KEY,
                    $this->server->getSettingValue(self::IS_RACE_LOCKED_KEY) ?? false);

                  HtmlTagProvider::theAdminCheckboxInput(esc_html__('Randomize Track When Empty', 'sim-league-toolkit'),
                    self::RANDOMIZE_TRACK_WHEN_EMPTY_KEY,
                    $this->server->getSettingValue(self::RANDOMIZE_TRACK_WHEN_EMPTY_KEY) ?? true);

                  $centralEntryListPathConfig = new HtmlTagProviderInputConfig(self::CENTRAL_ENTRY_LIST_PATH_KEY,
                    esc_html__('Central Entry List Path', 'sim-league-toolkit'),
                    $this->server->getSettingValue(self::CENTRAL_ENTRY_LIST_PATH_KEY) ?? '',
                    $this->getError(self::CENTRAL_ENTRY_LIST_PATH_KEY));
                  $centralEntryListPathConfig->size = 100;
                  HtmlTagProvider::theAdminInputField($centralEntryListPathConfig);

                  HtmlTagProvider::theAdminCheckboxInput(esc_html__('Allow Auto DQ', 'sim-league-toolkit'),
                    self::ALLOW_AUTO_DQ_KEY,
                    $this->server->getSettingValue(self::ALLOW_AUTO_DQ_KEY) ?? false);

                  HtmlTagProvider::theAdminCheckboxInput(esc_html__('Short Formation Lap', 'sim-league-toolkit'),
                    self::SHORT_FORMATION_LAP_KEY,
                    $this->server->getSettingValue(self::SHORT_FORMATION_LAP_KEY) ?? true);

                  HtmlTagProvider::theAdminCheckboxInput(esc_html__('Dump Entry List', 'sim-league-toolkit'),
                    self::DUMP_ENTRY_LIST_KEY,
                    $this->server->getSettingValue(self::DUMP_ENTRY_LIST_KEY) ?? true);
                ?>
                <tr>
                    <th scope='row'>
                        <label for='<?= self::FORMATION_LAP_TYPE_KEY ?>'><?= esc_html__('Formation Lap Type', 'sim-league-toolkit') ?></label>
                    </th>
                    <td>
                      <?php
                        $rawValue = $this->server->getSettingValue(self::FORMATION_LAP_TYPE_KEY);
                        $settingValue = $rawValue ?? '3';
                      ?>
                        <select id='<?= self::FORMATION_LAP_TYPE_KEY ?>'
                                name='<?= self::FORMATION_LAP_TYPE_KEY ?>'>
                            <option value='3' <?= selected($settingValue, '3', false) ?>><?= esc_html__('Formation Lap with Position Control and UI', 'sim-league-toolkit') ?></option>
                            <option value='0' <?= selected($settingValue, '0', false) ?>><?= esc_html__('Old Limiter Lap', 'sim-league-toolkit') ?></option>
                            <option value='1' <?= selected($settingValue, '1', false) ?>><?= esc_html__('Free', 'sim-league-toolkit') ?></option>
                        </select>
                    </td>
                </tr>
                <?php
                  HtmlTagProvider::theAdminCheckboxInput(esc_html__('Ignore Premature Disconnects', 'sim-league-toolkit'),
                    self::IGNORE_PREMATURE_DISCONNECTS_KEY,
                    $this->server->getSettingValue(self::IGNORE_PREMATURE_DISCONNECTS_KEY) ?? false);
                ?>
            </table>
        </fieldset>
      <?php
    }

    public function save(): void {
      $this->server->applySetting(self::ADMIN_PASSWORD_KEY, $this->getSanitisedFieldFromPost(self::ADMIN_PASSWORD_KEY, ''));
      $this->server->applySetting(self::ALLOW_AUTO_DQ_KEY, $this->postContainsField(self::ALLOW_AUTO_DQ_KEY));
      $this->server->applySetting(self::CENTRAL_ENTRY_LIST_PATH_KEY, $this->getSanitisedFieldFromPost(self::CENTRAL_ENTRY_LIST_PATH_KEY, ''));
      $this->server->applySetting(self::DUMP_ENTRY_LIST_KEY, $this->postContainsField(self::DUMP_ENTRY_LIST_KEY));
      $this->server->applySetting(self::DUMP_LEADER_BOARDS_KEY, $this->postContainsField(self::DUMP_LEADER_BOARDS_KEY));
      $this->server->applySetting(self::FORMATION_LAP_TYPE_KEY, $this->getSanitisedFieldFromPost(self::FORMATION_LAP_TYPE_KEY, 3));
      $this->server->applySetting(self::IGNORE_PREMATURE_DISCONNECTS_KEY, $this->postContainsField(self::IGNORE_PREMATURE_DISCONNECTS_KEY));
      $this->server->applySetting(self::IS_RACE_LOCKED_KEY, $this->postContainsField(self::IS_RACE_LOCKED_KEY));
      $this->server->applySetting(self::LAN_DISCOVERY_KEY, $this->postContainsField(self::LAN_DISCOVERY_KEY));
      $this->server->applySetting(self::MAX_CAR_SLOTS_KEY, $this->getSanitisedFieldFromPost(self::MAX_CAR_SLOTS_KEY, 30));
      $this->server->applySetting(self::MAX_CONNECTIONS_KEY, $this->getSanitisedFieldFromPost(self::MAX_CONNECTIONS_KEY, 30));
      $this->server->applySetting(self::PASSWORD_KEY, $this->getSanitisedFieldFromPost(self::PASSWORD_KEY, ''));
      $this->server->applySetting(self::PASSWORD_KEY, $this->getSanitisedFieldFromPost(self::PASSWORD_KEY, ''));
      $this->server->applySetting(self::PUBLIC_IP_KEY, $this->getSanitisedFieldFromPost(self::PUBLIC_IP_KEY, ''));
      $this->server->applySetting(self::RACE_CRAFT_REQUIREMENT_KEY, $this->getSanitisedFieldFromPost(self::RACE_CRAFT_REQUIREMENT_KEY, -1));
      $this->server->applySetting(self::RANDOMIZE_TRACK_WHEN_EMPTY_KEY, $this->postContainsField(self::RANDOMIZE_TRACK_WHEN_EMPTY_KEY));
      $this->server->applySetting(self::REGISTER_TO_LOBBY_KEY, $this->postContainsField(self::REGISTER_TO_LOBBY_KEY));
      $this->server->applySetting(self::SAFETY_RATING_REQUIREMENT_KEY, $this->getSanitisedFieldFromPost(self::SAFETY_RATING_REQUIREMENT_KEY, -1));
      $this->server->applySetting(self::SERVER_NAME_KEY, $this->getSanitisedFieldFromPost(self::SERVER_NAME_KEY, ''));
      $this->server->applySetting(self::SHORT_FORMATION_LAP_KEY, $this->postContainsField(self::SHORT_FORMATION_LAP_KEY));
      $this->server->applySetting(self::SPECTATOR_PASSWORD_KEY, $this->getSanitisedFieldFromPost(self::SPECTATOR_PASSWORD_KEY, ''));
      $this->server->applySetting(self::TCP_PORT_KEY, $this->getSanitisedFieldFromPost(self::TCP_PORT_KEY, 9201));
      $this->server->applySetting(self::TRACK_MEDALS_REQUIREMENT_KEY, $this->getSanitisedFieldFromPost(self::TRACK_MEDALS_REQUIREMENT_KEY, 0));
      $this->server->applySetting(self::UDP_PORT_KEY, $this->getSanitisedFieldFromPost(self::UDP_PORT_KEY, 9201));

      if (!$this->validate()) {
        return;
      }

      $this->server->saveSettings();
    }

    public function validate(): bool {
      $isValid = true;
      $this->errors = [];

      $password = $this->server->getSettingValue(self::PASSWORD_KEY);
      $spectatorPassword = $this->server->getSettingValue(self::SPECTATOR_PASSWORD_KEY);
      if (!empty($password) && !empty($spectatorPassword) && $password === $spectatorPassword) {
        $isValid = false;
        $this->errors[self::SPECTATOR_PASSWORD_KEY] = esc_html__('Spectator Password cannot be the same as Password.', 'sim-league-toolkit');
      }

      $maxCarSlots = (int)$this->server->getSettingValue(self::MAX_CAR_SLOTS_KEY);
      $maxConnections = (int)$this->server->getSettingValue(self::MAX_CONNECTIONS_KEY);

      if ($maxCarSlots > $maxConnections) {
        $isValid = false;
        $this->errors[self::MAX_CAR_SLOTS_KEY] = esc_html__('Max Car Slots cannot be more than Max Connections.', 'sim-league-toolkit');
      }

      $publicIp = $this->server->getSettingValue(self::PUBLIC_IP_KEY) ?? '';
      if (!empty($publicIp) && !filter_var($publicIp, FILTER_VALIDATE_IP)) {
        $isValid = false;
        $this->errors[self::PUBLIC_IP_KEY] = esc_html__('The specified Public IP is not a valid IP address.', 'sim-league-toolkit');
      }

      return $isValid;
    }
  }