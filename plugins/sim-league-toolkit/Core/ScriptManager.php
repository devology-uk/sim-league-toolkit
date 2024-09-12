<?php

  namespace SLTK\Core;

  /**
   * Handles loading of JavaScript scripts
   */
  class ScriptManager {

    /**
     * Enqueues scripts for the current page
     *
     * @param string $hook_suffix The suffix of the current page
     *
     * @return void
     */
    public static function enqueueAdminScripts(string $hook_suffix): void {

      $scriptModule = self::getScriptModule($hook_suffix);

      if(!$scriptModule) {
        return;
      }

      $assetFile = SLTK_PLUGIN_DIR . 'build/admin/' . $scriptModule . '/index.asset.php';

      if(!file_exists($assetFile)) {
        return;
      }

      $asset = include $assetFile;

      wp_enqueue_script(
        'sltk-admin-page-script',
        SLTK_PLUGIN_ROOT_URL . '/build/admin/' . $scriptModule . '/index.js',
        $asset['dependencies'],
        $asset['version'],
        [
          'in_footer' => true,
        ]
      );

    }

    /**
     * Maps the hook suffix of current page to a JavaScript module name
     *
     * @param string $hook_suffix Suffix for current page
     *
     * @return string|null The name of the script to be loaded
     */
    public static function getScriptModule(string $hook_suffix): ?string {
      if(str_ends_with($hook_suffix, AdminPageSlugs::RACE_NUMBERS)) {
        return AdminScriptModules::RACE_NUMBERS;
      }

      if(str_ends_with($hook_suffix, AdminPageSlugs::SERVERS)) {
        return AdminScriptModules::SERVER;
      }

      return null;
    }

    /**
     * Initialises the service
     *
     * @return void
     */
    public static function init(): void {
      add_action('admin_enqueue_scripts', [self::class, 'enqueueAdminScripts']);
    }
  }