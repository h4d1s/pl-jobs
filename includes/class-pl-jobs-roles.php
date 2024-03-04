<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

if (class_exists('PL_Jobs_Roles')) {
  return;
}

class PL_Jobs_Roles {

  private const roles = array("pl_jobs_hr", "editor", "administrator");

  public static function add_roles() {
    self::add_hr_role();
  }

  public static function remove_roles() {
    self::remove_hr_role();
  }

  public static function add_caps() {
    self::add_hr_caps();
  }

  // HR

  private static function add_hr_role() {
    add_role(
      "pl_jobs_hr",
      __( "HR", "pixel-labs" ),
    );

    self::add_hr_caps();
  }

  private static function applications_cap() 
  {
    $application_cpt = PL_Jobs_Application_CPT::POST_TYPE;
    $roles = self::roles;
    foreach($roles as $role) {
      $role = get_role($role);
      $role->add_cap("read");

      // Applications

      $role->add_cap("create_{$application_cpt}", "do_not_allow");
      $role->add_cap("create_{$application_cpt}s", "do_not_allow");
      $role->add_cap("create_private_{$application_cpt}s", "do_not_allow");
      $role->add_cap("create_published_{$application_cpt}s", "do_not_allow");
      $role->add_cap("create_others_{$application_cpt}s", "do_not_allow");

      $role->add_cap("read_{$application_cpt}");
      $role->add_cap("read_{$application_cpt}s");
      $role->add_cap("read_private_{$application_cpt}s");
      $role->add_cap("read_published_{$application_cpt}s");
      $role->add_cap("read_others_{$application_cpt}s");

      $role->add_cap("edit_{$application_cpt}");
      $role->add_cap("edit_{$application_cpt}s");
      $role->add_cap("edit_private_{$application_cpt}s");
      $role->add_cap("edit_published_{$application_cpt}s");
      $role->add_cap("edit_others_{$application_cpt}s");

      $role->add_cap("delete_{$application_cpt}s");
      $role->add_cap("delete_private_{$application_cpt}");
      $role->add_cap("delete_published_{$application_cpt}s");
      $role->add_cap("delete_others_{$application_cpt}s");
    }
  }

  private static function jobs_cap()
  {
    $roles = self::roles;
    $job_post_cpt = PL_Jobs_Post_CPT::POST_TYPE;

    foreach($roles as $role) {
      $role = get_role($role);
      $role->add_cap("read");

      // Job post

      $role->add_cap("read_{$job_post_cpt}");
      $role->add_cap("read_private_{$job_post_cpt}s");

      $role->add_cap("publish_{$job_post_cpt}s");

      $role->add_cap("edit_{$job_post_cpt}");
      $role->add_cap("edit_{$job_post_cpt}s");
      $role->add_cap("edit_private_{$job_post_cpt}s");
      $role->add_cap("edit_published_{$job_post_cpt}s");
      $role->add_cap("edit_others_{$job_post_cpt}s");

      $role->add_cap("delete_{$job_post_cpt}s");
      $role->add_cap("delete_private_{$job_post_cpt}s");
      $role->add_cap("delete_published_{$job_post_cpt}s");
      $role->add_cap("delete_others_{$job_post_cpt}s");

      $role->add_cap("manage_pl_job_post_types");
      $role->add_cap("manage_pl_job_post_categories");
      $role->add_cap("manage_pl_job_post_locations");
    }
  }

  private static function add_hr_caps()
  {
    self::applications_cap();
    self::jobs_cap();
  }

  private static function remove_hr_role() {
    remove_role('pl_jobs_hr');
  }
}
