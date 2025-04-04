<?php
  /*
   * Plugin Name:       SIM League Toolkit
   * Plugin URI:        https://kodeology.com/sim-leauge-toolkit
   * Description:       A collection of blocks and admin pages to run and manage a league using almost any motor racing simulator or game
   * Version:           1.0.0
   * Requires at least: 6
   * Requires PHP:      8.3
   * Author:            Kodeology
   * Author URI:        https://kodeology.com
   * License:           GPL v2 or later
   * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
   * Update URI:        https://kodeology.com/sim-leauge-toolkit
   * Text Domain:       sim-league-toolkit
   * Domain Path:       /languages
   * Requires Plugins:
   */

  use SLTK\Core\AutoLoader;
  use SLTK\SimLeagueToolkitPlugin;

  define('SLTK_PLUGIN_DIR', plugin_dir_path(__FILE__));
  define('SLTK_PLUGIN_ROOT_URL', plugins_url('', __FILE__));

  require_once(SLTK_PLUGIN_DIR . 'core' . DIRECTORY_SEPARATOR . 'AutoLoader.php');

  AutoLoader::init();

  register_activation_hook(__FILE__, [SimLeagueToolkitPlugin::class, 'activate']);
  register_deactivation_hook(__FILE__, [SimLeagueToolkitPlugin::class, 'deactivate']);
  register_uninstall_hook(__FILE__, [SimLeagueToolkitPlugin::class, 'uninstall']);

  add_action('plugin_loaded', [SimLeagueToolkitPlugin::class, 'loaded']);
  add_action('init', [SimLeagueToolkitPlugin::class, 'init']);


