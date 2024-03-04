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

if (class_exists('PL_Jobs_Utils')) {
  return;
}

class PL_Jobs_Utils
{
  public static function time_ago($date_string = "", $short = false) {
    if ( ! strtotime( $date_string ) ) {
      return "";
    }

    $now  = new DateTime();
    $date = new DateTime( $date_string );

    $interval = $date->diff( $now );

    $ago = "";
    if ( $interval->y ) {
      $ago_nx = "";
      if($short) {
        $ago_nx = _nx( '%dy', '%dy', $interval->y, 'years', 'pixel-labs' );
      } else {
        $ago_nx = _nx( '%d year', '%d years', $interval->y, 'years', 'pixel-labs' );
      }

      $ago = wp_sprintf($ago_nx, number_format_i18n($interval->y));
    } else if ( $interval->m ) {
      $ago_nx = "";
      if($short) {
        $ago_nx = _nx( '%dm', '%dm', $interval->m, 'months', 'pixel-labs' );
      } else {
        $ago_nx = _nx( '%d month', '%d months', $interval->m, 'months', 'pixel-labs' );
      }

      $ago = wp_sprintf($ago_nx, number_format_i18n($interval->m));
    } else if ( $interval->d ) {
      $ago_nx = "";
      if($short) {
        $ago_nx = _nx( '%dd', '%dd', $interval->d, 'days', 'pixel-labs' );
      } else {
        $ago_nx = _nx( '%d day', '%d days', $interval->d, 'days', 'pixel-labs' );
      }

      $ago = wp_sprintf($ago_nx, number_format_i18n($interval->d));
    } else if ( $interval->h ) {
      $ago_nx = "";
      if($short) {
        $ago_nx = _nx( '%dh', '%dh', $interval->h, 'hours', 'pixel-labs' );
      } else {
        $ago_nx = _nx( '%d hour', '%d hours', $interval->h, 'hours', 'pixel-labs' );
      }
      $ago = wp_sprintf($ago_nx, number_format_i18n($interval->h));
    } else if ( $interval->i ) {
      $ago_nx = "";
      if($short) {
        $ago_nx = _nx( '%dm', '%dm', $interval->i, 'minutes', 'pixel-labs' );
      } else {
        $ago_nx = _nx( '%d minute', '%d minutes', $interval->i, 'minutes', 'pixel-labs' );
      }

      $ago = wp_sprintf($ago_nx, number_format_i18n($interval->i));
    }

    if ( empty( $ago ) ) {
      if ( $short ) {
        return __( "< 1 min", 'pixel-labs' );
      } else {
        return __( "few seconds ago", "pixel-labs" );
      }
    }

    if($short) {
      return $ago;
    }

    return wp_sprintf( __( "%s ago", "pixel-labs" ), $ago );
  }

  public static function get_flag_url($country_code) {
    return PL_JOBS_URL . "assets/img/country-flags/" . strtolower($country_code) . ".png";
  }
}
