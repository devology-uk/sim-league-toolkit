<?php

  namespace SLTK\Config;

  class GameConfigLoader
  {
    private static array $cache = [];

    public static function load(string $gameId): object
    {
      if (!isset(self::$cache[$gameId]))
      {
        $path = SLTK_PLUGIN_DIR . "/config/games/{$gameId}.json";

        if (!file_exists($path))
        {
          throw new \InvalidArgumentException("Unknown game: {$gameId}");
        }

        self::$cache[$gameId] = json_decode(file_get_contents($path));
      }

      return self::$cache[$gameId];
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
