<?php

  namespace SLTK\Pages\Server\Settings;

  use SLTK\Core\GameKeys;
  use SLTK\Domain\Server;

  class ServerSettingsProviderFactory {
    public static function create(string $gameKey, Server $server): ServerSettingsProvider {
      return match ($gameKey) {
        GameKeys::AssettoCorsaCompetizione => new AccServerSettingsProvider($server),
        default => new FallbackServerSettingsProvider($server),
      };
    }
  }