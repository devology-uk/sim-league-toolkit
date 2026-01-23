<?php

  namespace SLTK\Config;

  use InvalidArgumentException;

  class GameConfigProvider
  {
    private static array $cache = [];

    public static function load(string $gameKey): object
    {
      if (!isset(self::$cache[$gameKey]))
      {
        $path = SLTK_PLUGIN_DIR . "/config/games/{$gameKey}.json";

        if (!file_exists($path))
        {
          throw new InvalidArgumentException("Unknown game: {$gameKey}");
        }

        self::$cache[$gameKey] = json_decode(file_get_contents($path));
      }

      return self::$cache[$gameKey];
    }

    public static function getAvailableGames(): array
    {
      $games = [];
      $configDir = SLTK_PLUGIN_DIR . '/config/games/';

      foreach (glob($configDir . '*.json') as $file)
      {
        $config = json_decode(file_get_contents($file));
        $games[] = [
          'id' => $config->gameId,
          'name' => $config->gameName
        ];
      }

      return $games;
    }
  }
