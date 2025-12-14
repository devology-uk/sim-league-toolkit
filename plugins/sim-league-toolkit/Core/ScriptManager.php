<?php

  namespace SLTK\Core;

  class ScriptManager {

    public static function enqueueAdminScripts(string $hook_suffix): void {

      wp_enqueue_script('timepicker-script', SLTK_PLUGIN_ROOT_URL . '/assets/js/jquery-timepicker.min.js', ['jquery'], null, true);
      wp_enqueue_script('sltk-admin-script', SLTK_PLUGIN_ROOT_URL . '/assets/js/admin.js', [
        'jquery',
        'jquery-ui-datepicker'
      ], null, true);

//      $scriptModule = self::getScriptModule($hook_suffix);
//
//      if(!$scriptModule) {
//        return;
//      }
//
//      $assetFile = SLTK_PLUGIN_DIR . 'build/admin/' . $scriptModule . '/index.asset.php';
//
//      if(!file_exists($assetFile)) {
//        return;
//      }
//
//      $asset = include $assetFile;
//
//      wp_enqueue_script(
//        'sltk-admin-page-script',
//        SLTK_PLUGIN_ROOT_URL . '/build/admin/' . $scriptModule . '/index.js',
//        $asset['dependencies'],
//        $asset['version'],
//        [
//          'in_footer' => true,
//        ]
//      );

    }

    public static function getScriptModule(string $hook_suffix): ?string {
      if (str_ends_with($hook_suffix, AdminPageSlugs::RACE_NUMBERS)) {
        return AdminScriptModules::RACE_NUMBERS;
      }

      if (str_ends_with($hook_suffix, AdminPageSlugs::SERVERS)) {
        return AdminScriptModules::SERVER;
      }

      return null;
    }

    public static function init(): void {
      add_action('admin_enqueue_scripts', [self::class, 'enqueueAdminScripts']);
    }
  }