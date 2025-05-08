<?php

  namespace SLTK\Pages\Game\Tabs;

  use SLTK\Core\HtmlTagProvider;
  use SLTK\Domain\Game;
  use SLTK\Domain\Platform;
  use SLTK\Pages\ControllerBase;

  class GameDetailsTabController extends ControllerBase {

    private Game $game;
    private bool $isReadOnly;

    public function __construct(Game $game, bool $isReadOnly) {
      parent::__construct();
      $this->game = $game;
      $this->isReadOnly = $isReadOnly;
    }


    public function theHiddenFields(): void {
      $this->theNonce();
    }

    public function theLatestVersionField(): void {
      HtmlTagProvider::theAdminTextInput(esc_html__('Latest Version', 'sim-league-toolkit'),
        Game::LATEST_VERSION_FIELD_NAME,
        $this->game->getLatestVersion() ?? '',
        $this->getError(Game::LATEST_VERSION_FIELD_NAME),
        esc_html__('The latest version of the game supported by Sim League Toolkit', 'sim-league-toolkit'),
        isReadOnly: $this->isReadOnly
      );
    }

    public function theNameField(): void {
      HtmlTagProvider::theAdminTextInput(esc_html__('Name', 'sim-league-toolkit'),
        Game::NAME_FIELD_NAME,
        $this->game->getName() ?? '',
        $this->getError(Game::NAME_FIELD_NAME),
        esc_html__('Name of the game as published.', 'sim-league-toolkit'),
        isReadOnly: $this->isReadOnly);
    }

    public function thePlatformsSelector(): void {
      $error = $this->getError(Game::PLATFORMS_FIELD_NAME)
      ?>

        <tr>
            <th scope='row'>
                <label for='<?= Game::PLATFORMS_FIELD_NAME ?>'<?= HtmlTagProvider::errorLabelClass($error) ?>>
                  <?= esc_html__('Platforms', 'sim-league-toolkit') ?>
                </label>
            </th>
            <td>
                <ul>
                  <?php
                    $platforms = Platform::list();
                    $gamePlatformIds = $this->game->getPlatformIds();
                    foreach ($platforms as $platform) {
                      $isSupported = in_array($platform->id, $gamePlatformIds);
                      ?>
                        <li>
                            <input type='checkbox' id='<?= Game::PLATFORMS_FIELD_NAME ?>'
                                   name='<?= Game::PLATFORMS_FIELD_NAME ?>' <?= checked($isSupported, true, false) ?>
                                   value='<?= $platform->id ?>'
                              <?= $this->isReadOnly ? 'disabled' : '' ?>
                            />
                            <span><?= $platform->getName() ?></span>
                        </li>
                      <?php
                    }
                  ?>
                </ul>
            </td>
        </tr>
      <?php

    }

    public function theSupportsResultUploadField(): void {
      HtmlTagProvider::theAdminCheckboxInput(esc_html__('Supports Result Upload',
        'sim-league-toolkit'),
        Game::SUPPORTS_RESULT_UPLOAD_FIELD_NAME,
        $this->game->getSupportsResultUpload(),
        $this->isReadOnly);
    }

    public function theIsPublishedField(): void {
      HtmlTagProvider::theAdminCheckboxInput(esc_html__('Published',
        'sim-league-toolkit'),
        Game::IS_PUBLISHED_FIELD_NAME,
        $this->game->getIsPublished(),
        $this->isReadOnly);
    }

    public function theIsBuiltinField(): void {
      HtmlTagProvider::theAdminCheckboxInput(esc_html__('Built In',
        'sim-league-toolkit'),
        Game::IS_BUILTIN_FIELD_NAME,
        $this->game->getIsBuiltin(),
        $this->isReadOnly);
    }

    protected function handleGet(): void {

    }

    protected function handlePost(): void {

    }
  }