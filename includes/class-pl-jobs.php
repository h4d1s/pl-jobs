<?php

if (!defined("ABSPATH")) {
  exit;
}

if (class_exists("PL_Jobs")) {
  return;
}

final class PL_Jobs
{
  /**
   * The single instance of the class.
   *
   * @var self
   * @since  1.26.0
   */
  private static $instance = null;

  /**
   * The unique identifier of this plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string    $plugin_name    The string used to uniquely identify this plugin.
   */
  protected $plugin_name;

  public $plugin_options;

  /**
   * The current version of the plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string    $version    The current version of the plugin.
   */
  protected $version;

  public $admin = null;
  public $assets = null;
  public $options = null;

  public static function instance()
  {
    if (is_null(self::$instance)) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  /**
   * Define the core functionality of the plugin.
   *
   * Set the plugin name and the plugin version that can be used throughout the plugin.
   * Load the dependencies, define the locale, and set the hooks for the admin area and
   * the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function __construct()
  {
    $this->plugin_name = "pl-jobs";
    $this->version = "1.0.0";
    $this->plugin_options = array(
      "settings"
    );

    $this->defineConstants();
    $this->load_dependencies();
    $this->init_hooks();
    $this->init();
  }

  private function init_hooks()
  {
    add_filter("upload_dir", array($this, "modify_upload_dir"));
    add_action("init", array($this, 'register_session'));
  }

  function modify_upload_dir($param)
  {
    $param['basedir'] = $param['basedir'] . "/pl-jobs/";
    $param['url'] = $param['url'] . "/pl-jobs/";
    return $param;
  }

  function register_session()
  {
    if (!session_id()) {
      session_start();
    }
  }

  public function defineConstants()
  {
    $upload_dir = wp_upload_dir(null, false);

    $this->define("PL_JOBS_VERSION", $this->version);
    $this->define("PL_JOBS_DIR", plugin_dir_path(PL_PLUGIN_FILE));
    $this->define("PL_JOBS_URL", plugin_dir_url(PL_PLUGIN_FILE));
    $this->define('PL_JOBS_LOG_DIR', $upload_dir['basedir'] . "/pl-jobs-logs/");
  }

  public function define($name, $value)
  {
    if (!defined($name)) {
      define($name, $value);
    }
  }

  private function load_dependencies()
  {
    require_once PL_JOBS_DIR . "includes/class-pl-jobs-activator.php";
    require_once PL_JOBS_DIR . "includes/class-pl-jobs-deactivator.php";
    require_once PL_JOBS_DIR . "includes/post/class-pl-jobs-post.php";
    require_once PL_JOBS_DIR . "includes/application/class-pl-jobs-application.php";
    require_once PL_JOBS_DIR . "includes/class-pl-jobs-i18n.php";
    require_once PL_JOBS_DIR . "includes/class-pl-jobs-options.php";
    require_once PL_JOBS_DIR . "includes/class-pl-jobs-template-loader.php";
    require_once PL_JOBS_DIR . "includes/class-pl-jobs-assets.php";
    require_once PL_JOBS_DIR . "includes/class-pl-jobs-validator.php";
    require_once PL_JOBS_DIR . "includes/class-pl-jobs-utils.php";
    require_once PL_JOBS_DIR . "includes/class-pl-jobs-roles.php";
    require_once PL_JOBS_DIR . "includes/class-pl-geo-ip.php";
    require_once PL_JOBS_DIR . "includes/class-pl-upload.php";

    require_once PL_JOBS_DIR . "includes/template-hooks.php";

    add_action('after_setup_theme', array($this, 'include_template_functions'), 11);
  }

  public function init()
  {
    if (is_null($this->options) || !$this->options instanceof PL_Jobs_Options) {
      $this->options = new PL_Jobs_Options($this->get_plugin_name());
    }

    if (is_null($this->assets) || !$this->assets instanceof PL_Jobs_Assets) {
      $this->assets = new PL_Jobs_Assets($this->get_plugin_name(), $this->get_version());
    }

    PL_Jobs_Application::init();
    PL_Jobs_Post::init();
  }

  public function include_template_functions()
  {
    include_once PL_JOBS_DIR . 'includes/template-functions.php';
  }

  public static function plugin_activate()
  {
    PL_Jobs_Activator::activate();
  }

  public static function plugin_deactivate()
  {
    PL_Jobs_Deactivator::deactivate();
  }

  /**
   * The name of the plugin used to uniquely identify it within the context of
   * WordPress and to define internationalization functionality.
   *
   * @since     1.0.0
   * @return    string    The name of the plugin.
   */
  public function get_plugin_name()
  {
    return $this->plugin_name;
  }

  /**
   * Retrieve the version number of the plugin.
   *
   * @since     1.0.0
   * @return    string    The version number of the plugin.
   */
  public function get_version()
  {
    return $this->version;
  }
}
