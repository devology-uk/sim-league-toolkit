<?php

  namespace SLTK\Pages\Game\Tabs;

  use SLTK\Domain\Game;

  class GameDriverCategoriesTab {
    private GameDriverCategoriesTabController $controller;

    public function __construct(Game $game, bool $isReadOnly) {
      $this->controller = new GameDriverCategoriesTabController($game, $isReadOnly);
    }

    public function render(): void {
      ?>
        <div class='wrap'>
          <?php
            $this->controller->theLeader();
            $this->controller->theCategories();
          ?>
        </div>
      <?php
    }
  }