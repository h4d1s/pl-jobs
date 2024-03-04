<?php

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

if (class_exists('PL_Jobs_Application_Posts')) {
  return;
}

class PL_Jobs_Application_Posts
{
  public static function init()
  {
    self::init_hooks();
  }

  public static function init_hooks()
  {
    add_filter("manage_" . PL_Jobs_Application_CPT::POST_TYPE . "_posts_columns", array(__CLASS__, "post_columns"));
    add_action("manage_" . PL_Jobs_Application_CPT::POST_TYPE . "_posts_custom_column", array(__CLASS__, "post_column"), 10, 2);

    add_filter("views_edit-" . PL_Jobs_Application_CPT::POST_TYPE, array(__CLASS__, "modify_quicklinks"));
    add_filter("post_date_column_status", array(__CLASS__, "post_date_column_status"), 10, 4);
    add_filter("post_row_actions", array(__CLASS__, "modify_row_actions"), 10, 2);
    add_filter("bulk_actions-edit-" . PL_Jobs_Application_CPT::POST_TYPE, array(__CLASS__, "modify_bulk_actions"));
    add_filter("handle_bulk_actions-edit-" . PL_Jobs_Application_CPT::POST_TYPE, array(__CLASS__, "handle_bulk_actions_statuses"), 10, 3);

    add_action("admin_menu", array(__CLASS__, "remove_metaboxes"));
  }

  public static function post_columns($post_columns)
  {
    $post_columns = [
      "cb"      => $post_columns["cb"],
      "title"   => __("Name", "pixel-labs"),
      "email"   => __("Email", "pixel-labs"),
      "phone"   => __("Phone", "pixel-labs"),
      "job"     => __("Job", "pixel-labs"),
      "status"  => __("Status", "pixel-labs"),
      "country" => __("Country", "pixel-labs"),
      "date"    => __("Date Submitted", "pixel-labs"),
    ];
    return $post_columns;
  }

  public static function post_column($column_name, $post_id)
  {
    if ("email" === $column_name) {
      echo get_post_meta($post_id, "_pl_jobs_application_email", true);
    }
    if ("phone" === $column_name) {
      echo get_post_meta($post_id, "_pl_jobs_application_phone", true);
    }
    if ("job" === $column_name) {
      $job_id = get_post_meta($post_id, "_pl_jobs_application_job_id", true);
      echo get_the_title($job_id);
    }
    if ("status" === $column_name) {
      $selected_post_status_object = get_post_status_object(get_post_status($post_id));
      echo $selected_post_status_object->label;
    }
    if ("country" === $column_name) {
      $geolocation = maybe_unserialize(get_post_meta($post_id, "_pl_jobs_application_geolocation", true));
      if (!empty($geolocation)) {
        echo wp_sprintf("<img src='%s' width='24' />", PL_Jobs_Utils::get_flag_url($geolocation["countryCode"]));
      } else {
        echo __("N/A", "pixel-labs");
      }
    }
  }

  public static function modify_quicklinks($views)
  {
    unset($views["publish"]);
    return $views;
  }

  public static function post_date_column_status($status, $post, $column_name, $mode)
  {
    return "";
  }

  public static function modify_row_actions($actions, $post)
  {
    if (PL_Jobs_Application_CPT::POST_TYPE !== $post->post_type) {
      return $actions;
    }

    unset($actions["inline hide-if-no-js"]);

    $actions["edit"] = wp_sprintf(
      "<a href='%s'>%s</a>",
      get_edit_post_link($post->id),
      __("View", "pixel-labs")
    );

    return $actions;
  }

  public static function modify_bulk_actions($actions)
  {
    unset($actions["edit"]);
    unset($actions["trash"]);

    $actions["reject"] = __("Reject", "pixel-labs");
    $actions["hire"] = __("Hire", "pixel-labs");

    return $actions;
  }

  public static function handle_bulk_actions_statuses($sendback, $doaction, $items)
  {
    $post_status = "";

    switch ($doaction) {
      case "reject":
        $post_status = "pl_rejected";
        break;
      case "hire":
        $post_status = "pl_hired";
        break;
    }

    foreach($items as $item_id) {
      $post_update = array(
        'ID'         => $item_id,
        'post_status' => $post_status
      );
      wp_update_post($post_update);
    }

    return $sendback;
  }

  public static function remove_metaboxes()
  {
    remove_meta_box('submitdiv', PL_Jobs_Application_CPT::POST_TYPE, 'side');
  }
}
