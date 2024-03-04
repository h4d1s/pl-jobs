<?php

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

if (class_exists('PL_Jobs_Application_CPT')) {
  return;
}

class PL_Jobs_Application_CPT
{
  const POST_TYPE = "pl_job_application";

  public static function init()
  {
    add_action("init", array(__CLASS__, "register"));
    add_action("init", array(__CLASS__, "add_statuses"));
  }

  public static function unregister()
  {
    if (!post_type_exists(self::POST_TYPE)) {
      return;
    }

    unregister_post_type(self::POST_TYPE);
  }

  public static function get_statuses()
  {
    $statuses = array(
      "pl_applied"        => array(
        "label"           => _x("Applied", "pixel-labs"),
        "label_count"     => _n_noop(
          'Applied <span class="count">(%s)</span>',
          'Applied <span class="count">(%s)</span>',
          'pixel-labs'
        )
      ),
      "pl_hired"          => array(
        "label"           => _x("Hired", "pixel-labs"),
        "label_count"     => _n_noop(
          'Hired <span class="count">(%s)</span>',
          'Hired <span class="count">(%s)</span>',
          'pixel-labs'
        ),
      ),
      "pl_interview"      => array(
        "label"           => _x("Interviewed", "pixel-labs"),
        "label_count"     => _n_noop(
          'Interviewed <span class="count">(%s)</span>',
          'Interviewed <span class="count">(%s)</span>',
          'pixel-labs'
        ),
      ),
      "pl_rejected"       => array(
        "label"           => _x("Rejected", "pixel-labs"),
        "label_count"     => _n_noop(
          'Rejected <span class="count">(%s)</span>',
          'Rejected <span class="count">(%s)</span>',
          'pixel-labs'
        ),
      ),
      "pl_qualified"    => array(
        "label"           => _x("Qualified", "pixel-labs"),
        "label_count"     => _n_noop(
          'Qualified <span class="count">(%s)</span>',
          'Qualified <span class="count">(%s)</span>',
          'pixel-labs'
        ),
      ),
    );

    return $statuses;
  }

  public static function register()
  {
    if (post_type_exists(self::POST_TYPE)) {
      return;
    }

    $singular = _x('Application', 'Application General Name', 'pixel-labs');
    $plural   = _x('Applications', 'Application Singular Name', 'pixel-labs');

    $labels = array(
      'name'                  => $singular,
      'singular_name'         => $plural,
      'menu_name'             => $plural,
      'name_admin_bar'        => $singular,
      'archives'              => wp_sprintf(__('%s Archives', 'pixel-labs'), $singular),
      'attributes'            => wp_sprintf(__('%s Attributes', 'pixel-labs'), $singular),
      'parent_item_colon'     => wp_sprintf(__('Parent %s:', 'pixel-labs'), $singular),
      'all_items'             => $plural,
      'add_new_item'          => wp_sprintf(__('Add New %s', 'pixel-labs'), $singular),
      'add_new'               => __('Add New', 'pixel-labs'),
      'new_item'              => wp_sprintf(__('New %s', 'pixel-labs'), $singular),
      'edit_item'             => wp_sprintf(__('Edit %s', 'pixel-labs'), $singular),
      'update_item'           => wp_sprintf(__('Update %s', 'pixel-labs'), $singular),
      'view_item'             => wp_sprintf(__('View %s', 'pixel-labs'), $singular),
      'view_items'            => wp_sprintf(__('View %s', 'pixel-labs'), $plural),
      'search_items'          => wp_sprintf(__('Search %s', 'pixel-labs'), $singular),
      'not_found'             => __('Not found', 'pixel-labs'),
      'not_found_in_trash'    => __('Not found in Trash', 'pixel-labs'),
      'featured_image'        => __('Featured Image', 'pixel-labs'),
      'set_featured_image'    => __('Set featured image', 'pixel-labs'),
      'remove_featured_image' => __('Remove featured image', 'pixel-labs'),
      'use_featured_image'    => __('Use as featured image', 'pixel-labs'),
      'insert_into_item'      => wp_sprintf(__('Insert into %s', 'pixel-labs'), $singular),
      'uploaded_to_this_item' => wp_sprintf(__('Uploaded to this %s', 'pixel-labs'), $singular),
      'items_list'            => wp_sprintf(__('%s list', 'pixel-labs'), $plural),
      'items_list_navigation' => wp_sprintf(__('%s list navigation', 'pixel-labs'), $plural),
      'filter_items_list'     => wp_sprintf(__('Filter %s list', 'pixel-labs'), $singular),
    );
    $args = array(
      'label'               => $plural,
      'description'         => wp_sprintf(__('%s Description', 'pixel-labs'), $singular),
      'labels'              => $labels,
      'supports'            => false,
      'taxonomies'          => array(),
      'hierarchical'        => false,
      'public'              => false,
      'show_ui'             => true,
      'show_in_menu'        => "edit.php?post_type=pl_job_post",
      'menu_position'       => 90,
      'show_in_admin_bar'   => false,
      'show_in_nav_menus'   => true,
      'can_export'          => true,
      'has_archive'         => false,
      'exclude_from_search' => true,
      'publicly_queryable'  => false,
      "map_meta_cap"        => true,
      "capability_type"     => self::POST_TYPE,
      "capabilities"        => array(
        "create_posts"  => false
      )
    );
    register_post_type(self::POST_TYPE, $args);
  }

  public static function add_statuses()
  {
    global $post;

    $statuses = self::get_statuses();
    foreach ($statuses as $key => $status) {
      register_post_status($key, array(
        'label'                     => $status["label"],
        'public'                    => true,
        'exclude_from_search'       => true,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => $status["label_count"]
      ));
    }
  }
}
