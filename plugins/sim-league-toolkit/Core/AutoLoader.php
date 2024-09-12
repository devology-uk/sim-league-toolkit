<?php

  namespace SLTK\Core;

  /**
   * Handle automatic loading of types within the the Sim League Toolkit plugin
   */
  class AutoLoader {
    private const NAMESPACE_ROOT = 'SLTK';

    /**
     * Initialises automatic loading by registering the load method
     *
     * @return void
     * @see spl_autoload_register
     */
    public static function init(): void {
      spl_autoload_register([self::class, 'load']);
    }

    /**
     * Parses the specified type, converts it to a file path and loads the file it not already loaded
     *
     * @param string $type The fully qualified name of the type to be loaded, passed by WordPress
     *
     * @return void
     */
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