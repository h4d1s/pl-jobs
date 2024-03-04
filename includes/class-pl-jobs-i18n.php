<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://
 * @since      1.0.0
 *
 * @package    Pl_Jobs
 * @subpackage Pl_Jobs/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Pl_Jobs
 * @subpackage Pl_Jobs/includes
 * @author     Pixel Labs <name@domain.com>
 */

if (class_exists('PL_Jobs_i18n')) {
  return;
}

class PL_Jobs_i18n {

  public static function init() {
    add_action( 'plugins_loaded', array(__CLASS__, 'load_plugin_textdomain') );
  }

  /**
   * Load the plugin text domain for translation.
   *
   * @since    1.0.0
   */
  public function load_plugin_textdomain() {

    load_plugin_textdomain(
      'pl-jobs',
      false,
      PL_JOBS_DIR . '/languages/'
    );
  }
}

PL_Jobs_i18n::init();
