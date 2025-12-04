<?php

  namespace SLTK\Pages\Game\Tabs;


  use Exception;
  use SLTK\Domain\Game;

  class GameCarClassesTab {
    private GameCarClassesTabController $controller;

    public function __construct(Game $game) {
      $this->controller = new GameCarClassesTabController($game);
    }

      /**
       * @throws Exception
       */
      public function render(): void {
      ?>
        <div class='wrap'>
          <?php
            $this->controller->theLeader();
            $this->controller->theClasses();
          ?>
        </div>
      <?php
    }
  }