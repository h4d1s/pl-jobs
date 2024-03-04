<?php
/**
 * Filters in `[jobs]` shortcode.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$s = get_query_var('pl_s');

$categories = get_query_var("pl_category", false);
$types = get_query_var("pl_type", false);
$locations = get_query_var("pl_location", false);
$publication_date = get_query_var("pl_date", false);
?>
<div class="pl-col-12">
  <form method="GET" action="">

    <?php if($categories): ?>
      <?php foreach($categories as $category): ?>
        <input type="hidden" name="pl_category[]" value="<?php echo $category; ?>" />
      <?php endforeach; ?>
    <?php endif; ?>

    <?php if($types): ?>
      <?php foreach($types as $type): ?>
        <input type="hidden" name="pl_type[]" value="<?php echo $type; ?>" />
      <?php endforeach; ?>
    <?php endif; ?>

    <?php if($locations): ?>
      <?php foreach($locations as $location): ?>
        <input type="hidden" name="pl_location[]" value="<?php echo $location; ?>" />
      <?php endforeach; ?>
    <?php endif; ?>

    <?php if($publication_date): ?>
      <input type="hidden" name="pl_date" value="<?php echo $publication_date; ?>" />
    <?php endif; ?>

    <div class="pl-input-group pl-no-wrap">
      <label for="pl_s" class="screen-reader-text">
        <?php esc_html_e("Search", "pixel-labs"); ?>
      </label>
      <input
        type="text"
        value="<?php echo esc_attr($s); ?>"
        id="pl_s"
        name="pl_s"
        class="pl-input pl-form-control pl-input-search"
        placeholder="<?php esc_attr_e('Search jobs...', 'pixel-labs'); ?>" />
      <button
        type="submit"
        class="pl-btn pl-btn-search">
        <?php esc_attr_e('Search', 'pixel-labs'); ?>
      </button>
    </div>
  </form>
</div>
