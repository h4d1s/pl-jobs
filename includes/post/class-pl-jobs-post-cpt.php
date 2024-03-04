<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if (class_exists('PL_Jobs_Post_CPT')) {
  return;
}

class PL_Jobs_Post_CPT
{
  const POST_TYPE = "pl_job_post";

  public static function init() {
    add_action("init", array(__CLASS__, "register"));
    add_action("save_post_". self::POST_TYPE, array(__CLASS__, "save"), 10, 3);

    add_filter("manage_". self::POST_TYPE ."_posts_columns", array(__CLASS__, "post_columns"));
    add_action("manage_". self::POST_TYPE ."_posts_custom_column", array(__CLASS__, "post_column"), 10, 2);
  }

  public static function save($post_ID, $post, $update) {
    if(wp_is_post_autosave($post_ID)) {
      return;
    }
  }

  public static function unregister() {
    if(!post_type_exists(self::POST_TYPE)) {
      return;
    }

    unregister_post_type(self::POST_TYPE);
  }

  public static function register() {
    if (post_type_exists(self::POST_TYPE)) {
      return;
    }

    $singular = _x( 'Job', 'Job Singular Name', 'pixel-labs' );
    $plural = _x( 'Jobs', 'Job General Name', 'pixel-labs' );

    $labels = array(
      'name'                  => $plural,
      'singular_name'         => $singular,
      'menu_name'             => $plural,
      'name_admin_bar'        => $singular,
      'archives'              => wp_sprintf(__( '%s Archives', 'pixel-labs' ), $singular),
      'attributes'            => wp_sprintf(__( '%s Attributes', 'pixel-labs' ), $singular),
      'parent_item_colon'     => wp_sprintf(__( 'Parent %s:', 'pixel-labs' ), $singular),
      'all_items'             => wp_sprintf(__( 'All %s', 'pixel-labs' ), $plural),
      'add_new_item'          => wp_sprintf(__( 'Add New %s', 'pixel-labs' ), $singular),
      'add_new'               => __( 'Add New', 'pixel-labs' ),
      'new_item'              => wp_sprintf(__( 'New %s', 'pixel-labs' ), $singular),
      'edit_item'             => wp_sprintf(__( 'Edit %s', 'pixel-labs' ), $singular),
      'update_item'           => wp_sprintf(__( 'Update %s', 'pixel-labs' ), $singular),
      'view_item'             => wp_sprintf(__( 'View %s', 'pixel-labs' ), $singular),
      'view_items'            => wp_sprintf(__( 'View %s', 'pixel-labs' ), $plural),
      'search_items'          => wp_sprintf(__( 'Search %s', 'pixel-labs' ), $singular),
      'not_found'             => __( 'Not found', 'pixel-labs' ),
      'not_found_in_trash'    => __( 'Not found in Trash', 'pixel-labs' ),
      'featured_image'        => __( 'Featured Image', 'pixel-labs' ),
      'set_featured_image'    => __( 'Set featured image', 'pixel-labs' ),
      'remove_featured_image' => __( 'Remove featured image', 'pixel-labs' ),
      'use_featured_image'    => __( 'Use as featured image', 'pixel-labs' ),
      'insert_into_item'      => wp_sprintf(__( 'Insert into %s', 'pixel-labs' ), $singular),
      'uploaded_to_this_item' => wp_sprintf(__( 'Uploaded to this %s', 'pixel-labs' ), $singular),
      'items_list'            => wp_sprintf(__( '%s list', 'pixel-labs' ), $plural),
      'items_list_navigation' => wp_sprintf(__( '%s list navigation', 'pixel-labs' ), $plural),
      'filter_items_list'     => wp_sprintf(__( 'Filter %s list', 'pixel-labs' ), $plural),
    );
    $args = array(
      'label'                 => $plural,
      'description'           => $singular,
      'labels'                => $labels,
      'supports'              => ["title", "editor", "custom-fields"],
      'taxonomies'            => ["pl_job_post_type", 'pl_job_post_category', "pl_job_post_location"],
      'hierarchical'          => false,
      'public'                => true,
      'show_ui'               => true,
      'show_in_menu'          => true,
      'menu_position'         => 30,
      'menu_icon'             => "dashicons-id",
      'show_in_admin_bar'     => true,
      'show_in_nav_menus'     => true,
      'can_export'            => true,
      'has_archive'           => false,
      'exclude_from_search'   => false,
      'publicly_queryable'    => true,
      'capability_type'       => self::POST_TYPE,
      'map_meta_cap'          => false,
      'rewrite'               => ['slug' => 'job'],
      'show_in_rest'          => true,
    );
    register_post_type(self::POST_TYPE, $args);

    self::register_taxonomies();
  }

  private static function register_taxonomies() {
    self::create_taxonomy_job_post_type();
    self::create_taxonomy_job_post_category();
    self::create_taxonomy_job_post_location();
  }

