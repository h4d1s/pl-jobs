<?php

 // Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Functions hooked into pl_jobs_single_before add_action
 *
 */
do_action( 'pl_jobs_archive_content_before' ); ?>

  <?php pl_get_template_part( 'loop/job', 'post-single' ); ?>

<?php
/**
 * Hook: pl_jobs_archive_content_after
 */
do_action( 'pl_jobs_archive_content_after' );
