<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

if (class_exists('PL_Geo_IP')) {
  return;
}

class PL_Geo_IP
{
  const GEO_IP_API = "http://ip-api.com/json/";

  public static function get_ip() {
    $ip = "";

    if(isset($_SERVER["HTTP_CLIENT_IP"])) {
      $ip = $_SERVER["HTTP_CLIENT_IP"];
    } else if(isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
      $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    } else {
      $ip = $_SERVER["REMOTE_ADDR"];
    }

    return $ip;
  }

  public static function geolocate_ip() {
    $geolocate_ip = get_transient("pl_jobs_geolocate_ip");

    if(FALSE === $geolocate_ip) {
      $url = add_query_arg( array(
        "ip" => self::get_ip()
      ), self::GEO_IP_API );

      $response = wp_remote_get( esc_url_raw( $url ) );

      $geolocate_ip = wp_remote_retrieve_body( $response );
      set_transient("pl_jobs_geolocate_ip", $geolocate_ip, DAY_IN_SECONDS * 365);
    }

    return json_decode($geolocate_ip, true);
  }

}
