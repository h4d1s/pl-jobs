<?php
/**
 * Template used to display post content on single pages.
 *
 * @package pl_jobs
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?>

<div id="post-<?php the_ID(); ?>" <?php post_class( 'pl-jobs-content' ); ?>>
  <?php
    /**
     * Hook: pl_jobs_single_job_before
     *
     * @hooked display_filters - 10
     */
    do_action( 'pl_jobs_single_job_before' ); ?>

  <?php
    /**
     * Hook: pl_jobs_single_job
     */
    do_action( 'pl_jobs_single_job' ); ?>

  <?php
    /**
     * Hook: pl_jobs_single_job_after
     *
     * @hooked display_filters - 10
     */
    do_action( 'pl_jobs_single_job_after' ); ?>

</div><!-- #post-## -->
