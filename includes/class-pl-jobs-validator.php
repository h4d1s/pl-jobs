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

if (class_exists('PL_Jobs_Validator')) {
  return;
}

class PL_Jobs_Validator
{
  public static function is_phone($phone) {
    if(strlen(trim($phone)) > 20) {
      return false;
    }

    if (!preg_match('/^$|(^[+]{0,1}[0-9]{8,}$)/', $phone)) {
      return false;
    }

    return true;
  }
}
