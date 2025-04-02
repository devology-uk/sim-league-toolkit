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

    private const string SAVE_SERVER_BUTTON_NAME = 'sltk-save-server-button';
    private const string SAVE_SETTINGS_BUTTON_NAME = 'sltk-save-settings-button';

    private string $action = Constants::ACTION_EDIT;
    private string $gameKey = '';
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
      if ($this->action !== Constants::ACTION_EDIT) {
        return;
      }
      ?>
        <p>
          <?= esc_html__('The settings for this server are shown below.  You cannot change the game.  Remember to click the Save button when all changes are complete,', 'sim-league-toolkit') ?>
        </p>
      <?php
    }

    public function theGameSelector(): void {
      $error = $this->getError(GameSelectorComponent::FIELD_ID);
      ?>
        <tr>
            <th scope='row'>
                <label for='<?= GameSelectorComponent::FIELD_ID ?>' <?= HtmlTagProvider::errorLabelClass($error) ?>>
                  <?= esc_html__('Game', 'sim-league-toolkit') ?></label></th>
            <td>
              <?php
                $this->gameSelectorComponent->render();
                HtmlTagProvider::theValidationError($error);
              ?>
            </td>
        </tr>
      <?php
    }

    public function theGameSpecificSettings(): void {
      $this->settingsProvider->render();
    }

    public function theIsHostedField(): void {
      if ($this->server->gameId === Constants::DEFAULT_ID) {
        return;
      }
      ?>
        <tr>
            <th scope='row'>
                <label for='<?= Server::IS_HOSTED_FIELD_NAME ?>'>
                  <?= esc_html__('Is Hosted', 'sim-league-toolkit') ?>
                </label>
            </th>
            <td>
                <input type='checkbox' id='<?= Server::IS_HOSTED_FIELD_NAME ?>'
                       name='<?= Server::IS_HOSTED_FIELD_NAME ?>' <?= checked($this->server->isHostedServer) ?>>
            </td>
        </tr>
      <?php
    }

    public function theNameField(): void {
      if ($this->server->gameId === Constants::DEFAULT_ID) {
        return;
      }

      HtmlTagProvider::theAdminTextInput(esc_html__('Name', 'sim-league-toolkit'),
        Server::NAME_FIELD_NAME,
        $this->server->name ?? '',
        $this->getError(Server::NAME_FIELD_NAME),
        esc_html__('Unique server name', 'sim-league-toolkit'));
    }

    public function theNewServerMessage(): void {
      if ($this->action !== Constants::ACTION_ADD) {
        return;
      }
      ?>
        <p>
          <?= esc_html__('To get started creating a new server select a game from the list.  Once the server is created you will not be able to change the game.', 'sim-league-toolkit') ?>
        </p>
      <?php
    }

    public function thePlatformSelector(): void {
      if ($this->server->gameId === Constants::DEFAULT_ID) {
        return;
      }
      $error = $this->getError(PlatformSelectorComponent::FIELD_ID);
      ?>
        <tr>
            <th scope='row'>
                <label for='<?= PlatformSelectorComponent::FIELD_ID ?>'
                  <?= HtmlTagProvider::errorLabelClass($error) ?>>
                  <?= esc_html__('Platform', 'sim-league-toolkit') ?>
                </label>
            </th>
            <td>
              <?php
                $this->platformSelectorComponent->render();
                HtmlTagProvider::theValidationError($error);
              ?>
            </td>
        </tr>
      <?php
    }

    public function theSaveServerButton(): void {
      if ($this->server->gameId === Constants::DEFAULT_ID) {
        return;
      }
      ?>
        <input type='submit' class='button button-primary' id='<?= self::SAVE_SERVER_BUTTON_NAME ?>'
               name='<?= self::SAVE_SERVER_BUTTON_NAME ?>' value='<?= esc_html__('Save', 'sim-league-toolkit') ?>'
               title='<?= esc_html__('Save the server details.', 'sim-league-toolkit') ?>'/>
      <?php
    }

    public function theSaveServerSettingsButton(): void {
      if ($this->server->gameId === Constants::DEFAULT_ID || !$this->settingsProvider->canSave()) {
        return;
      }
      ?>
        <input type='submit' class='button button-primary' id='<?= self::SAVE_SETTINGS_BUTTON_NAME ?>'
               name='<?= self::SAVE_SETTINGS_BUTTON_NAME ?>'
               value='<?= esc_html__('Save Settings', 'sim-league-toolkit') ?>'
               title='<?= esc_html__('Save the server settings.', 'sim-league-toolkit') ?>'/>
      <?php
    }

    public function theServerHiddenFields(): void {
      $this->theNonce();
      HtmlTagProvider::theHiddenField(FieldNames::PAGE_ACTION, $this->action);
      HtmlTagProvider::theHiddenField(FieldNames::ID, $this->id);
      HtmlTagProvider::theHiddenField(Server::GAME_ID_FIELD_NAME, $this->server->gameId);
      HtmlTagProvider::theHiddenField(Server::GAME_KEY_FIELD_NAME, $this->gameKey);
    }

    public function theSettingsHiddenFields(): void {
      wp_nonce_field(self::NONCE_ACTION, self::NONCE_NAME);
      HtmlTagProvider::theHiddenField(FieldNames::ID, $this->id);
      HtmlTagProvider::theHiddenField(Server::GAME_KEY_FIELD_NAME, $this->gameKey);
    }

    private function loadServer(): void {
      $this->server = Server::get($this->id);
      $this->gameSelectorComponent->setValue($this->server->gameId);
      $this->platformSelectorComponent->setValue($this->server->platformId);
      $this->platformSelectorComponent->setGameId($this->server->gameId);
    }

    private function processGameSelection(): void {
      $gameId = (int)$this->gameSelectorComponent->getValue();
      if ($gameId === Constants::DEFAULT_ID) {
        HtmlTagProvider::theErrorMessage(__('You must select a game.', 'sim-league-toolkit'));

        return;
      }


      $this->platformSelectorComponent->setGameId($gameId);
      $this->server = new Server();
      $this->server->gameId = $gameId;
      $this->gameKey = Game::getGameKey($gameId);
    }

    private function processSaveServer(): void {

      if (!$this->validateNonce()) {
        return;
      }

      $isHostedServer = $this->postContainsField(Server::IS_HOSTED_FIELD_NAME);
      $name = $this->getSanitisedFieldFromPost(Server::NAME_FIELD_NAME, '');
      $platformId = (int)$this->platformSelectorComponent->getValue();

      $this->server->name = $name;
      $this->server->isHostedServer = $isHostedServer;
      $this->server->platformId = $platformId;

      $validationResult = $this->server->validate();
      if (!$validationResult->success) {
        $this->errors = $validationResult->validationErrors;

        return;
      }

      if ($this->server->save()) {
        if ($this->action === Constants::ACTION_ADD) {
          $params = [
            QueryParamNames::ID => $this->server->id,
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

    protected function handleGet(): void {
      $this->action = $this->getActionFromUrl();

      if ($this->action === Constants::ACTION_EDIT) {
        $this->id = (int)$this->getSanitisedFieldFromUrl(FieldNames::ID, Constants::DEFAULT_ID);
        $this->loadServer();
        $this->gameKey = Game::getGameKey($this->server->gameId);
        $this->settingsProvider = ServerSettingsProviderFactory::create($this->gameKey, $this->server);

        return;
      }

      $this->server = new Server();
    }

    protected function handlePost(): void {
      $this->action = $this->getActionFromPost();
      $this->id = $this->getSanitisedFieldFromPost(FieldNames::ID, Constants::DEFAULT_ID);

      if ($this->action === Constants::ACTION_ADD && !$this->postContainsField(self::SAVE_SERVER_BUTTON_NAME)) {
        $this->processGameSelection();

        return;
      }

      $this->gameKey = $this->getSanitisedFieldFromPost(Server::GAME_KEY_FIELD_NAME, '');

      if (empty($this->gameKey)) {
        $this->gameKey = Game::getGameKey($this->id);
      }

      if ($this->id === Constants::DEFAULT_ID) {
        $this->server = new Server();
        $this->server->gameId = (int)$this->gameSelectorComponent->getValue();
      } else {
        $this->loadServer();
      }

      $this->settingsProvider = ServerSettingsProviderFactory::create($this->gameKey, $this->server);

      if ($this->postContainsField(self::SAVE_SERVER_BUTTON_NAME)) {
        $this->processSaveServer();

        return;
      }

      if ($this->postContainsField(self::SAVE_SETTINGS_BUTTON_NAME)) {
        $this->processSaveSettings();
      }
    }

  }