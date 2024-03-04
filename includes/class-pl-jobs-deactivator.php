<?php

if (!defined('ABSPATH')) {
  exit;
}

/**
 * Fired during plugin deactivation
 *
 * @link       http://
 * @since      1.0.0
 *
 * @package    Pl_Jobs
 * @subpackage Pl_Jobs/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Pl_Jobs
 * @subpackage Pl_Jobs/includes
 * @author     Pixel Labs <name@domain.com>
 */

if (class_exists('PL_Jobs_Deactivator')) {
  return;
}

class PL_Jobs_Deactivator
{

  public static function unregister_post_types()
  {
    PL_Jobs_Application_CPT::unregister();
    PL_Jobs_Post_CPT::unregister();
  }

  public static function delete_pages()
  {
    PL_Jobs_Post::delete_page();
  }

  public static function remove_roles()
  {
    PL_Jobs_Roles::remove_roles();
  }

  public static function deactivate()
  {
    self::unregister_post_types();
    self::delete_pages();
    self::remove_roles();
    
    session_destroy();
  }
}
