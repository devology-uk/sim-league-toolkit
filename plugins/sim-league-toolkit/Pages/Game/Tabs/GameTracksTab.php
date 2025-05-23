<?php

  namespace SLTK\Pages\Game\Tabs;


  use SLTK\Domain\Game;

  class GameTracksTab {
    private GameTracksTabController $controller;

    public function __construct(Game $game, bool $isReadOnly) {
      $this->controller = new GameTracksTabController($game, $isReadOnly);
    }

    public function render(): void {
      ?>
      <div class='wrap'>
        <?php
          $this->controller->theLeader();
          $this->controller->theTracks();
        ?>
      </div>
      <?php
    }
  }