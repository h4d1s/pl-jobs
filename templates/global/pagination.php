<?php
/**
 * The template for displaying achive posts.
 *
 * @package pl_jobs
 */

global $wp_query;

$current_page = max(1, get_query_var('pl_page'));
$total = $wp_query->max_num_pages;

if($total <= 1) {
  return;
}
?>

<nav class="pl-pagination">
  <?php
  echo paginate_links(
    apply_filters(
      'pl_jobs_pagination_args',
      array(
        'format'    => '?pl_page=%#%',
        'current'   => max( 1, $current_page ),
        'total'     => $total,
        'prev_text' => '&larr;',
        'next_text' => '&rarr;',
        'type'      => 'list',
        'end_size'  => 3,
        'mid_size'  => 3,
      )
    )
  );
  ?>
</nav>
