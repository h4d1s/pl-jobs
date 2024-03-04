<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

if (class_exists('PL_Jobs_Application_Meta_Box_Publish')) {
  return;
}

class PL_Jobs_Application_Meta_Box_Publish
{
  public static function init() {
    add_action("add_meta_boxes", array(__CLASS__, "add"));
    add_action("admin_enqueue_scripts", array(__CLASS__, "enqueue_script"));
  }

  /**
   * Set up and add the meta box.
   */
  public static function add() {
    add_meta_box(
      "pl_jobs_application_publish_box",
      __("Publish", "pixel-labs"),
      array(__CLASS__, "html"),
      "pl_job_application",
      "side",
      "low"
    );
  }

  /**
   * Display the meta box HTML to the user.
   *
   * @param \WP_Post $post   Post object.
   */
  public static function html( $post ) {
    if(PL_Jobs_Application_CPT::POST_TYPE !== $post->post_type) {
      return;
    }

    $country = array();
    $geolocation = maybe_unserialize(get_post_meta($post->ID, "_pl_jobs_application_geolocation", true));
    if(!empty($geolocation)) {
      $country["flag_url"] = PL_Jobs_Utils::get_flag_url($geolocation["countryCode"]);
      $country["name"] = $geolocation["country"];
    }
    $submitted_date = get_the_date("Y-m-d H:i", $post->ID);
    $ip = get_post_meta($post->ID, '_pl_jobs_application_ip', true);

    $job_id = intval(get_post_meta($post->ID, "_pl_jobs_application_job_id", true));
    $job_title = get_the_title($job_id);

    $selected_post_status = $post->post_status;
    $selected_post_status_object = get_post_status_object( $selected_post_status );

    $args = array(
      "post" => $post,
      "country" => $country,
      "submitted_date" => $submitted_date,
      "ip" => $ip,
      "job_id" => $job_id,
      "job_title" => $job_title,
      "selected_post_status" => $selected_post_status,
      "selected_post_status_object" => $selected_post_status_object
    );
    load_template(PL_JOBS_DIR . 'includes/application/views/html-meta-box-publish.php', true, $args);
  }

  public static function enqueue_script() {
    wp_register_script("pl-jobs-application-meta-box-data", "", array("jquery"), "", true);
    wp_enqueue_script("pl-jobs-application-meta-box-data");

    $inline_script = <<<JS
    (function( $ ) {
      "use strict";

      $(document).ready(function() {
        var \$postStatusSelect = $('#post-status-select');
        \$postStatusSelect.find('.save-post-status').on('click', function(e) {
          $("#post-status-display").text(\$postStatusSelect.find("option:selected").text());
        });
      });
    })(jQuery);
    JS;
    wp_add_inline_script("pl-jobs-application-meta-box-data", $inline_script);
  }
}
