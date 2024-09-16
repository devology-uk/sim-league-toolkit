<?php

  namespace SLTK\Core;

  class StyleManager {

    public static function enqueueAdminStyles(): void {
      wp_enqueue_style('sltk-admin-styles', SLTK_PLUGIN_ROOT_URL . '/css/admin-styles.css');
      wp_enqueue_style('wp-components');
    }

    public static function init(): void {
      add_action('admin_enqueue_scripts', [self::class, 'enqueueAdminStyles']);
    }
  }