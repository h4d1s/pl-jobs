<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if (class_exists('PL_Jobs_Post_Meta_Box_Meta')) {
  return;
}

class PL_Jobs_Post_Meta_Box_Meta
{
  public static function init() {
    add_action( 'init', array(__CLASS__, 'register') );
    add_action( 'enqueue_block_editor_assets', array(__CLASS__, 'enqueue') );
  }

  public static function register() {
    register_post_meta( PL_Jobs_Post_CPT::POST_TYPE, '_pl_jobs_post_closing_date', array(
      'show_in_rest' => true,
      'single' => true,
      'type' => 'string',
      'auth_callback' => function() {
        return current_user_can( 'edit_posts' );
      }
    ));
    register_post_meta( PL_Jobs_Post_CPT::POST_TYPE, '_pl_jobs_post_position_filled', array(
      'show_in_rest' => true,
      'single' => true,
      'type' => 'boolean',
      'auth_callback' => function() {
        return current_user_can( 'edit_posts' );
      }
    ));
  }

  public static function enqueue() {
    $editor_assets = require_once(trailingslashit(PL_JOBS_DIR) . '/includes/post/meta-boxes/jobs-meta/build/index.asset.php');
    wp_enqueue_script(
      'jobs-meta-block',
      trailingslashit(PL_JOBS_DIR) . '/includes/post/meta-boxes/jobs-meta/build/index.js',
      $editor_assets["dependencies"],
      $editor_assets["version"],
      true
    );
  }
}
