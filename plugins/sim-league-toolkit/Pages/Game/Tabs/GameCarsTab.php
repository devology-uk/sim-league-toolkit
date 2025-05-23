<?php

  namespace SLTK\Pages\Game\Tabs;


  use SLTK\Domain\Game;

  class GameCarsTab {
    private GameCarsTabController $controller;

    public function __construct(Game $game, bool $isReadOnly) {
      $this->controller = new GameCarsTabController($game, $isReadOnly);
    }

    public function render(): void {
      ?>
      <div class='wrap'>
        <?php
          $this->controller->theLeader();
          $this->controller->theCars();
        ?>
      </div>
      <?php
    }
  }