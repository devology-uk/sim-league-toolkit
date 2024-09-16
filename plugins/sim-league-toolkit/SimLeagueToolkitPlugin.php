<?php

  namespace SLTK;

  use SLTK\Api\ApiRegistrar;
  use SLTK\Core\ScriptManager;
  use SLTK\Core\StyleManager;
  use SLTK\Core\UserProfileExtension;
  use SLTK\Database\DatabaseBuilder;
  use SLTK\Menu\AdminMenuBuilder;

  class SimLeagueToolkitPlugin {

    public static function activate(): void {
      DatabaseBuilder::init();
      DatabaseBuilder::initialiseOrUpdate();
    }

    public static function deactivate(): void {}

    public static function init(): void {
      StyleManager::init();
      ScriptManager::init();
      UserProfileExtension::init();
    }

    public static function loaded(): void {
      AdminMenuBuilder::init();
      ApiRegistrar::init();
    }

    public static function uninstall(): void {
//      DatabaseBuilder::init();
//      DatabaseBuilder::uninstall();
    }
  }