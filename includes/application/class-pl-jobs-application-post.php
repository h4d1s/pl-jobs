<?php

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

if (class_exists('PL_Jobs_Application_Post')) {
  return;
}

class PL_Jobs_Application_Post
{
  public static function init()
  {
    self::init_hooks();
  }

  public static function init_hooks()
  {
    add_action("admin_post_pl_jobs_add_application", array(__CLASS__, "add_application"));
    add_action("admin_post_nopriv_pl_jobs_add_application", array(__CLASS__, "add_application"));
    add_action("before_delete_post", array(__CLASS__, "before_delete"), 10, 2);
    add_action("admin_head", array(__CLASS__, "change_title"));
  }

  public static function add_application()
  {
    check_admin_referer( 'pl_jobs_add_application', '_pl_jobs_add_application_nonce' );

    $error = new WP_Error();

    // Current job post ID
    $job_post_id_field_name = "pl_jobs_post_id";
    $job_post_id = $_POST[$job_post_id_field_name];
    if (isset($job_post_id) && !empty($job_post_id)) {
      $job_post_id = filter_var($job_post_id, FILTER_VALIDATE_INT);
    } else {
      $error->add($job_post_id_field_name, __("There was an error applying.", "pixel-labs"));
    }

    // Fullname
    $fullname_field_name = "pl_jobs_fullname";
    $fullname = $_POST[$fullname_field_name];
    if (isset($fullname) && !empty($fullname)) {
      $fullname = sanitize_text_field($fullname);
    } else {
      $error->add($fullname_field_name, __("Fullname is required.", "pixel-labs"));
    }

    // Email
    $email_field_name = "pl_jobs_email";
    $email = $_POST[$email_field_name];
    if (isset($email) && !empty($email)) {
      if (is_email($email)) {
        $email = sanitize_email($email);

        $args = [
          'numberposts'      => 1,
          'meta_key'         => '_pl_jobs_application_email',
          'meta_value'       => $email,
          'post_type'        => 'pl_job_application',
        ];
        $query = new WP_Query($args);
        $is_applicant_registered = $query->found_posts;
        wp_reset_postdata();

        if ($is_applicant_registered > 0) {
          $error->add($email_field_name, __("Already registered.", "pixel-labs"));
        }
      } else {
        $error->add($email_field_name, __("Email is not valid.", "pixel-labs"));
      }
    } else {
      $error->add($email_field_name, __("Email is required.", "pixel-labs"));
    }

    // Phone
    $phone_field_name = "pl_jobs_phone";
    $phone = $_POST[$phone_field_name];
    if (isset($phone) && PL_Jobs_Validator::is_phone($phone)) {
      $phone = sanitize_text_field($phone);
    } else {
      $error->add($phone_field_name, __("Phone is not valid.", "pixel-labs"));
    }

    // Cover letter
    $cover_letter_field_name = "pl_jobs_cover_letter";
    $cover_letter = $_POST[$cover_letter_field_name];
    if (isset($cover_letter) && !empty($cover_letter)) {
      $cover_letter = sanitize_textarea_field($cover_letter);
    } else {
      $error->add($cover_letter_field_name, __("Cover letter is required.", "pixel-labs"));
    }

    // CV attachment
    $cv_field_name = "pl_jobs_cv";
    $cv_attachment = $_FILES[$cv_field_name];
    $cv_attachment_id = -1;
    if (isset($cv_attachment) && !empty($cv_attachment)) {
      try {
        $upload = PL_Upload::upload($cv_attachment);
        $attachment = [
          'guid'            => $upload["url"],
          'post_mime_type'  => $upload["type"],
          'post_title'      => preg_replace('/\.[^.]+$/', '', basename($upload["file"])),
          'post_content'    => '',
          'post_status'     => 'inherit'
        ];
        $attachment_id = wp_insert_attachment($attachment, $upload["file"]);

        if (!is_wp_error($attachment_id)) {
          $cv_attachment_id = $attachment_id;
        } else {
          $error->add($cv_field_name, __("There was an error adding CV file.", "pixel-labs"));
        }
      } catch (\Exception $e) {
        $error->add($cv_field_name, $e->getMessage());
      }
    } else {
      $error->add($cv_field_name, __("CV is required.", "pixel-labs"));
    }

    unset($_SESSION["form_apply"]);
    $_SESSION["form_apply"]["data"] = [
      "pl_jobs_fullname" => $fullname,
      "pl_jobs_email" => $email,
      "pl_jobs_phone" => $phone,
      "pl_jobs_cover_letter" => $cover_letter,
      "pl_jobs_cv" => $cv_attachment_id
    ];
    foreach ($error->errors as $k => $e) {
      $_SESSION["form_apply"]["errors"][$k] = $e;
    }

    if (!empty($error->get_error_codes())) {
      wp_safe_redirect(wp_get_referer());
      return;
    }

    $application_post = [
      'post_title'      => wp_strip_all_tags($fullname),
      'post_content'    => $cover_letter,
      'post_status'     => 'pl_applied',
      'post_type'       => 'pl_job_application',
      'post_author'     => 1,
      'meta_input'      => [
        '_pl_jobs_application_job_id'           => $job_post_id,
        '_pl_jobs_application_email'            => $email,
        '_pl_jobs_application_phone'            => $phone,
        '_pl_jobs_application_cv_attachment_id' => $cv_attachment_id,
        '_pl_jobs_application_ip'               => PL_Geo_IP::get_ip(),
        '_pl_jobs_application_geolocation'      => maybe_serialize(PL_Geo_IP::geolocate_ip())
      ]
    ];
    $post_id = wp_insert_post($application_post);

    if (is_wp_error($post_id)) {
      $error->add("pl_jobs_cv_create", __("There was an error applying.", "pixel-labs"));
      wp_safe_redirect(wp_get_referer());
      return;
    }

    unset($_SESSION["form_apply"]);
    $_SESSION["form_apply"]["success"] = 1;
    wp_safe_redirect(wp_get_referer());
  }

  public static function before_delete($postid, $post)
  {
    WP_Filesystem();
    global $wp_filesystem;
    $cv_attachment_id = get_post_meta($postid, '_pl_jobs_application_cv_attachment_id', true);
    $cv_attachment_path = get_attached_file($cv_attachment_id);

    if ($wp_filesystem->exists($cv_attachment_path)) {
      $wp_filesystem->delete($cv_attachment_path);
    }

    wp_delete_attachment($cv_attachment_id, true);
  }

  public static function change_title()
  {
    global $post, $title;

    if (!is_admin()) {
      return;
    }

    if (!isset($post)) {
      return;
    }

    if (PL_Jobs_Application_CPT::POST_TYPE !== $post->post_type) {
      return;
    }

    $title = get_the_title($post->ID);
  }
}
