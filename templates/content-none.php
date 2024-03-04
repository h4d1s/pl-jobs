<?php
/**
 * The template part for displaying a message that posts cannot be found.
 *
 * Learn more: https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Hook: pl_jobs_archive_before
 */
do_action( 'pl_jobs_archive_before' );
?>

<div class="no-results not-found">
	<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

		<p>
			<?php
				/* translators: 1: URL */
				printf( wp_kses( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'pixel-labs' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( admin_url( 'post-new.php' ) ) );
			?>
		</p>

	<?php elseif ( is_search() ) : ?>

		<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'pixel-labs' ); ?></p>

	<?php else : ?>

		<p><?php esc_html_e( 'Nothing found.', 'pixel-labs' ); ?></p>

	<?php endif; ?>
</div><!-- .no-results -->
