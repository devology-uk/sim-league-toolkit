<?php

  namespace SLTK\Pages\Game\Tabs;


  use SLTK\Domain\Game;

  class GameCarClassesTab {
    private GameCarClassesTabController $controller;
    private Game $game;
    private bool $isReadOnly;

    public function __construct(Game $game, bool $isReadOnly) {
      $this->controller = new GameCarClassesTabController($game, $isReadOnly);
      $this->isReadOnly = $isReadOnly;
    }

    public function render(): void {}
  }