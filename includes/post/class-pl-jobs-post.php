<?php

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

if (class_exists('PL_Jobs_Post')) {
  return;
}

class PL_Jobs_Post
{
  public static function init()
  {
    self::load_dependencies();
    self::init_dependencies();
    self::init_hooks();
  }

  private static function load_dependencies()
  {
    require_once PL_JOBS_DIR . 'includes/post/class-pl-jobs-post-cpt.php';
    require_once PL_JOBS_DIR . 'includes/post/class-pl-jobs-post-filter.php';
    require_once PL_JOBS_DIR . 'includes/post/meta-boxes/class-pl-jobs-post-meta-box-meta.php';
    require_once PL_JOBS_DIR . 'includes/post/meta-boxes/class-pl-jobs-post-meta-box-status.php';
    require_once PL_JOBS_DIR . 'includes/post/class-pl-jobs-post-shortcode.php';
    require_once PL_JOBS_DIR . 'includes/post/class-pl-jobs-post-query.php';
  }

  public static function init_dependencies()
  {
    PL_Jobs_Post_CPT::init();
    PL_Jobs_Post_Query::init();
    PL_Jobs_Post_Filter::init();
    PL_Jobs_Post_Meta_Box_Meta::init();
    PL_Jobs_Post_Meta_Box_Status::init();
    PL_Jobs_Post_Shortcode::init();
  }

  private static function init_hooks()
  {
    add_filter('the_content', array(__CLASS__, 'single_template_modify_content'));
    add_action("init", array(__CLASS__, "create_page"));
    add_action("wp", array(__CLASS__, "ensure_views_counter"));

    add_action("delete_post", array(__CLASS__, "delete_applications"), 10, 2);

    add_action("restrict_manage_posts", array(__CLASS__, "add_filter_status_dropdown"), 10, 2);
    add_action("pre_get_posts", array(__CLASS__, "filter_statuses"));
  }

  public static function ensure_views_counter()
  {
    if (!is_singular(PL_Jobs_Post_CPT::POST_TYPE)) {
      return false;
    }

    global $post;
    $date = new DateTime();
    $date->add(new DateInterval("P1Y"));

    if (!isset($_COOKIE["pl_job_post_view_counter"])) {
      $posts = array($post->ID);
      setcookie("pl_job_post_view_counter", maybe_serialize($posts), $date->getTimestamp(), "/");
    } else {
      $posts = maybe_unserialize($_COOKIE["pl_job_post_view_counter"]);

      if (!in_array($post->ID, $posts)) {
        array_push($posts, $post->ID);
        setcookie("pl_job_post_view_counter", maybe_serialize($posts), $date->getTimestamp(), "/");

        $post_views = get_post_meta($post->ID, "_pl_job_post_view_counter", true);
        $post_views = (!empty($post_views) ? $post_views++ : 1);

        update_post_meta($post->ID, "_pl_job_post_view_counter", $post_views);
      }
    }
  }

  public static function create_page()
  {
    global $pl_jobs;
    if (!$pl_jobs->options->has("jobs_page_id")) {
      $user_id = get_current_user_id();
      $jobs_page = array(
        'post_title'    => 'Jobs',
        'post_content'  => '[pl_jobs]',
        'post_status'   => 'publish',
        'post_author'   => $user_id,
        'post_type'     => 'page'
      );
      $page_id = wp_insert_post($jobs_page);

      if (is_wp_error($page_id)) {
        // TODO: Error
        return;
      }

      $pl_jobs->options->set("jobs_page_id", $page_id);
    }
  }

  public static function delete_page()
  {
    global $pl_jobs;
    if ($pl_jobs->options->has("jobs_page_id")) {
      $result = wp_delete_post("jobs_page_id", true);

      if ($result) {
        // TODO: Error
      }
    }
  }

  public static function single_template_modify_content( $content ) {
    if ( is_singular( PL_Jobs_Post_CPT::POST_TYPE ) ) {
      ob_start();
      pl_display_single_job_meta();
      $meta_html = ob_get_clean();
      $content = $meta_html . $content;

      ob_start();
      pl_display_form_apply();
      $form_html = ob_get_clean();
      $content .= $form_html;
    }

    return $content;
  }

  public static function get_applicants_count($job_id)
  {
    $args = array(
      "post_per_page"    => 1,
      "meta_key"         => "_pl_jobs_application_job_id",
      "meta_value"       => $job_id,
      "post_type"        => "pl_job_application",
      "fields"           => "ids",
      "order"            => "DESC",
      "orderby"          => "date"
    );
    $query = new WP_Query($args);
    $found_posts = $query->found_posts;
    wp_reset_postdata();

    return $found_posts;
  }

  public static function get_post_views($post_id)
  {
    $post_views = get_post_meta($post_id, "_pl_job_post_view_counter", true);
    return (!empty($post_views) ? $post_views++ : 0);
  }

