<?php

  namespace SLTK\Core;

  class DevServer {

    private static int $port = 3000;
    private static ?bool $isRunning = null;

    public static function isRunning(): bool {
      if (!defined('WP_DEBUG') || !WP_DEBUG) {
        return false;
      }

      if (self::$isRunning !== null) {
        return self::$isRunning;
      }

      $context = stream_context_create([
        'http' => [
          'timeout' => 0.3,
          'ignore_errors' => true,
        ]
      ]);

      $response = @file_get_contents('http://localhost:' . self::$port . '/', false, $context);
      self::$isRunning = ($response !== false);

      return self::$isRunning;
    }

    public static function getBaseUrl(): string {
      return 'http://localhost:' . self::$port;
    }

    public static function getPort(): int {
      return self::$port;
    }
  }