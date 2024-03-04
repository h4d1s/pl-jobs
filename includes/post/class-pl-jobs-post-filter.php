<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if (class_exists('PL_Jobs_Post_Filter')) {
  return;
}

class PL_Jobs_Post_Filter
{
  public static function init() {
    add_action( 'admin_post_pl-jobs-filter', array(__CLASS__, 'filter') );
    add_action( 'admin_post_nopriv_pl-jobs-filter', array(__CLASS__, 'filter') );
  }

  public static function filter() {
    $nonce = $_GET['_pl_jobs_filter_nonce_field'];
    $remove_query_keys = array("_pl_jobs_filter_nonce_field", "_wp_http_referer");
    $url = remove_query_arg($remove_query_keys, wp_unslash($_GET["_wp_http_referer"]));

    if ( !wp_verify_nonce($nonce, "pl-jobs-filter") ) {
      wp_redirect($url);
      return;
    }

    if(isset($_GET["pl_s"]) && !empty($_GET["pl_s"])) {
      $q = sanitize_text_field($_GET["pl_s"]);

      $url = add_query_arg([
        "pl_s" => $q
      ], $url);
    } else {
      $url = remove_query_arg("pl_s", $url);
    }

    if(isset($_GET["pl_jobs_filter_category"]) && !empty($_GET["pl_jobs_filter_category"])) {
      $filter_category = $_GET["pl_jobs_filter_category"];
      if(!is_array($filter_category)) {
        $filter_category = array($_GET["pl_jobs_filter_category"]);
      }

      $category = $filter_category;
      $url = add_query_arg([
        "pl_category" => $category
      ], $url);
    } else {
      $url = remove_query_arg("pl_category", $url);
    }

    if(isset($_GET["pl_jobs_filter_contract_type"]) && !empty($_GET["pl_jobs_filter_contract_type"])) {
      $filter_contract_type = $_GET["pl_jobs_filter_contract_type"];
      if(!is_array($filter_contract_type)) {
        $filter_contract_type = array($_GET["pl_jobs_filter_contract_type"]);
      }
      $type = $filter_contract_type;

      $url = add_query_arg([
        "pl_type" => $type
      ], $url);
    } else {
      $url = remove_query_arg("pl_type", $url);
    }

    if(isset($_GET["pl_jobs_filter_location"]) && !empty($_GET["pl_jobs_filter_location"])) {
      $filter_location = $_GET["pl_jobs_filter_location"];
      if(!is_array($filter_location)) {
        $filter_location = array($_GET["pl_jobs_filter_location"]);
      }
      $location = $filter_location;

      $url = add_query_arg([
        "pl_location" => $location
      ], $url);
    } else {
      $url = remove_query_arg("pl_location", $url);
    }

    if(isset($_GET["pl_jobs_filter_publication_date"]) && !empty($_GET["pl_jobs_filter_publication_date"])) {
      $date = $_GET["pl_jobs_filter_publication_date"];

      $url = add_query_arg([
        "pl_date" => $date
      ], $url);
    } else {
      $url = remove_query_arg("pl_date", $url);
    }

    wp_redirect($url);
  }
}
