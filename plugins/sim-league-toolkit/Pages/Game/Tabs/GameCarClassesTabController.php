<?php

  namespace SLTK\Pages\Game\Tabs;

  use SLTK\Domain\Game;
  use SLTK\Pages\ControllerBase;

  class GameCarClassesTabController extends ControllerBase {

    private Game $game;
    private bool $isReadOnly;

    public function __construct(Game $game, bool $isReadOnly) {
      parent::__construct();
      $this->game = $game;
      $this->isReadOnly = $isReadOnly;
    }

    protected function handleGet(): void {

    }

    protected function handlePost(): void {

    }
  }