<?php

  namespace SLTK\Pages\Game\Tabs;

  use SLTK\Domain\Game;

  class GameDetailsTab {
    private GameDetailsTabController $controller;
    private Game $game;
    private bool $isReadOnly;

    public function __construct(Game $game, bool $isReadOnly) {
      $this->game = $game;
      $this->isReadOnly = $isReadOnly;
      $this->controller = new  GameDetailsTabController($this->game, $this->isReadOnly);
    }

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