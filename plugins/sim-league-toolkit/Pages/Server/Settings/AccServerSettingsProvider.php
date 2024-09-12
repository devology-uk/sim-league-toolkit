<?php

  namespace SLTK\Pages\Server\Settings;

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

    private array $validationErrors = [];

    /**
     * @inheritDoc
     */
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
          <tr>
            <th scope='row'>
              <label for='<?= self::TCP_PORT_KEY ?>'><?= esc_html__('TCP Port', 'sim-league-toolkit') ?></label>
            </th>
            <td>
              <?php
                $settingValue = $this->server->getSetting(self::TCP_PORT_KEY);
              ?>
              <input type='number' id='<?= self::TCP_PORT_KEY ?>'
                     name='<?= self::TCP_PORT_KEY ?>' value='<?= $settingValue ?? 9201 ?>'
                     required <?= disabled($this->server->isHostedServer) ?> />
            </td>
          </tr>
          <tr>
            <th scope='row'>
              <label for='<?= self::UDP_PORT_KEY ?>'><?= esc_html__('UDP Port', 'sim-league-toolkit') ?></label>
            </th>
            <td>
              <?php
                $settingValue = $this->server->getSetting(self::UDP_PORT_KEY);
              ?>
              <input type='number' id='<?= self::UDP_PORT_KEY ?>'
                     name='<?= self::UDP_PORT_KEY ?>' value='<?= $settingValue ?? 9201 ?>'
                     required <?= disabled($this->server->isHostedServer) ?> />
            </td>
          </tr>
          <tr>
            <th scope='row'>
              <label for='<?= self::REGISTER_TO_LOBBY_KEY ?>'><?= esc_html__('Register to Lobby', 'sim-league-toolkit') ?></label>
            </th>
            <td>
              <?php
                $settingValue = $this->server->getSetting(self::REGISTER_TO_LOBBY_KEY) ?? true;
              ?>
              <input type='checkbox' id='<?= self::REGISTER_TO_LOBBY_KEY ?>'
                     name='<?= self::REGISTER_TO_LOBBY_KEY ?>'
                <?= checked($settingValue, true, false) ?> <?= disabled($this->server->isHostedServer) ?> />
            </td>
          </tr>
          <tr>
            <th scope='row'>
              <label for='<?= self::MAX_CONNECTIONS_KEY ?>'><?= esc_html__('Max Connections', 'sim-league-toolkit') ?></label>
            </th>
            <td>
              <?php
                $settingValue = $this->server->getSetting(self::MAX_CONNECTIONS_KEY);
              ?>
              <input type='number' id='<?= self::MAX_CONNECTIONS_KEY ?>'
                     name='<?= self::MAX_CONNECTIONS_KEY ?>' value='<?= $settingValue ?? 30 ?>'
                     required <?= disabled($this->server->isHostedServer) ?>/>
            </td>
          </tr>
          <tr>
            <th scope='row'>
              <label for='<?= self::LAN_DISCOVERY_KEY ?>'><?= esc_html__('Lan Discovery', 'sim-league-toolkit') ?></label>
            </th>
            <td>
              <?php
                $settingValue = $this->server->getSetting(self::LAN_DISCOVERY_KEY) ?? false;
              ?>
              <input type='checkbox' id='<?= self::LAN_DISCOVERY_KEY ?>'
                     name='<?= self::LAN_DISCOVERY_KEY ?>'
                <?= checked($settingValue, true, false) ?> <?= disabled($this->server->isHostedServer) ?> />
            </td>
          </tr>
          <tr>
            <?php
              $settingValue = $this->server->getSetting(self::PUBLIC_IP_KEY);
              $validationError = $this->validationErrors[self::PUBLIC_IP_KEY] ?? '';
            ?>
            <th scope='row'>
              <label for='<?= self::PUBLIC_IP_KEY ?>'
                     class='<?= empty($validationError) ? '' : 'error-message' ?>'><?= esc_html__('Public IP', 'sim-league-toolkit') ?></label>
            </th>
            <td>
              <input type='text' id='<?= self::PUBLIC_IP_KEY ?>'
                     name='<?= self::PUBLIC_IP_KEY ?>'
                     value='<?= $settingValue ?? '' ?>' <?= disabled($this->server->isHostedServer) ?> />
              <?php
                if(!empty($validationError)) {
                  ?>
                  <p class='small-text error-message'>
                    <?= $validationError ?>
                  </p>
                  <?php
                }
              ?>
            </td>
          </tr>
        </table>
      </fieldset>

      <fieldset>
        <legend>settings.json <?= esc_html__('Settings', 'sim-league-toolkit') ?></legend>
        <table class='form-table'>
          <tr>
            <th scope='row'>
              <label for='<?= self::SERVER_NAME_KEY ?>'><?= esc_html__('Server Name', 'sim-league-toolkit') ?></label>
            </th>
            <td>
              <?php
                $settingValue = $this->server->getSetting(self::SERVER_NAME_KEY);
              ?>
              <input type='text' id='<?= self::SERVER_NAME_KEY ?>'
                     name='<?= self::SERVER_NAME_KEY ?>' value='<?= $settingValue ?? $this->server->name ?>' />
            </td>
          </tr>
          <tr>
            <th scope='row'>
              <label for='<?= self::PASSWORD_KEY ?>'><?= esc_html__('Password', 'sim-league-toolkit') ?></label>
            </th>
            <td>
              <?php
                $settingValue = $this->server->getSetting(self::PASSWORD_KEY);
              ?>
              <input type='text' id='<?= self::PASSWORD_KEY ?>'
                     name='<?= self::PASSWORD_KEY ?>' value='<?= $settingValue ?? '' ?>' />
            </td>
          </tr>
          <tr>
            <th scope='row'>
              <label for='<?= self::ADMIN_PASSWORD_KEY ?>'><?= esc_html__('Admin Password', 'sim-league-toolkit') ?></label>
            </th>
            <td>
              <?php
                $settingValue = $this->server->getSetting(self::ADMIN_PASSWORD_KEY);
              ?>
              <input type='text' id='<?= self::ADMIN_PASSWORD_KEY ?>'
                     name='<?= self::ADMIN_PASSWORD_KEY ?>' value='<?= $settingValue ?? '' ?>' />
            </td>
          </tr>
          <tr>
            <?php
              $settingValue = $this->server->getSetting(self::SPECTATOR_PASSWORD_KEY);
              $validationError = $this->validationErrors[self::SPECTATOR_PASSWORD_KEY] ?? '';
            ?>
            <th scope='row'>
              <label for='<?= self::SPECTATOR_PASSWORD_KEY ?>'
                     class='<?= empty($validationError) ? '' : 'error-message' ?>'><?= esc_html__('Spectator Password', 'sim-league-toolkit') ?></label>
            </th>
            <td>
              <input type='text' id='<?= self::SPECTATOR_PASSWORD_KEY ?>' name='<?= self::SPECTATOR_PASSWORD_KEY ?>'
                     value='<?= $settingValue ?? '' ?>' />
              <?php
                if(!empty($validationError)) {
                  ?>
                  <p class='small-text error-message'>
                    <?= $validationError ?>
                  </p>
                  <?php
                }
              ?>
            </td>
          </tr>
          <tr>
            <th scope='row'>
              <label for='<?= self::TRACK_MEDALS_REQUIREMENT_KEY ?>'><?= esc_html__('Track Medals Requirement', 'sim-league-toolkit') ?></label>
            </th>
            <td>
              <?php
                $settingValue = $this->server->getSetting(self::TRACK_MEDALS_REQUIREMENT_KEY);
              ?>
              <input type='number' id='<?= self::TRACK_MEDALS_REQUIREMENT_KEY ?>'
                     name='<?= self::TRACK_MEDALS_REQUIREMENT_KEY ?>' value='<?= $settingValue ?? 0 ?>' min='0'
                     max='3' step='1' />
            </td>
          </tr>
          <tr>
            <th scope='row'>
              <label for='<?= self::SAFETY_RATING_REQUIREMENT_KEY ?>'><?= esc_html__('Safety Rating Requirement', 'sim-league-toolkit') ?></label>
            </th>
            <td>
              <?php
                $settingValue = $this->server->getSetting(self::SAFETY_RATING_REQUIREMENT_KEY);
              ?>
              <input type='number' id='<?= self::SAFETY_RATING_REQUIREMENT_KEY ?>'
                     name='<?= self::SAFETY_RATING_REQUIREMENT_KEY ?>' value='<?= $settingValue ?? -1 ?>'
                     min='-1'
                     max='99' step='1' />
            </td>
          </tr>
          <tr>
            <th scope='row'>
              <label for='<?= self::RACE_CRAFT_REQUIREMENT_KEY ?>'><?= esc_html__('Race Craft Requirement', 'sim-league-toolkit') ?></label>
            </th>
            <td>
              <?php
                $settingValue = $this->server->getSetting(self::RACE_CRAFT_REQUIREMENT_KEY);
              ?>
              <input type='number' id='<?= self::RACE_CRAFT_REQUIREMENT_KEY ?>'
                     name='<?= self::RACE_CRAFT_REQUIREMENT_KEY ?>' value='<?= $settingValue ?? -1 ?>' min='-1'
                     max='99' step='1' />
            </td>
          </tr>
          <tr>
            <?php
              $settingValue = $this->server->getSetting(self::MAX_CAR_SLOTS_KEY);
              $validationError = $this->validationErrors[self::MAX_CAR_SLOTS_KEY] ?? '';
            ?>
            <th scope='row'>
              <label for='<?= self::MAX_CAR_SLOTS_KEY ?>'
                     class='<?= empty($validationError) ? '' : 'error-message' ?>'><?= esc_html__('Max Car Slots', 'sim-league-toolkit') ?></label>
            </th>
            <td>
              <input type='number' id='<?= self::MAX_CAR_SLOTS_KEY ?>'
                     name='<?= self::MAX_CAR_SLOTS_KEY ?>' value='<?= $settingValue ?? 30 ?>' min='1'
                     max='80' step='1' />
              <?php
                if(!empty($validationError)) {
                  ?>
                  <p class='small-text error-message'>
                    <?= $validationError ?>
                  </p>
                  <?php
                }
              ?>
            </td>
          </tr>
          <tr>
            <th scope='row'>
              <label for='<?= self::DUMP_LEADER_BOARDS_KEY ?>'><?= esc_html__('Dump Leader Boards', 'sim-league-toolkit') ?></label>
            </th>
            <td>
              <?php
                $settingValue = $this->server->getSetting(self::DUMP_LEADER_BOARDS_KEY) ?? true;
              ?>
              <input type='checkbox' id='<?= self::DUMP_LEADER_BOARDS_KEY ?>'
                     name='<?= self::DUMP_LEADER_BOARDS_KEY ?>'
                <?= checked($settingValue, true, false) ?> />
            </td>
          </tr>
          <tr>
            <th scope='row'>
              <label for='<?= self::IS_RACE_LOCKED_KEY ?>'><?= esc_html__('Is Race Locked', 'sim-league-toolkit') ?></label>
            </th>
            <td>
              <?php
                $settingValue = $this->server->getSetting(self::IS_RACE_LOCKED_KEY) ?? false;
              ?>
              <input type='checkbox' id='<?= self::IS_RACE_LOCKED_KEY ?>'
                     name='<?= self::IS_RACE_LOCKED_KEY ?>'
                <?= checked($settingValue, true, false) ?> />
            </td>
          </tr>
          <tr>
            <th scope='row'>
              <label for='<?= self::RANDOMIZE_TRACK_WHEN_EMPTY_KEY ?>'><?= esc_html__('Randomize Track When Empty', 'sim-league-toolkit') ?></label>
            </th>
            <td>
              <?php
                $settingValue = $this->server->getSetting(self::RANDOMIZE_TRACK_WHEN_EMPTY_KEY) ?? false;
              ?>
              <input type='checkbox' id='<?= self::RANDOMIZE_TRACK_WHEN_EMPTY_KEY ?>'
                     name='<?= self::RANDOMIZE_TRACK_WHEN_EMPTY_KEY ?>'
                <?= checked($settingValue, true, false) ?> />
            </td>
          </tr>
          <tr>
            <th scope='row'>
              <label for='<?= self::CENTRAL_ENTRY_LIST_PATH_KEY ?>'><?= esc_html__('Central Entry List Path', 'sim-league-toolkit') ?></label>
            </th>
            <td>
              <?php
                $settingValue = $this->server->getSetting(self::CENTRAL_ENTRY_LIST_PATH_KEY);
              ?>
              <input type='text' id='<?= self::CENTRAL_ENTRY_LIST_PATH_KEY ?>'
                     name='<?= self::CENTRAL_ENTRY_LIST_PATH_KEY ?>' value='<?= $settingValue ?? '' ?>' />
            </td>
          </tr>
          <tr>
            <th scope='row'>
              <label for='<?= self::ALLOW_AUTO_DQ_KEY ?>'><?= esc_html__('Allow Auto DQ', 'sim-league-toolkit') ?></label>
            </th>
            <td>
              <?php
                $settingValue = $this->server->getSetting(self::ALLOW_AUTO_DQ_KEY) ?? false;
              ?>
              <input type='checkbox' id='<?= self::ALLOW_AUTO_DQ_KEY ?>'
                     name='<?= self::ALLOW_AUTO_DQ_KEY ?>'
                <?= checked($settingValue, true, false) ?> />
            </td>
          </tr>
          <tr>
            <th scope='row'>
              <label for='<?= self::SHORT_FORMATION_LAP_KEY ?>'><?= esc_html__('Short Formation Lap', 'sim-league-toolkit') ?></label>
            </th>
            <td>
              <?php
                $settingValue = $this->server->getSetting(self::SHORT_FORMATION_LAP_KEY) ?? false;
              ?>
              <input type='checkbox' id='<?= self::SHORT_FORMATION_LAP_KEY ?>'
                     name='<?= self::SHORT_FORMATION_LAP_KEY ?>'
                <?= checked($settingValue, true, false) ?> />
            </td>
          </tr>
          <tr>
            <th scope='row'>
              <label for='<?= self::DUMP_ENTRY_LIST_KEY ?>'><?= esc_html__('Dump Entry List', 'sim-league-toolkit') ?></label>
            </th>
            <td>
              <?php
                $settingValue = $this->server->getSetting(self::DUMP_ENTRY_LIST_KEY) ?? true;
              ?>
              <input type='checkbox' id='<?= self::DUMP_ENTRY_LIST_KEY ?>'
                     name='<?= self::DUMP_ENTRY_LIST_KEY ?>'
                <?= checked($settingValue, true, false) ?> />
            </td>
          </tr>
          <tr>
            <th scope='row'>
              <label for='<?= self::FORMATION_LAP_TYPE_KEY ?>'><?= esc_html__('Formation Lap Type', 'sim-league-toolkit') ?></label>
            </th>
            <td>
              <?php
                $settingValue = (int)($this->server->getSetting(self::FORMATION_LAP_TYPE_KEY) ?? 3);
              ?>
              <select id='<?= self::FORMATION_LAP_TYPE_KEY ?>'
                      name='<?= self::FORMATION_LAP_TYPE_KEY ?>'>
                <option value='3' <?= selected($settingValue, 3, false) ?>><?= esc_html__('Formation Lap with Position Control and UI', 'sim-league-toolkit') ?></option>
                <option value='0' <?= selected($settingValue, 0, false) ?>><?= esc_html__('Old Limiter Lap', 'sim-league-toolkit') ?></option>
                <option value='1' <?= selected($settingValue, 1, false) ?>><?= esc_html__('Free', 'sim-league-toolkit') ?></option>
              </select>
            </td>
          </tr>
          <tr>
            <th scope='row'>
              <label for='<?= self::IGNORE_PREMATURE_DISCONNECTS_KEY ?>'><?= esc_html__('Ignore Premature Disconnects', 'sim-league-toolkit') ?></label>
            </th>
            <td>
              <?php
                $settingValue = $this->server->getSetting(self::IGNORE_PREMATURE_DISCONNECTS_KEY) ?? false;
              ?>
              <input type='checkbox' id='<?= self::IGNORE_PREMATURE_DISCONNECTS_KEY ?>'
                     name='<?= self::IGNORE_PREMATURE_DISCONNECTS_KEY ?>'
                <?= checked($settingValue, true, false) ?> />
            </td>
          </tr>
        </table>
      </fieldset>
      <?php
    }

    /**
     * @inheritDoc
     */
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

      if(!$this->validate()) {
        return;
      }
    }

    private function validate(): bool {
      $isValid = true;
      $this->validationErrors = [];

      $password = $this->server->getSetting(self::PASSWORD_KEY);
      $spectatorPassword = $this->server->getSetting(self::SPECTATOR_PASSWORD_KEY);
      if(!empty($password) && !empty($spectatorPassword) && $password === $spectatorPassword) {
        $isValid = false;
        $this->validationErrors[self::SPECTATOR_PASSWORD_KEY] = esc_html__('Spectator Password cannot be the same as Password.', 'sim-league-toolkit');
      }

      $maxCarSlots = (int)$this->server->getSetting(self::MAX_CAR_SLOTS_KEY);
      $maxConnections = (int)$this->server->getSetting(self::MAX_CONNECTIONS_KEY);

      if($maxCarSlots > $maxConnections) {
        $isValid = false;
        $this->validationErrors[self::MAX_CAR_SLOTS_KEY] = esc_html__('Max Car Slots cannot be more than Max Connections.', 'sim-league-toolkit');
      }

      $publicIp = $this->server->getSetting(self::PUBLIC_IP_KEY);
      if(!filter_var($publicIp, FILTER_VALIDATE_IP)) {
        $isValid = false;
        $this->validationErrors[self::PUBLIC_IP_KEY] = esc_html__('The specified Public IP is not a valid IP address.', 'sim-league-toolkit');
      }

      return $isValid;
    }
  }