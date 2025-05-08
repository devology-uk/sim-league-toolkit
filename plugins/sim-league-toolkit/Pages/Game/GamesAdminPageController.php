<?php

  namespace SLTK\Pages\Game;

  use SLTK\Core\HtmlTagProvider;
  use SLTK\Domain\Game;
  use SLTK\Pages\ControllerBase;

  class GamesAdminPageController extends ControllerBase {
    private const string UNSUPPORTED_GAME_NAME_FIELD = 'sltk-unsupported-game-name';

    public function theHiddenFields(): void {
      $this->theNonce();
    }

    public function theUnsupportedGameField(): void { ?>
        <tr>
            <th scope='row'>
                <label class='form-label'
                       for='<?= self::UNSUPPORTED_GAME_NAME_FIELD ?>'><?= esc_html__('Requested Game Name', 'sim-league-toolkit') ?></label>
            </th>
            <td>
                <input type='text' class='form-field' id='<?= self::UNSUPPORTED_GAME_NAME_FIELD ?>'
                       name='<?= self::UNSUPPORTED_GAME_NAME_FIELD ?>'/>
            </td>
        </tr>
      <?php
    }

    public function theTable(): void {
      $table = new GamesTable();
      $table->prepare_items();
      $table->display();
    }

    protected function handleGet(): void {
    }

    protected function handlePost(): void {
      if (!$this->validateNonce()) {
        return;
      }

      $requestedGame = $this->getSanitisedFieldFromPost(self::UNSUPPORTED_GAME_NAME_FIELD);
      if (!$requestedGame) {
        return;
      }

      $siteTitle = get_bloginfo('name');
      $message = "A request has been submitted by {$siteTitle} to have support for {$requestedGame} added to SLTK.";
      wp_mail('mike.hanson@kodeology.com', 'SLTK Game Request', $message);
      HtmlTagProvider::theSuccessMessage(esc_html__('Your request to have support for ', 'sim-league-toolkit') . $requestedGame . esc_html__(' added to Sim League Toolkit has been sent to the team.', 'sim-league-toolkit'));
    }
  }