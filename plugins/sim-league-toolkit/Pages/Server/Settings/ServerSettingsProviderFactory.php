<?php

  namespace SLTK\Pages\Server\Settings;

  use SLTK\Core\GameKeys;
  use SLTK\Domain\Server;

  /**
   * Factory for creating provider of game specific settings
   */
  class ServerSettingsProviderFactory {

    /**
     * @param string $gameKey The well known key for the target game
     *
     * @return ServerSettingsProvider|null  New instance of a game specific settings provider for the specified game
     */
    public static function create(string $gameKey, Server $server): ?ServerSettingsProvider {
      return match ($gameKey) {
        GameKeys::AssettoCorsaCompetizione => new AccServerSettingsProvider($server),
        default => null,
      };
    }

  }