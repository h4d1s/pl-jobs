<?php
/**
 * Template used to display post content on single pages.
 *
 * @package pl_jobs
 */
?>

<div class="pl-col-12">
  <div class="pl-jobs-list-item">
    <div class="pl-jobs-list-item-header">
      <a href="<?php echo get_permalink(); ?>">
        <?php echo get_the_title(); ?>
      </a>

      <span class="pl-jobs-list-item-header-date">
        <?php echo pl_display_date(); ?>
      </span>
    </div>

    <div class="pl-jobs-list-item-body">
      <?php pl_display_job_meta(); ?>
    </div>
  </div>
</div>
