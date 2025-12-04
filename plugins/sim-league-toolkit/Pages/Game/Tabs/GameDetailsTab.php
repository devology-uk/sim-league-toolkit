<?php

  namespace SLTK\Pages\Game\Tabs;

  use Exception;
  use SLTK\Domain\Game;

  class GameDetailsTab {
    private GameDetailsTabController $controller;
    private Game $game;

    public function __construct(Game $game) {
      $this->game = $game;
      $this->controller = new  GameDetailsTabController($this->game);
    }

      /**
       * @throws Exception
       */
      public function render(): void { ?>
        <div class='wrap'>
            <form method='post' enctype='multipart/form-data'>
              <?php
                $this->controller->theHiddenFields();
              ?>
                <table class='form-table'>
                  <?php
                    $this->controller->theNameField();
                    $this->controller->thePlatformsSelector();
                    $this->controller->theLatestVersionField();
                    $this->controller->theSupportsResultUploadField();
                    $this->controller->theIsPublishedField();
                    $this->controller->theIsBuiltinField();
                  ?>
                </table>
            </form>
        </div>
      <?php

    }
  }