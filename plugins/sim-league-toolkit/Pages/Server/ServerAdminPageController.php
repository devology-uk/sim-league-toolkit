<?php

  namespace SLTK\Pages\Server;

  use SLTK\Components\GameSelectorComponent;
  use SLTK\Components\PlatformSelectorComponent;
  use SLTK\Core\AdminPageSlugs;
  use SLTK\Core\Constants;
  use SLTK\Core\FieldNames;
  use SLTK\Core\HtmlTagProvider;
  use SLTK\Core\QueryParamNames;
  use SLTK\Core\UrlBuilder;
  use SLTK\Domain\Game;
  use SLTK\Domain\Server;
  use SLTK\Pages\ControllerBase;
  use SLTK\Pages\Server\Settings\ServerSettingsProvider;
  use SLTK\Pages\Server\Settings\ServerSettingsProviderFactory;

  class ServerAdminPageController extends ControllerBase {

    private const string GAME_ID_FIELD_NAME = 'sltk-game-id';
    private const string GAME_KEY_FIELD_NAME = 'sltk-game-key';
    private const string IS_HOSTED_FIELD_NAME = 'sltk-server-is-hosted';
    private const string SAVE_SERVER_BUTTON_NAME = 'sltk-save-server-button';
    private const string SAVE_SETTINGS_BUTTON_NAME = 'sltk-save-settings-button';
    private const string SERVER_NAME_FIELD_NAME = 'sltk-server-name';
    private const string SERVER_NONCE_ACTION = 'sltk-server-nonce-action';
    private const string SERVER_NONCE_NAME = 'sltk-server-nonce-name';
    private const string SETTINGS_NONCE_ACTION = 'sltk-settings-nonce-action';
    private const string SETTINGS_NONCE_NAME = 'sltk-settings-nonce-name';

    private string $action = Constants::ACTION_EDIT;
    private string $gameKey;
    private GameSelectorComponent $gameSelectorComponent;
    private int $id = Constants::DEFAULT_ID;
    private PlatformSelectorComponent $platformSelectorComponent;
    private Server $server;
    private ServerSettingsProvider $settingsProvider;

    public function __construct() {
      $this->gameSelectorComponent = new GameSelectorComponent();
      $this->platformSelectorComponent = new PlatformSelectorComponent();
      parent::__construct();
    }

    public function showSettings(): bool {
      return $this->server->id !== Constants::DEFAULT_ID;
    }

    public function theBackButton(): void {
      $url = UrlBuilder::getAdminPageRelativeUrl(AdminPageSlugs::SERVERS);
      ?>
      <a class='button button-secondary' href='<?= $url ?>'
         title='<?= esc_html__('Back To Servers', 'sim-league-toolkit') ?>'><?= esc_html__('Back To Servers', 'sim-league-toolkit') ?></a>
      <?php
    }

    public function theExistingServerMessage(): void {
      if($this->action !== Constants::ACTION_EDIT) {
        return;
      }
      ?>
      <p>
        <?= esc_html__('The settings for this server are shown below.  You cannot change the game.  Remember to click the Save button when all changes are complete,', 'sim-league-toolkit') ?>
      </p>
      <?php
    }

    public function theGameSelector(): void {
      ?>
      <tr>
        <th scope='row'><?= esc_html__('Game', 'sim-league-toolkit') ?></th>
        <td>
          <?php
            $this->gameSelectorComponent->render();
          ?>
        </td>
      </tr>
      <?php
    }

    public function theGameSpecificSettings(): void {
      $this->settingsProvider->render();
    }

    public function theIsHostedField(): void {
      if($this->server->gameId === Constants::DEFAULT_ID) {
        return;
      }
      ?>
      <tr>
        <th scope='row'>
          <label for='<?= self::IS_HOSTED_FIELD_NAME ?>'>
            <?= esc_html__('Is Hosted', 'sim-league-toolkit') ?>
          </label>
        </th>
        <td>
          <input type='checkbox' id='<?= self::IS_HOSTED_FIELD_NAME ?>'
                 name='<?= self::IS_HOSTED_FIELD_NAME ?>' <?= checked($this->server->isHostedServer) ?>>
        </td>
      </tr>
      <?php
    }

    public function theNameField(): void {
      if($this->server->gameId === Constants::DEFAULT_ID) {
        return;
      }
      ?>
      <tr>
        <th scope='row'>
          <label for='<?= self::SERVER_NAME_FIELD_NAME ?>'>
            <?= esc_html__('Name', 'sim-league-toolkit') ?>
          </label>
        </th>
        <td>
          <input type='text' id='<?= self::SERVER_NAME_FIELD_NAME ?>'
                 name='<?= self::SERVER_NAME_FIELD_NAME ?>' value='<?= $this->server->name ?? '' ?>'
                 size='50'
          />
        </td>
      </tr>
      <?php
    }

    public function theNewServerMessage(): void {
      if($this->action !== Constants::ACTION_ADD) {
        return;
      }
      ?>
      <p>
        <?= esc_html__('To get started creating a new server select a game from the list.  Once the server is created you will not be able to change the game.', 'sim-league-toolkit') ?>
      </p>
      <?php
    }

    public function thePlatformSelector(): void {
      if($this->server->gameId === Constants::DEFAULT_ID) {
        return;
      }
      ?>
      <tr>
        <th scope='row'><?= esc_html__('Platform', 'sim-league-toolkit') ?></th>
        <td>
          <?php
            $this->platformSelectorComponent->render();
          ?>
        </td>
      </tr>
      <?php
    }

    public function theSaveServerButton(): void {
      if($this->server->gameId === Constants::DEFAULT_ID) {
        return;
      }
      ?>
      <input type='submit' class='button button-primary' id='<?= self::SAVE_SERVER_BUTTON_NAME ?>'
             name='<?= self::SAVE_SERVER_BUTTON_NAME ?>' value='<?= esc_html__('Save', 'sim-league-toolkit') ?>'
             title='<?= esc_html__('Save the server details.', 'sim-league-toolkit') ?>' />
      <?php
    }

    public function theSaveServerSettingsButton(): void {
      if($this->server->gameId === Constants::DEFAULT_ID) {
        return;
      }
      ?>
      <input type='submit' class='button button-primary' id='<?= self::SAVE_SETTINGS_BUTTON_NAME ?>'
             name='<?= self::SAVE_SETTINGS_BUTTON_NAME ?>'
             value='<?= esc_html__('Save Settings', 'sim-league-toolkit') ?>'
             title='<?= esc_html__('Save the server settings.', 'sim-league-toolkit') ?>' />
      <?php
    }

    public function theServerHiddenFields(): void {
      wp_nonce_field(self::SERVER_NONCE_ACTION, self::SERVER_NONCE_NAME);
      $this->theHiddenField(FieldNames::PAGE_ACTION, $this->action);
      $this->theHiddenField(FieldNames::ID, $this->id);
      $this->theHiddenField(self::GAME_ID_FIELD_NAME, $this->server->gameId);
      $this->theHiddenField(self::GAME_KEY_FIELD_NAME, $this->gameKey);
    }

    public function theSettingsHiddenFields(): void {
      wp_nonce_field(self::SETTINGS_NONCE_ACTION, self::SETTINGS_NONCE_NAME);
      $this->theHiddenField(FieldNames::ID, $this->id);
      $this->theHiddenField(self::GAME_KEY_FIELD_NAME, $this->gameKey);
    }

    protected function handleGet(): void {
      $this->action = $this->getActionFromUrl();

      if($this->action === Constants::ACTION_EDIT) {
        $this->id = (int)$this->getSanitisedFieldFromUrl(FieldNames::ID, Constants::DEFAULT_ID);
        $this->server = Server::get($this->id);
        $this->gameSelectorComponent->setValue($this->server->gameId);
        $this->platformSelectorComponent->setValue($this->server->platformId);
        $this->gameKey = Game::getGameKey($this->server->gameId);
        $this->settingsProvider = ServerSettingsProviderFactory::create($this->gameKey, $this->server);

        return;
      }

      $this->server = new Server();
    }

    protected function handlePost(): void {
      $this->action = $this->getActionFromPost();
      $this->id = $this->getSanitisedFieldFromPost(FieldNames::ID, Constants::DEFAULT_ID);

      if($this->action === Constants::ACTION_ADD && !$this->postContainsField(self::SAVE_SERVER_BUTTON_NAME)) {
        $this->processGameSelection();

        return;
      }

      $this->gameKey = $this->getSanitisedFieldFromPost(self::GAME_KEY_FIELD_NAME, '');

      if(empty($this->gameKey)) {
        $this->gameKey = Game::getGameKey($this->id);
      }

      if($this->id === Constants::DEFAULT_ID) {
        $this->server = new Server();
        $this->server->gameId = (int)$this->gameSelectorComponent->getValue();
      } else {
        $this->server = Server::get($this->id);
      }

      $this->settingsProvider = ServerSettingsProviderFactory::create($this->gameKey, $this->server);

      if($this->postContainsField(self::SAVE_SERVER_BUTTON_NAME)) {
        $this->processSaveServer();

        return;
      }

      if($this->postContainsField(self::SAVE_SETTINGS_BUTTON_NAME)) {
        $this->processSaveSettings();
      }
    }

    private function processGameSelection(): void {
      $gameId = (int)$this->gameSelectorComponent->getValue();
      if($gameId === Constants::DEFAULT_ID) {
        HtmlTagProvider::theErrorMessage(__('You must select a game.', 'sim-league-toolkit'));

        return;
      }

      $this->server = new Server();
      $this->server->gameId = $gameId;
      $this->gameKey = Game::getGameKey($gameId);
    }

    private function processSaveServer(): void {

      if(!$this->validateNonce(self::SERVER_NONCE_NAME, self::SERVER_NONCE_ACTION)) {
        return;
      }

      $isHostedServer = $this->postContainsField(self::IS_HOSTED_FIELD_NAME);
      $name = $this->getSanitisedFieldFromPost(self::SERVER_NAME_FIELD_NAME, '');
      $platformId = (int)$this->platformSelectorComponent->getValue();

      $this->server->name = $name;
      $this->server->isHostedServer = $isHostedServer;
      $this->server->platformId = $platformId;

      if(!$this->validateServer()) {
        return;
      }

      if($this->server->save()) {
        if($this->action === Constants::ACTION_ADD) {
          $params = [
            QueryParamNames::ID     => $this->server->id,
            QueryParamNames::ACTION => Constants::ACTION_EDIT
          ];

          $url = UrlBuilder::getAdminPageRelativeUrl(AdminPageSlugs::SERVER, $params);
          HtmlTagProvider::theRedirectScript($url, 2);
        }
        esc_html__('The server was created successfully, please wait...', 'sim-league-toolkit');
      }
    }

    private function processSaveSettings(): void {
      $this->settingsProvider->save();
    }

    private function validateServer(): bool {
      $isValid = true;

      if(empty($this->server->name)) {
        $isValid = false;
        HtmlTagProvider::theErrorMessage(esc_html__('A name for the server is required.', 'sim-league-tools'));
      }

      if($this->server->platformId === Constants::DEFAULT_ID) {
        $isValid = false;
        HtmlTagProvider::theErrorMessage(esc_html__('You must select a platform.', 'sim-league-tools'));
      }

      return $isValid;
    }

  }