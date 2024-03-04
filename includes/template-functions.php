<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if (! function_exists( 'pl_get_template_part' ) ) {
  function pl_get_template_part( $slug, $name = null, $load = true ) {
    return PL_Jobs_Template_Loader::get_template_part( $slug, $name, $load );
  }
}

if (! function_exists( 'pl_display_pagination' ) ) {
  function pl_display_pagination() {
    pl_get_template_part('global/pagination');
  }
}

if (! function_exists( 'pl_display_filters' ) ) {
  function pl_display_filters() {
    pl_get_template_part('global/job-filters');
  }
}

if (! function_exists( 'pl_get_object_terms' ) ) {
  function pl_get_object_terms( $object_id, $taxonomy, $field = null, $index_key = null ) {
    $terms = get_the_terms( $object_id, $taxonomy );
    if ( ! $terms || is_wp_error( $terms ) ) {
      return array();
    }
    return is_null( $field ) ? $terms : wp_list_pluck( $terms, $field, $index_key );
  }
}

if (! function_exists( 'pl_display_job_meta' ) ) {
  function pl_display_job_meta() {
    pl_get_template_part('global/job-meta');
  }
}

if (! function_exists( 'pl_body_classes' ) ) {
  function pl_body_classes( $classes ) {
    $classes[] = 'pl-jobs';

    return $classes;
  }
}

if (! function_exists( 'pl_display_post_nav' ) ) {
  function pl_display_post_nav() {
    echo the_posts_pagination();
  }
}

if (! function_exists( 'pl_display_total_count' ) ) {
  function pl_display_total_count() {
    pl_get_template_part( 'global/total-count' );
  }
}

if ( ! function_exists( 'pl_job_post_single' ) ) {
  function pl_job_post_single() {
    pl_get_template_part( 'loop/job-post-single' );
  }
}

if ( ! function_exists( 'pl_result_count' ) ) {
  function pl_result_count() {
    pl_get_template_part( 'loop/result-count' );
  }
}

if ( ! function_exists( 'pl_search_form' ) ) {
  function pl_search_form() {
    pl_get_template_part( 'global/job-search-form' );
  }
}

if ( ! function_exists( 'pl_display_single_header' ) ) {
  function pl_display_single_header() {
    pl_get_template_part( 'single-job/header' );
  }
}

if ( ! function_exists( 'pl_display_form_apply' ) ) {
  function pl_display_form_apply() {
    pl_get_template_part( 'single-job/form-apply' );
  }
}

if ( ! function_exists( 'pl_display_single_job_meta' ) ) {
  function pl_display_single_job_meta() {
    pl_get_template_part( 'single-job/meta' );
  }
}

if ( ! function_exists( 'pl_display_date' ) ) {
  function pl_display_date() {
    echo get_the_date('d/m/Y');
  }
}

if ( ! function_exists( 'pl_display_edit_post_link' ) ) {
  function pl_display_edit_post_link() {
    edit_post_link(
      __( 'Edit this job', 'pixel-labs' ),
      '',
      '',
      '',
      ''
    );
  }
}