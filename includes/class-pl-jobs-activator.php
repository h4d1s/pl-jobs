<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if (class_exists('PL_Jobs_Activator')) {
  return;
}

abstract class PL_Jobs_Activator {
  
	public static function register_post_types() {
    PL_Jobs_Post_CPT::register();
	  PL_Jobs_Application_CPT::register();

    flush_rewrite_rules();
	}

	public static function create_pages() {
    PL_Jobs_Post::create_page();
  }

  public static function add_roles() {
    PL_Jobs_Roles::add_roles();
  }

	public static function activate() {
		self::register_post_types();
		self::add_roles();
	}

}