  private static function create_taxonomy_job_post_type() {
    $taxonomy = "pl_job_post_type";
    if(taxonomy_exists($taxonomy)) {
      return;
    }

    $labels = array(
      'name'                       => _x( 'Job Type', 'Taxonomy General Name', 'pixel-labs' ),
      'singular_name'              => _x( 'Job Type', 'Taxonomy Singular Name', 'pixel-labs' ),
      'menu_name'                  => __( 'Job Types', 'pixel-labs' ),
      'all_items'                  => __( 'All Job Types', 'pixel-labs' ),
      'parent_item'                => __( 'Parent Job Type', 'pixel-labs' ),
      'parent_item_colon'          => __( 'Parent Job Type:', 'pixel-labs' ),
      'new_item_name'              => __( 'New Job Type Name', 'pixel-labs' ),
      'add_new_item'               => __( 'Add New Job Type', 'pixel-labs' ),
      'edit_item'                  => __( 'Edit Job Type', 'pixel-labs' ),
      'update_item'                => __( 'Update Job Type', 'pixel-labs' ),
      'view_item'                  => __( 'View Job Type', 'pixel-labs' ),
      'separate_items_with_commas' => __( 'Separate job type with commas', 'pixel-labs' ),
      'add_or_remove_items'        => __( 'Add or remove job type', 'pixel-labs' ),
      'choose_from_most_used'      => __( 'Choose from the most used', 'pixel-labs' ),
      'popular_items'              => __( 'Popular Job Type', 'pixel-labs' ),
      'search_items'               => __( 'Search Job Type', 'pixel-labs' ),
      'not_found'                  => __( 'Not Found', 'pixel-labs' ),
      'no_terms'                   => __( 'No job type', 'pixel-labs' ),
      'items_list'                 => __( 'Job Types list', 'pixel-labs' ),
      'items_list_navigation'      => __( 'Job Types list navigation', 'pixel-labs' ),
    );
    $capabilities = array(
      "manage_terms"             => "manage_{$taxonomy}s",
      "edit_terms"               => "manage_{$taxonomy}s",
      "delete_terms"             => "manage_{$taxonomy}s",
      "assign_terms"             => "edit_" . self::POST_TYPE . "s",
    );
    $args = array(
      'labels'                     => $labels,
      'hierarchical'               => false,
      'public'                     => false,
      'has_archive'                => false,
      'show_ui'                    => true,
      'show_admin_column'          => false,
      'show_in_nav_menus'          => false,
      'show_tagcloud'              => false,
      'show_in_rest'               => true,
      'rewrite'                    => array("slug" => "type"),
      'query_var'                  => "pl_type",
      "capabilities"               => $capabilities
    );
    register_taxonomy( $taxonomy, self::POST_TYPE, $args );
    self::create_job_post_type_terms($taxonomy);
  }

  private static function create_job_post_type_terms($taxonomy) {
    $terms = array(
      "freelance"    => __("Freelance", "pixel-labs"),
      "fulltime"     => __("Fulltime", "pixel-labs"),
      "internship"   => __("Internship", "pixel-labs"),
      "part-time"    => __("Part Time", "pixel-labs"),
      "temporary"    => __("Temporary", "pixel-labs")
    );

    foreach ($terms as $slug => $term) {
      $exists = term_exists( $term, $taxonomy );
      if ( $exists == 0 && $exists == null ) {
        wp_insert_term(
          $term,
          $taxonomy,
          array(
            'slug' => $slug,
          )
        );
      }
    }
  }

  private static function create_taxonomy_job_post_category() {
    $taxonomy = "pl_job_post_category";
    $taxonomy_plural = "pl_job_post_categories";

    if(taxonomy_exists($taxonomy)) {
      return;
    }

    $labels = array(
      'name'                       => _x( 'Job Category', 'Taxonomy General Name', 'pixel-labs' ),
      'singular_name'              => _x( 'Job Category', 'Taxonomy Singular Name', 'pixel-labs' ),
      'menu_name'                  => __( 'Job Categories', 'pixel-labs' ),
      'all_items'                  => __( 'All Job Categories', 'pixel-labs' ),
      'parent_item'                => __( 'Parent Job Category', 'pixel-labs' ),
      'parent_item_colon'          => __( 'Parent Job Category:', 'pixel-labs' ),
      'new_item_name'              => __( 'New Job Category Name', 'pixel-labs' ),
      'add_new_item'               => __( 'Add New Job Category', 'pixel-labs' ),
      'edit_item'                  => __( 'Edit Job Category', 'pixel-labs' ),
      'update_item'                => __( 'Update Job Category', 'pixel-labs' ),
      'view_item'                  => __( 'View Job Category', 'pixel-labs' ),
      'separate_items_with_commas' => __( 'Separate job category with commas', 'pixel-labs' ),
      'add_or_remove_items'        => __( 'Add or remove job category', 'pixel-labs' ),
      'choose_from_most_used'      => __( 'Choose from the most used', 'pixel-labs' ),
      'popular_items'              => __( 'Popular Job Category', 'pixel-labs' ),
      'search_items'               => __( 'Search Job Category', 'pixel-labs' ),
      'not_found'                  => __( 'Not Found', 'pixel-labs' ),
      'no_terms'                   => __( 'No job category', 'pixel-labs' ),
      'items_list'                 => __( 'Job Category list', 'pixel-labs' ),
      'items_list_navigation'      => __( 'Job Category list navigation', 'pixel-labs' ),
    );
    $capabilities = array(
      "manage_terms"             => "manage_{$taxonomy_plural}",
      "edit_terms"               => "manage_{$taxonomy_plural}",
      "delete_terms"             => "manage_{$taxonomy_plural}",
      "assign_terms"             => "edit_" . self::POST_TYPE . "s",
    );
    $args = array(
      'labels'                     => $labels,
      'hierarchical'               => false,
      'public'                     => false,
      'show_ui'                    => true,
      'show_admin_column'          => false,
      'show_in_nav_menus'          => false,
      'show_tagcloud'              => false,
      'show_in_rest'               => true,
      'rewrite'                    => array("slug" => "category"),
      'query_var'                  => "pl_category",
      'capabilities'               => $capabilities,
    );
    register_taxonomy( $taxonomy, self::POST_TYPE, $args );
  }

