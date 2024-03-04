<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://
 * @since      1.0.0
 *
 * @package    Pl_Jobs
 * @subpackage Pl_Jobs/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Pl_Jobs
 * @subpackage Pl_Jobs/admin
 * @author     Pixel Labs <name@domain.com>
 */

if (class_exists('PL_Jobs_Options')) {
  return;
}

class PL_Jobs_Options {

	/**
	 * The prefix used by all option names.
	 *
	 * @var string
	 */
	private $prefix;

  /**
   * @return string
   */
  public function get_prefix() {
    return $this->prefix;
  }

  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   * @param      string    $plugin_name       The name of this plugin.
   * @param      string    $version    The version of this plugin.
   */
  public function __construct($plugin_name) {
    $this->prefix = $plugin_name . "_options_";
  }

  public function has($name) {
    return $this->get($name) !== null;
  }

  public function add($name, $value) {
    add_option($this->prefix . $name, $value);
  }

  public function get($name, $default = null) {
    return get_option($this->prefix . $name, $default);
  }

  public function set($name, $value) {
    update_option($this->prefix . $name, $value);
  }

  public function delete($name) {
    delete_option($this->prefix . $name);
  }
}
