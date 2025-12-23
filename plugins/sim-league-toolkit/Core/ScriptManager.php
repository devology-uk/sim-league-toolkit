<?php

  namespace SLTK\Core;

  class ScriptManager {

    public static function enqueueAdminScripts(string $hookSuffix): void {

      if(!str_ends_with($hookSuffix, 'sim-league-toolkit')) {
        return;
      }

      $assetFile = SLTK_PLUGIN_DIR . 'build/admin/index.asset.php';

      if(!file_exists($assetFile)) {
        return;
      }

      $asset = include $assetFile;

      wp_enqueue_script(
        'sltk-admin-script',
        SLTK_PLUGIN_ROOT_URL . '/build/admin/index.js',
        $asset['dependencies'],
        $asset['version'],
        true
      );

    }

    public static function init(): void {
      add_action('admin_enqueue_scripts', [self::class, 'enqueueAdminScripts']);
    }
  }