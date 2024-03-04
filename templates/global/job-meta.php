<?php
/**
 * Filters in `[jobs]` shortcode.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$job_type_terms = pl_get_object_terms( get_the_id(), 'pl_job_post_type' );
$job_category_terms = pl_get_object_terms( get_the_id(), 'pl_job_post_category' );
$job_location_terms = pl_get_object_terms( get_the_id(), 'pl_job_post_location' );
?>

<ul class="pl-list pl-list-padded">
  <?php if(isset($job_type_terms) && !empty($job_type_terms)): ?>
  <li class="pl-list-item">
    <span class="pl-list-item-icon">
      <i class="icon-calendar"></i>
    </span>
    <?php
    foreach($job_type_terms as $term) {
      echo $term->name;
    }
    ?>
  </li>
  <?php endif; ?>

  <?php if(isset($job_category_terms) && !empty($job_category_terms)): ?>
  <li class="pl-list-item">
    <span class="pl-list-item-icon">
      <i class="icon-price-tag"></i>
    </span>
    <?php
    foreach($job_category_terms as $term) {
      echo $term->name;
    }
    ?>
  </li>
  <?php endif; ?>

  <?php if(isset($job_location_terms) && !empty($job_location_terms)): ?>
  <li class="pl-list-item">
    <span class="pl-list-item-icon">
      <i class="icon-location"></i>
    </span>
    <?php
    foreach($job_location_terms as $term) {
      echo $term->name;
    }
    ?>
  </li>
  <?php endif; ?>

</ul>
