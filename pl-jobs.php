<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://
 * @since             1.0.0
 * @package           Pl_Jobs
 *
 * @wordpress-plugin
 * Plugin Name:       PL Jobs
 * Plugin URI:        http://
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Pixel Labs
 * Author URI:        http://
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       pl-jobs
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( 'PL_PLUGIN_FILE' ) ) {
	define('PL_PLUGIN_FILE', __FILE__);
}

require_once plugin_dir_path(PL_PLUGIN_FILE) . "includes/class-pl-jobs.php";

function PLJ() {
  return PL_Jobs::instance();
}
if(!array_key_exists("pl_jobs", $GLOBALS)) {
  $GLOBALS["pl_jobs"] = PLJ();
}

register_activation_hook(__FILE__, array("PL_Jobs", "plugin_activate"));
register_deactivation_hook(__FILE__, array("PL_Jobs", "plugin_deactivate"));
