<?php

  namespace SLTK\Core;

  class StyleManager {

    public static function enqueueAdminStyles($hookSuffix): void {
      if (!str_ends_with($hookSuffix, 'sim-league-toolkit')) {
        return;
      }

      // CSS is always loaded from build directory since writeToDisk: true
      // This ensures styles work with both dev server and production
      $assetFile = SLTK_PLUGIN_DIR . 'build/admin/index.asset.php';

      if (!file_exists($assetFile)) {
        return;
      }

      $asset = include $assetFile;

      // Use filemtime for dev to bust cache on changes
      $version = DevServer::isRunning()
        ? filemtime(SLTK_PLUGIN_DIR . 'build/admin/index.css')
        : $asset['version'];

      wp_enqueue_style(
        'sltk-admin-styles',
        SLTK_PLUGIN_ROOT_URL . '/build/admin/index.css',
        array_filter(
          $asset['dependencies'],
          function ($style) {
            return wp_style_is($style, 'registered');
          }
        ),
        $version
      );
    }

    public static function init(): void {
      add_action('admin_enqueue_scripts', [self::class, 'enqueueAdminStyles']);
    }
  }