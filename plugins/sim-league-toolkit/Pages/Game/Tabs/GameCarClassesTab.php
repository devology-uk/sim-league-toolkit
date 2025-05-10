<?php

  namespace SLTK\Pages\Game\Tabs;


  use SLTK\Domain\Game;

  class GameCarClassesTab {
    private GameCarClassesTabController $controller;

    public function __construct(Game $game, bool $isReadOnly) {
      $this->controller = new GameCarClassesTabController($game, $isReadOnly);
    }

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