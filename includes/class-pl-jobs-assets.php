<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

if (class_exists('PL_Jobs_Assets')) {
  return;
}

class PL_Jobs_Assets
{
  private $plugin_name;
  private $version;

  function __construct($plugin_name, $version) {
    $this->plugin_name = $plugin_name;
    $this->version = $version;

    add_action( 'wp_enqueue_scripts', array($this, 'styles'));
    add_action( 'wp_enqueue_scripts', array($this, 'scripts'));
  }

	public function get_asset_url( $path ) {
		return trailingslashit(PL_JOBS_URL) . "assets/" . $path;
	}

  public function styles() {
    wp_enqueue_style("pixel-labs", $this->get_asset_url('css/pl.css'), array(), $this->version, 'all');
    wp_enqueue_style($this->plugin_name, $this->get_asset_url('css/pl-jobs.css'), array(), $this->version, 'all');
    wp_enqueue_style($this->plugin_name . '-icomoon', $this->get_asset_url('css/icomoon.css'), array(), $this->version, 'all');
  }

  public function scripts() {
    global $post;

    wp_enqueue_script("{$this->plugin_name}-reCAPTCHA", "https://www.google.com/recaptcha/api.js", array(), $this->version, true);

	  wp_enqueue_script("{$this->plugin_name}-jquery-validate", $this->get_asset_url('libs/jquery-validate/jquery.validate.min.js'), array("jquery"), "1.19.3", true);
	  wp_enqueue_script("{$this->plugin_name}-jquery-validate-additional-methods", $this->get_asset_url('libs/jquery-validate/additional-methods.min.js'), array("jquery"), "1.19.3", true);

	  wp_enqueue_script($this->plugin_name, $this->get_asset_url("js/pl-jobs.js"), array("jquery"), $this->version, true);
    wp_localize_script(
      $this->plugin_name,
      "pl_jobs_settings_obj",
      array(
        "ajax_url" => admin_url("admin-ajax.php"),
        "jquery_validate" => array(
          "phone_validation"    => __("Please enter a valid phone number.", "pixel-labs")
        )
      )
    );

    if(is_singular("pl_job_post")) {
      wp_enqueue_script("{$this->plugin_name}-application", $this->get_asset_url("js/pl-jobs.application.js"), array("jquery"), $this->version, true );
      wp_localize_script(
        "{$this->plugin_name}-application",
        "pl_jobs_application_ajax_obj",
        array(
          "nonce"             => wp_create_nonce("pl-jobs-application-nonce"),
          "pl_jobs_post_id"   => $post->ID,
          "form_apply"        => array(
            "response_success"    => __("Thank you for applying.", "pixel-labs"),
            "submit"              => __("Submit", "pixel-labs"),
            "submitting"          => __("Submitting...", "pixel-labs"),
            "submitted"           => __("Submitted", "pixel-labs"),
          )
        )
      );
    }
  }
}
