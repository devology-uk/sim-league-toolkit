<?php

  namespace SLTK\Pages\Game\Tabs;


  use Exception;
  use SLTK\Domain\Game;

  class GameCarsTab {
    private GameCarsTabController $controller;

    public function __construct(Game $game) {
      $this->controller = new GameCarsTabController($game);
    }

      /**
       * @throws Exception
       */
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