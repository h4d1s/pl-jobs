<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://
 * @since      1.0.0
 *
 * @package    Pl_Jobs
 * @subpackage Pl_Jobs/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Pl_Jobs
 * @subpackage Pl_Jobs/admin
 * @author     Pixel Labs <name@domain.com>
 */

if (class_exists('PL_Jobs_Post_Query')) {
  return;
}

class PL_Jobs_Post_Query
{
  public static function init() {
    add_filter('query_vars', array(__CLASS__, 'add_query_vars_filter'));
  }

  public static function add_query_vars_filter( $vars ) {
    $vars[] = "pl_s";
    $vars[] = "pl_date";
    $vars[] = "pl_page";
    $vars[] = "pl_category";
    $vars[] = "pl_type";
    $vars[] = "pl_location";
    $vars[] = "pl_date";

    return $vars;
  }

  public static function jobs_query( &$q ) {
    $args = array();
    $s = get_query_var('pl_s', '');
    $categories = get_query_var('pl_category', '');
    $date = get_query_var('pl_date', '');
    $types = get_query_var('pl_type', '');
    $locations = get_query_var('pl_location', '');
    $current_page = get_query_var('pl_page', '');

    $args['post_type'] = PL_Jobs_Post_CPT::POST_TYPE;
    $args['post_status'] = 'publish';
    $args['posts_per_page'] = 3;
    $args['orderby'] = 'date';
    $args['order'] = 'DESC';
    $args['paged'] = $current_page;

    if(!empty($s)) {
      $args['s'] = $s;
    }

    if(!empty($date)) {
      $date_query = array();

      switch($date) {
        case "last-day":
          $date_query = array(
            'year'  => date( 'Y' ),
            'month' => date( 'm' ),
            'day'   => date( 'd' ),
          );
          break;
        case "last-three-days":
          $date_query = array(
            'year'  => date( 'Y' ),
            'month' => date( 'm' ),
            'day'   => date( 'd', strtotime( "-3 days" ) ),
          );
          break;
        case "last-week":
          $date_query = array(
            'year' => date( 'Y' ),
            'week' => date( 'W' ),
          );
          break;
        case "last-month":
          $date_query = array(
            'year'  => date( 'Y' ),
            'month' => date( 'm' ),
          );
          break;
      }

      $args['date_query'] = $date_query;
    }


    $tax_query =  $q->get('tax_query', []);
    $tax_query_relation = $q->get('tax_query');
    if ( ! empty( $tax_query_relation ) ) {
      $tax_query['relation'] = 'AND';
      $tax_query = array_merge( $tax_query_relation, $tax_query );
    }

    if(!empty($categories)) {
      array_push($tax_query, array(
        'taxonomy' => 'pl_job_post_category',
        'field'    => 'term_id',
        'terms'    => $categories,
      ));
    }
    if(!empty($locations)) {
      array_push($tax_query, array(
        'taxonomy' => 'pl_job_post_location',
        'field'    => 'term_id',
        'terms'    => $locations,
      ));
    }
    if(!empty($types)) {
      array_push($tax_query, array(
        'taxonomy' => 'pl_job_post_type',
        'field'    => 'term_id',
        'terms'    => $types,
      ));
    }

    if(!empty($tax_query)) {
      if(count($tax_query) > 1) {
        $tax_query['relation'] = 'AND';
      }
      $args['tax_query'] = $tax_query;
    }

    return $args;
  }
}
