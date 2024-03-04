<?php

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

if (class_exists('PL_Jobs_Application')) {
  return;
}

class PL_Jobs_Application
{
  public static function init()
  {
    self::load_dependencies();
    self::init_dependencies();
    self::init_hooks();
  }

  public static function load_dependencies()
  {
    require_once PL_JOBS_DIR . 'includes/application/class-pl-jobs-application-cpt.php';
    require_once PL_JOBS_DIR . 'includes/application/class-pl-jobs-application-post.php';
    require_once PL_JOBS_DIR . 'includes/application/class-pl-jobs-application-posts.php';
    require_once PL_JOBS_DIR . 'includes/application/meta-boxes/class-pl-jobs-application-meta-box-applicant.php';
    require_once PL_JOBS_DIR . 'includes/application/meta-boxes/class-pl-jobs-application-meta-box-notes.php';
    require_once PL_JOBS_DIR . 'includes/application/meta-boxes/class-pl-jobs-application-meta-box-publish.php';
  }

  public static function init_dependencies()
  {
    PL_Jobs_Application_CPT::init();
    PL_Jobs_Application_Post::init();
    PL_Jobs_Application_Posts::init();
    PL_Jobs_Application_Meta_Box_Applicant::init();
    PL_Jobs_Application_Meta_Box_Notes::init();
    PL_Jobs_Application_Meta_Box_Publish::init();
  }

  public static function init_hooks()
  {
  }
}
