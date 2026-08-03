<?php

  namespace SLTK\Migration;

  use Exception;
  use SLTK\Domain\Game;

  /**
   * Resolves ACCLT's `gameType`/`game` string values (e.g. 'ACC', 'AMS2') to SLTK game ids.
   */
  class GameKeyLookup {
    private array $idsByKey;

    /**
     * @throws Exception
     */
    public function __construct() {
      $this->idsByKey = [];

      foreach (Game::list() as $game) {
        $this->idsByKey[$game->getGameKey()] = $game->getId();
      }
    }

    public function resolve(string $gameKey): ?int {
      return $this->idsByKey[$gameKey] ?? null;
    }
  }
