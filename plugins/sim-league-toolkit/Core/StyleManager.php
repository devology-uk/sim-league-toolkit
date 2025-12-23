<?php

  namespace SLTK\Core;

  class StyleManager {

    public static function enqueueAdminStyles($hookSuffix): void {
      if(!str_ends_with($hookSuffix, 'sim-league-toolkit')) {
        return;
      }

      $assetFile = SLTK_PLUGIN_DIR . 'build/admin/index.asset.php';

      if(!file_exists($assetFile)) {
        return;
      }

      $asset = include $assetFile;

      wp_enqueue_style('sltk-admin-styles',
        SLTK_PLUGIN_ROOT_URL . '/build/admin/index.css',
        array_filter(
          $asset['dependencies'],
          function ( $style ) {
            return wp_style_is( $style, 'registered' );
          }
        ),
        $asset['version']);
    }

    public static function init(): void {
      add_action('admin_enqueue_scripts', [self::class, 'enqueueAdminStyles']);
    }
  }