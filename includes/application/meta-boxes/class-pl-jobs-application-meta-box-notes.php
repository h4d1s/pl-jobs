<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

if (class_exists('PL_Jobs_Application_Meta_Box_Notes')) {
  return;
}

class PL_Jobs_Application_Meta_Box_Notes
{
  public static function init() {
    add_action("add_meta_boxes", array(__CLASS__, "add"));
    add_action("save_post", array(__CLASS__, "save"));
  }

  /**
   * Set up and add the meta box.
   */
  public static function add() {
    add_meta_box(
      "pl_jobs_application_notes_box",
      __("Notes", "pixel-labs"),
      array(__CLASS__, "html"),
      "pl_job_application",
      "advanced",
      "high"
    );
  }

  public static function save($post_id) {
    if (!array_key_exists("pl_jobs_application_notes", $_POST)) {
      return;
    }

    update_post_meta(
      $post_id,
      "_pl_jobs_application_notes",
      sanitize_textarea_field($_POST["pl_jobs_application_notes"])
    );
  }

  /**
   * Display the meta box HTML to the user.
   *
   * @param \WP_Post $post   Post object.
   */
  public static function html( $post ) {
    $notes = get_post_meta($post->ID, "_pl_jobs_application_notes", true);
    wp_editor($notes, "pl_jobs_application_notes");
  }
}