  private static function create_taxonomy_job_post_location() {
    $taxonomy = "pl_job_post_location";
    $labels = array(
      'name'                       => _x( 'Job Location', 'Taxonomy General Name', 'pixel-labs' ),
      'singular_name'              => _x( 'Job Location', 'Taxonomy Singular Name', 'pixel-labs' ),
      'menu_name'                  => __( 'Job Locations', 'pixel-labs' ),
      'all_items'                  => __( 'All Job Locations', 'pixel-labs' ),
      'parent_item'                => __( 'Parent Job Location', 'pixel-labs' ),
      'parent_item_colon'          => __( 'Parent Job Location:', 'pixel-labs' ),
      'new_item_name'              => __( 'New Job Location Name', 'pixel-labs' ),
      'add_new_item'               => __( 'Add New Job Location', 'pixel-labs' ),
      'edit_item'                  => __( 'Edit Job Location', 'pixel-labs' ),
      'update_item'                => __( 'Update Job Location', 'pixel-labs' ),
      'view_item'                  => __( 'View Job Location', 'pixel-labs' ),
      'separate_items_with_commas' => __( 'Separate job location with commas', 'pixel-labs' ),
      'add_or_remove_items'        => __( 'Add or remove job location', 'pixel-labs' ),
      'choose_from_most_used'      => __( 'Choose from the most used', 'pixel-labs' ),
      'popular_items'              => __( 'Popular Job Location', 'pixel-labs' ),
      'search_items'               => __( 'Search Job Location', 'pixel-labs' ),
      'not_found'                  => __( 'Not Found', 'pixel-labs' ),
      'no_terms'                   => __( 'No job location', 'pixel-labs' ),
      'items_list'                 => __( 'Job Location list', 'pixel-labs' ),
      'items_list_navigation'      => __( 'Job Location list navigation', 'pixel-labs' ),
    );
    $capabilities = array(
      "manage_terms"             => "manage_{$taxonomy}s",
      "edit_terms"               => "manage_{$taxonomy}s",
      "delete_terms"             => "manage_{$taxonomy}s",
      "assign_terms"             => "edit_" . self::POST_TYPE . "s",
    );
    $args = array(
      'labels'                     => $labels,
      'hierarchical'               => false,
      'public'                     => false,
      'show_ui'                    => true,
      'show_admin_column'          => false,
      'show_in_nav_menus'          => false,
      'show_tagcloud'              => false,
      'show_in_rest'               => true,
      'rewrite'                    => array("slug" => "location"),
      'query_var'                  => "pl_location",
      'capabilities'               => $capabilities,
    );
    register_taxonomy($taxonomy, array(self::POST_TYPE), $args);
  }

  public static function post_columns($post_columns) {
    $post_columns = array(
      "cb"              => $post_columns["cb"],
      "title"           => __("Job Title", "pixel-labs"),
      "applications"    => __("Applications", "pixel-labs"),
      "views"           => __("Views", "pixel-labs"),
      "status"          => __("Status", "pixel-labs"),
    );
    return $post_columns;
  }

  public static function post_column( $column_name, $post_id ) {
    if ( "title" === $column_name ) {
      echo get_post_meta($post_id, "_pl_jobs_application_email", true);
    }
    if ( "applications" === $column_name ) {
      echo PL_Jobs_Post::get_applicants_count($post_id);
    }
    if ( "views" === $column_name ) {
      echo PL_Jobs_Post::get_post_views($post_id);
    }
    if ( "status" === $column_name ) {
      $closing_date = get_post_meta($post_id, "_pl_jobs_post_closing_date", true);
      $is_active = PL_Jobs_Post::is_job_active($closing_date);
      $status = $is_active ? __("Active", "pixel-labs") : __("Expired", "pixel-labs");
      echo $status;
    }
  }
}
