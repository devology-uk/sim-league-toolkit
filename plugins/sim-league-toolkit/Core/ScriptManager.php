<?php

  namespace SLTK\Core;

  class ScriptManager {

    public static function enqueueAdminScripts(string $hookSuffix): void {
      if (!str_ends_with($hookSuffix, 'sim-league-toolkit')) {
        return;
      }

      if (DevServer::isRunning()) {
        self::enqueueDevScripts();
      } else {
        self::enqueueProductionScripts();
      }
    }

    private static function enqueueDevScripts(): void {
      $baseUrl = DevServer::getBaseUrl();

      // Webpack dev server client for HMR
      wp_enqueue_script(
        'sltk-hmr-client',
        $baseUrl . '/webpack-dev-server.js',
        [],
        null,
        true
      );

      // Main admin script from dev server
      wp_enqueue_script(
        'sltk-admin-script',
        $baseUrl . '/admin/index.js',
        ['wp-element', 'wp-api-fetch', 'wp-i18n'],
        null,
        true
      );

      self::localizeScript();
    }

    private static function enqueueProductionScripts(): void {
      $assetFile = SLTK_PLUGIN_DIR . 'build/admin/index.asset.php';

      if (!file_exists($assetFile)) {
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

      self::localizeScript();
    }

    private static function localizeScript(): void {
      wp_localize_script('sltk-admin-script', 'sltkData', [
        'apiUrl'  => rest_url('sltk/v1/'),
        'nonce'   => wp_create_nonce('wp_rest'),
        'isDebug' => defined('WP_DEBUG') && WP_DEBUG,
      ]);
    }

    public static function init(): void {
      add_action('admin_enqueue_scripts', [self::class, 'enqueueAdminScripts']);
    }
  }