  public static function get_last_application_date($job_id)
  {
    global $wpdb;
    $application_date = "";

    $sql = $wpdb->prepare(
      "
      SELECT p.ID FROM $wpdb->posts p
      LEFT JOIN $wpdb->postmeta pm ON (p.ID = pm.post_id)
      WHERE pm.meta_key = %s
        AND pm.meta_value = %d
      ORDER BY p.post_date DESC
      LIMIT 1;
    ",
      "_pl_jobs_application_job_id",
      $job_id
    );
    $application_id = $wpdb->get_var($sql);

    if (!is_null($application_id)) {
      $application_date = get_the_date(DateTime::ATOM, intval($application_id));
    }

    return PL_Jobs_Utils::time_ago($application_date);
  }

  public static function is_job_active($closing_date_string)
  {
    $now = new DateTime();
    $closing_date = new DateTime($closing_date_string);
    return $closing_date >= $now;
  }

  public static function delete_applications($post_id, $post)
  {
    $args = array(
      "post_per_page"    => 1,
      "meta_key"         => "_pl_jobs_application_job_id",
      "meta_value"       => $post_id,
      "post_type"        => "pl_job_application",
      "fields"           => "ids",
      "order"            => "DESC",
      "orderby"          => "date"
    );
    $posts = get_posts($args);
    if (!empty($posts)) {
      foreach ($posts as $post) {
        wp_delete_post($post->ID, true);
      }
    }
  }

  public static function add_filter_status_dropdown($post_type, $which)
  {
    if (PL_Jobs_Post_CPT::POST_TYPE !== $post_type) {
      return;
    }
    if ("top" !== $which) {
      return;
    }
  ?>
  <select id="filter-by-status" name="status">
    <option selected="selected" value="-1">
      <?php esc_html_e("All Statuses", "pixel-labs"); ?>
    </option>
    <option value="active" <?php if (isset($_GET["status"])) : selected($_GET["status"], "active");
                            endif; ?>>
      <?php esc_html_e("Active", "pixel-labs"); ?>
    </option>
    <option value="expired" <?php if (isset($_GET["status"])) : selected($_GET["status"], "expired");
                            endif; ?>>
      <?php esc_html_e("Expired", "pixel-labs"); ?>
    </option>
  </select>
  <?php
    $args = array(
      "name" => "location",
      "show_option_none" => __("All Locations", "pixel-labs"),
      "id" => "filter-by-location",
      "taxonomy" => "pl_job_post_location",
      "selected" => (isset($_GET["location"]) && !empty($_GET["location"]) ? $_GET["location"] : false)
    );
    wp_dropdown_categories($args);

    $args = array(
      "name" => "category",
      "show_option_none" => __("All Categories", "pixel-labs"),
      "id" => "filter-by-category",
      "taxonomy" => "pl_job_post_category",
      "selected" => (isset($_GET["category"]) && !empty($_GET["category"]) ? $_GET["category"] : false)
    );
    wp_dropdown_categories($args);

    $args = array(
      "name" => "type",
      "show_option_none" => __("All Types", "pixel-labs"),
      "id" => "filter-by-type",
      "taxonomy" => "pl_job_post_type",
      "selected" => (isset($_GET["type"]) && !empty($_GET["type"]) ? $_GET["type"] : false)
    );
    wp_dropdown_categories($args);
  }

  public static function filter_statuses($query)
  {
    global $pagenow;

    if (
      !is_admin()
      && PL_Jobs_Post_CPT::POST_TYPE !== $query->post_type
      && !$query->is_main_query()
      && "edit.php" !== $pagenow
    ) {
      return $query;
    }

    $meta_query = array();
    $tax_query = array();

    if (isset($_GET["status"]) && !empty($_GET["status"])) {
      $compare = ($_GET["status"] === "active" ? ">=" : "<=");
      $compare_no_closing_date = ($_GET["status"] === "active" ? "NOT EXISTS" : "EXISTS");
      $now = new DateTime("now");

      $meta_query[] = array(
        "relation" => "OR",
        array(
          "key"     => "_pl_jobs_post_closing_date",
          "compare" => $compare_no_closing_date,
        ),
        array(
          "key"     => "_pl_jobs_post_closing_date",
          "value"   => $now->format("Y-m-d"),
          "compare" => $compare,
          "type"    => "DATE"
        )
      );
    }

    if (isset($_GET["location"]) && !empty($_GET["location"])) {
      $tax_query[] = array(
        'taxonomy' => "pl_job_post_location",
        'field'    => 'term_id',
        'terms'    => $_GET["location"],
      );
    }

    if (isset($_GET["category"]) && !empty($_GET["category"])) {
      $tax_query[] = array(
        'taxonomy' => "pl_job_post_category",
        'field'    => 'term_id',
        'terms'    => $_GET["category"],
      );
    }

    if (isset($_GET["type"]) && !empty($_GET["type"])) {
      $tax_query[] = array(
        'taxonomy' => "pl_job_post_type",
        'field'    => 'term_id',
        'terms'    => $_GET["type"],
      );
    }

    $query->set("meta_query", $meta_query);
    $query->set("tax_query", $tax_query);
  }
}
