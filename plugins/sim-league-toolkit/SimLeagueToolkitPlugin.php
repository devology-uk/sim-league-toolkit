<?php

  namespace SLTK;

  use SLTK\Api\ApiRegistrar;
  use SLTK\Core\ScriptManager;
  use SLTK\Core\StyleManager;
  use SLTK\Core\UserProfileExtension;
  use SLTK\Database\DatabaseBuilder;
  use SLTK\Menu\AdminMenuBuilder;

  /**
   * The Sim League Toolkit controller
   */
  class SimLeagueToolkitPlugin {

    /**
     * Handles activations of the plugin
     *
     * @return void
     */
    public static function activate(): void {
      DatabaseBuilder::init();
      DatabaseBuilder::initialiseOrUpdate();
    }

    /**
     * Handles deactivation of the plugin
     *
     * @return void
     */
    public static function deactivate(): void {}

    /**
     * Handles initialisation of the current request before any headers are sent
     *
     * @return void
     */
    public static function init(): void {
      StyleManager::init();
      ScriptManager::init();
      UserProfileExtension::init();
    }

    /**
     * Handles notification that plugin has been loaded
     *
     * @return void
     */
    public static function loaded(): void {
      AdminMenuBuilder::init();
      ApiRegistrar::init();
    }

    /**
     * Handles uninstall of the plugin
     *
     * @return void
     */
    public static function uninstall(): void {
//      DatabaseBuilder::init();
//      DatabaseBuilder::uninstall();
    }
  }