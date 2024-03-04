<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

if (class_exists('PL_Jobs_Application_Meta_Box_Applicant')) {
  return;
}

class PL_Jobs_Application_Meta_Box_Applicant
{
  public static function init() {
    add_action("add_meta_boxes", array(__CLASS__, "add"));
  }

  /**
   * Set up and add the meta box.
   */
  public static function add() {
    add_meta_box(
      "pl_jobs_application_applicant_box",
      __("Applicant", "pixel-labs"),
      array(__CLASS__, "html"),
      "pl_job_application",
      "advanced",
      "high"
    );
  }

  /**
   * Display the meta box HTML to the user.
   *
   * @param \WP_Post $post   Post object.
   */
  public static function html( $post ) {
    $email = get_post_meta($post->ID, '_pl_jobs_application_email', true);
    $phone = get_post_meta($post->ID, '_pl_jobs_application_phone', true);
    $cv_attachment_id = get_post_meta($post->ID, '_pl_jobs_application_cv_attachment_id', true);
    $url = wp_get_attachment_url( $cv_attachment_id );
    $cover_letter = get_the_content("", "", $post->ID);

    $args = array(
      "email" => $email,
      "phone" => $phone,
      "cv_attachment_id" => $cv_attachment_id,
      "url" => $url,
      "cover_letter" => $cover_letter,
    );
    load_template(PL_JOBS_DIR . 'includes/application/views/html-meta-box-applicant.php', true, $args);
  }
}
