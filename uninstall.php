<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       http://
 * @since      1.0.0
 *
 * @package    Pl_Jobs
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

class PL_Uninstall {

  public static function clear_all_options() {
    global $pl_jobs;
    foreach ( $pl_jobs->plugin_options as $option ) {
      if ( $pl_jobs->options->has( $option ) ) {
        $pl_jobs->options->delete( $option );
      }
    }
  }

  public static function jobs_delete_dir() {
    WP_Filesystem();
    global $wp_filesystem;

    if ($wp_filesystem->exists(PL_JOBS_UPLOADS)) {
      $wp_filesystem->rmdir(PL_JOBS_UPLOADS, true);
    }
  }
}

PL_Uninstall::jobs_delete_dir();
PL_Uninstall::clear_all_options();
