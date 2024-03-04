<?php
/**
 * Filters in `[jobs]` shortcode.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<header class="entry-header pl-col-md-12">
  <?php
  /**
   * Hook: pl_jobs_single_header_before
   */
  do_action( 'pl_jobs_single_header_before' );

  if ( is_single() ) {
    the_title( '<h1 class="entry-title">', '</h1>' );
  } else {
    the_title( sprintf( '<h2 class="alpha entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
  }

  /**
   * Hook: pl_jobs_single_header_after
   */
  do_action( 'pl_jobs_single_header_after' );
  ?>
</header><!-- .entry-header -->
