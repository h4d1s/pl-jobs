<?php

if ( ! defined( "ABSPATH" ) ) {
	exit; // Exit if accessed directly
}

if (class_exists("PL_Jobs_Post_Meta_Box_Status")) {
  return;
}

class PL_Jobs_Post_Meta_Box_Status
{
  public static function init() {
    add_action( 'enqueue_block_editor_assets', array(__CLASS__, 'enqueue') );
  }

  public static function enqueue() {
    $post_id = get_the_ID();
    $post_views = PL_Jobs_Post::get_post_views($post_id);
    $applicants_count = PL_Jobs_Post::get_applicants_count($post_id);
    $application_date = PL_Jobs_Post::get_last_application_date($post_id);

    $args = array(
      "post_views" => $post_views,
      "applicants_count" => $applicants_count,
      "application_date" => $application_date,
    );

    $editor_assets = require_once(PL_JOBS_DIR . '/includes/post/meta-boxes/jobs-status/build/index.asset.php');
    wp_enqueue_script(
      'jobs-status-block',
      trailingslashit(PL_JOBS_URL) . '/includes/post/meta-boxes/jobs-status/build/index.js',
      $editor_assets["dependencies"],
      $editor_assets["version"],
      true
    );

    wp_localize_script(
      'jobs-status-block',
      'jobs_status_block_data',
      $args
    );
  }
}
