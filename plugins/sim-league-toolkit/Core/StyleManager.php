<?php

  namespace SLTK\Core;

  class StyleManager {

    public static function enqueueAdminStyles(): void {
      wp_enqueue_style('font-awesome-cdn', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css');
      wp_enqueue_style('jquery-styles', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
      wp_enqueue_style('timepicker-styles', SLTK_PLUGIN_ROOT_URL . '/assets/css/jquery-timepicker.min.css');
      wp_enqueue_style('sltk-admin-styles', SLTK_PLUGIN_ROOT_URL . '/assets/css/admin-styles.css');
//      wp_enqueue_style('wp-components');
    }

    public static function init(): void {
      add_action('admin_enqueue_scripts', [self::class, 'enqueueAdminStyles']);
    }
  }