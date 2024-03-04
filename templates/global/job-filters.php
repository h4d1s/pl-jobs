<?php
/**
 * Filters in `[jobs]` shortcode.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$s = get_query_var("pl_s", "");
$categories = get_query_var("pl_category", "");
$types = get_query_var("pl_type", "");
$locations = get_query_var("pl_location", "");
$publication_date = get_query_var("pl_date", "");

global $wp_query;

$job_publication_date = array(
  ""                => __("Choose...", "pixel-labs"),
  "last-day"        => __("Last day", "pixel-labs"),
  "last-three-days" => __("Last three days", "pixel-labs"),
  "last-week"       => __("Last week", "pixel-labs"),
  "last-month"      => __("Last month", "pixel-labs"),
);
$job_type_terms = get_terms( array(
  'taxonomy'    => 'pl_job_post_type',
  'hide_empty'  => false,
) );
$job_category_terms = get_terms( array(
  'taxonomy'    => 'pl_job_post_category',
  'hide_empty'  => false,
) );
$job_location_terms = get_terms( array(
  'taxonomy'    => 'pl_job_post_location',
  'hide_empty'  => false,
) );
?>

<div class="pl-col-12">
  <div class="pl-jobs-filters">
    <form method="GET" action="">
      <input type="hidden" name="pl_s" value="<?php echo $s; ?>" />

      <div class="pl-filter">
        <p class="pl-filter-attribute">
          <label for="pl_date">
            <?php esc_html_e("Publication date", "pixel-labs"); ?>
          </label>
        </p>
        <select
          name="pl_date"
          id="pl_date"
          class="pl-select pl-form-control">
          <?php foreach($job_publication_date as $value => $date): ?>
            <option
              value="<?php echo esc_attr($value); ?>"
              <?php selected($publication_date, $value); ?>
            >
              <?php echo $date; ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="pl-row">
        <?php if(!empty($job_location_terms) && !is_wp_error($job_location_terms)): ?>
          <div class="pl-filter pl-col-4">
            <p class="pl-filter-attribute">
              <?php esc_html_e("Location", "pixel-labs"); ?>
            </p>
            <ul class="pl-list">
              <?php foreach ($job_location_terms as $term): ?>
                <li>
                  <input
                    type="checkbox"
                    id="<?php echo esc_attr($term->slug); ?>"
                    name="pl_location[]"
                    value="<?php echo esc_attr($term->term_id); ?>"
                    <?php
                    if(!empty($locations)):
                      foreach($locations as $location):
                        checked($location, strval($term->term_id));
                      endforeach;
                    endif;
                    ?>
                  />
                  <label
                    class="pl-label"
                    for="<?php echo esc_attr($term->slug); ?>">
                    <?php echo wp_sprintf("%s (%d)", $term->name, $term->count); ?>
                  </label>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <?php if(!empty($job_category_terms) && !is_wp_error($job_category_terms)): ?>
          <div class="pl-filter pl-col-4">
            <p class="pl-filter-attribute">
              <?php esc_html_e("Categories", "pixel-labs"); ?>
            </p>
            <ul class="pl-list">
              <?php foreach ($job_category_terms as $term): ?>
                <li>
                  <input
                    type="checkbox"
                    id="<?php echo esc_attr($term->slug); ?>"
                    name="pl_category[]"
                    value="<?php echo esc_attr($term->term_id); ?>"
                    <?php
                    if(!empty($categories)):
                      foreach($categories as $category):
                        checked($category, strval($term->term_id));
                      endforeach;
                    endif;
                    ?>
                  />
                  <label
                    class="pl-label"
                    for="<?php echo esc_attr($term->slug); ?>">
                      <?php echo wp_sprintf("%s (%d)", $term->name, $term->count); ?>
                  </label>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <?php if(!empty($job_type_terms) && !is_wp_error($job_type_terms)): ?>
          <div class="pl-filter pl-col-4">
            <p class="pl-filter-attribute">
              <?php esc_html_e("Contract type", "pixel-labs"); ?>
            </p>
            <ul class="pl-list">
              <?php foreach ($job_type_terms as $term): ?>
                <li>
                  <input
                    type="checkbox"
                    id="<?php echo esc_attr($term->slug); ?>"
                    name="pl_type[]"
                    value="<?php echo esc_attr($term->term_id); ?>"
                    <?php
                    if(!empty($types)):
                      foreach($types as $type):
                        checked($type, strval($term->term_id));
                      endforeach;
                    endif;
                    ?>
                  />
                  <label
                    class="pl-label"
                    for="<?php echo esc_attr($term->slug); ?>">
                    <?php echo wp_sprintf("%s (%d)", $term->name, $term->count); ?>
                  </label>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>
      </div>

      <button type="submit" class="pl-btn">
        <?php _e("Filter", "pixel-labs"); ?>
      </button>
    </form>
  </div>
</div>
