<?php

  namespace SLTK\Pages\Game;

  use SLTK\Core\HtmlTagProvider;
  use SLTK\Domain\Game;
  use SLTK\Pages\ControllerBase;

  class GamesAdminPageController extends ControllerBase {
    private const string UNSUPPORTED_GAME_NAME_FIELD = 'sltk-unsupported-game-name';

    public function theGamesRows(): void {
      $games = Game::list();
      foreach($games as $game) {
        ?>
        <tr>
          <td><?= $game->name ?></td>
          <td><?= $game->latestVersion ?></td>
          <td><?= $game->supportsResultUpload ? esc_html__('Yes', 'sim-league-toolkit') : esc_html__('No', 'sim-league-toolkit') ?></td>
        </tr>
        <?php
      }
    }

    public function theHiddenFields(): void {
      wp_nonce_field(self::NONCE_ACTION, self::NONCE_NAME);
    }

    public function theUnsupportedGameField(): void { ?>
      <tr>
        <th scope='row'>
          <label class='form-label'
                 for='<?= self::UNSUPPORTED_GAME_NAME_FIELD ?>'><?= esc_html__('Requested Game Name', 'sim-league-toolkit') ?></label>
        </th>
        <td>
          <input type='text' class='form-field' id='<?= self::UNSUPPORTED_GAME_NAME_FIELD ?>'
                 name='<?= self::UNSUPPORTED_GAME_NAME_FIELD ?>' />
        </td>
      </tr>
      <?php
    }

    protected function handleGet(): void {
      // TODO: Implement handleGet() method.
    }

    protected function handlePost(): void {
      if(!$this->validateNonce(self::NONCE_NAME, self::NONCE_ACTION)) {
        return;
      }

      $requestedGame = $this->getSanitisedFieldFromPost(self::UNSUPPORTED_GAME_NAME_FIELD);
      if(!$requestedGame) {
        return;
      }

      $siteTitle = get_bloginfo('name');
      $message = "A request has been submitted by {$siteTitle} to have support for {$requestedGame} added to SLTK.";
      wp_mail('playologyio@gmail.com', 'SLTK Game Request', $message);
      HtmlTagProvider::theSuccessMessage(esc_html__('Your request to have support for ', 'sim-league-toolkit') . $requestedGame . esc_html__(' added to Sim League Toolkit has been sent to the team.', 'sim-league-toolkit'));
    }
  }