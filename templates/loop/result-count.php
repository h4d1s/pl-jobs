<?php
/**
 * Result Count
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wp_query;

$total = count($wp_query->posts);
$per_page = get_query_var('posts_per_page', 1);
$current = max(get_query_var( 'paged', 1 ), 1);
?>

<p class="pl-result-count">
	<?php
	// phpcs:disable WordPress.Security
	if ( 1 === intval( $total ) ) {
		_e( 'Showing the single result', 'pixel-labs' );
	} elseif ( $total <= $per_page || -1 === $per_page ) {
		/* translators: %d: total results */
		printf( _n( 'Showing %d result', 'Showing %d results', $total, 'pixel-labs' ), $total );
	} else {
		$first = ( $per_page * $current ) - $per_page + 1;
		$last  = min( $total, $per_page * $current );
		/* translators: 1: first result 2: last result 3: total results */
		printf( _nx( 'Showing %1$d&ndash;%2$d of %3$d result', 'Showing %1$d&ndash;%2$d of %3$d results', $total, 'with first and last result', 'pixel-labs' ), $first, $last, $total );
	}
	// phpcs:enable WordPress.Security
	?>
</p>
