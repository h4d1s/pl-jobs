<?php
/**
 * The template for displaying archive posts.
 *
 * @package pl_jobs
 */

$count_posts = wp_count_posts( 'pl_job_post' );
$published_posts = $count_posts->publish;
?>

<div class="pl-col-12">
  <p class="pl-total-jobs-count">
    <?php
    echo apply_filters(
      'pl_total_jobs_count',
      wp_sprintf(
        _nx( 'There is %s job open.', 'There are %s jobs open.', $published_posts, 'number of jobs', 'pixel-labs' ), number_format_i18n( $published_posts )
      )
    );
    ?>
  </p>
</div>
