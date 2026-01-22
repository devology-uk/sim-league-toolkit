<?php

  namespace SLTK;

  use SLTK\Api\ApiRegistrar;
  use SLTK\Core\EnumValidator;
  use SLTK\Core\MenuManager;
  use SLTK\Core\ScriptManager;
  use SLTK\Core\StyleManager;
  use SLTK\Core\UserProfileExtension;
  use SLTK\Database\DatabaseBuilder;

  class SimLeagueToolkitPlugin {

    public static function activate(): void {
      DatabaseBuilder::init();
      DatabaseBuilder::initialiseOrUpdate();

      $errors = EnumValidator::validate();

      if (!empty($errors))
      {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die(
          '<h1>' . esc_html__('Plugin Activation Error', 'sim-league-toolkit') . '</h1>' .
          '<p>' . esc_html__('Enum validation failed:', 'sim-league-toolkit') . '</p>' .
          '<ul><li>' . implode('</li><li>', array_map('esc_html', $errors)) . '</li></ul>' .
          '<p><a href="' . admin_url('plugins.php') . '">' . esc_html__('Back to Plugins', 'sim-league-toolkit') . '</a></p>',
          esc_html__('Plugin Activation Error', 'sim-league-toolkit'),
          ['back_link' => true]
        );
      }
    }

    public static function deactivate(): void {}

    public static function init(): void {
      UserProfileExtension::init();
    }

    public static function loaded(): void {
      MenuManager::init();
      StyleManager::init();
      ScriptManager::init();
      ApiRegistrar::init();
    }

    public static function uninstall(): void {
//      DatabaseBuilder::init();
//      DatabaseBuilder::uninstall();
    }
  }