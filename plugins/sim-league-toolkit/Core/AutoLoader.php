<?php

  namespace SLTK\Core;

  class AutoLoader {
    private const NAMESPACE_ROOT = 'SLTK';

    public static function init(): void {
      spl_autoload_register([self::class, 'load']);
    }

    public static function load(string $type): void {
      if(!str_starts_with($type, self::NAMESPACE_ROOT)) {
        return;
      }

      $typeElements = explode('\\', $type);

      if(!is_array($typeElements)) {
        return;
      }

      array_shift($typeElements);
      $typeName = array_pop($typeElements);
      $path = SLTK_PLUGIN_DIR . strtolower(implode(DIRECTORY_SEPARATOR, $typeElements)) . DIRECTORY_SEPARATOR . $typeName . '.php';

      if(file_exists($path)) {
        require_once $path;
      }

    }
  }