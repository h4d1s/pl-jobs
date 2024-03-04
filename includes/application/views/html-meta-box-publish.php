<?php
/**
 * Shows an order item fee
 *
 * @var object $item The item being displayed
 * @var int $item_id The id of the item being displayed
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}
?>

<?php $args = wp_parse_args($args, array()); ?>

<div id="submitdiv">
  <div class="submitbox" id="submitpost">
    <div id="minor-publishing">
      <div id="misc-publishing-actions">
        <div class="misc-pub-section misc-pub-post-status">
          <?php _e( 'Status:', 'pixel-labs' ); ?>
          <span id="post-status-display"><?php echo $args["selected_post_status_object"]->label; ?></span>
          <a href="#post_status" class="edit-post-status hide-if-no-js" role="button">
            <span aria-hidden="true">
              <?php _e( 'Edit', 'pixel-labs' ); ?>
            </span>
            <span class="screen-reader-text">
              <?php _e( 'Edit status', 'pixel-labs' ); ?>
            </span>
          </a>
          <div id="post-status-select" class="hide-if-js">
            <input
              type="hidden"
              name="hidden_post_status"
              id="hidden_post_status"
              value="<?php echo esc_attr($args["post"]->post_status); ?>" />
            <label for="post_status" class="screen-reader-text">
              <?php _e( 'Set status', 'pixel-labs' ); ?>
            </label>

            <select name="post_status" id="post_status">
              <?php
              $statuses = PL_Jobs_Application_CPT::get_statuses();
              foreach($statuses as $key => $post_status): ?>
                <option<?php selected( $post->post_status, $key ); ?> value="<?php echo $key; ?>">
                  <?php echo $post_status["label"]; ?>
                </option>
              <?php endforeach; ?>
            </select>
            <a href="#post_status" class="save-post-status hide-if-no-js button">
              <?php _e( 'OK', 'pixel-labs' ); ?>
            </a>
            <a href="#post_status" class="cancel-post-status hide-if-no-js button-cancel">
              <?php _e( 'Cancel', 'pixel-labs' ); ?>
            </a>
          </div>
        </div>
        <div class="misc-pub-section pl-dashicons-before dashicons-before dashicons-id-alt">
          <?php esc_html_e("Job:", "pixel-labs"); ?>
          <span id="post-visibility-display">
                <?php echo $args["job_title"]; ?>
              </span>
        </div>
        <div class="misc-pub-section pl-dashicons-before dashicons-before dashicons-calendar">
          <?php esc_html_e("Submitted on:", "pixel-labs"); ?>
          <span id="post-visibility-display">
                <?php echo $args["submitted_date"]; ?>
              </span>
        </div>
        <div class="misc-pub-section pl-dashicons-before dashicons-before dashicons-admin-site-alt2">
          <?php esc_html_e("IP:", "pixel-labs"); ?>
          <span id="post-visibility-display">
            <?php if(!empty($args["country"])): ?>
              <?php $country = $args["country"]; ?>
              <img
                src="<?php echo esc_attr($country["flag_url"]); ?>"
                width="24"
                alt="<?php echo $country["name"]; ?>" />
            <?php endif; ?>
            <?php echo $args["ip"]; ?>
          </span>
        </div>
      </div>
    </div>
    <div id="major-publishing-actions">
      <div id="publishing-action">
        <span class="spinner"></span>
        <input
          name="original_publish"
          type="hidden"
          id="original_publish"
          value="<?php esc_attr_e( "Update", "pixel-labs" ); ?>" />
        <?php submit_button(
          __( "Update", "pixel-labs" ),
          "primary large",
          "save",
          false,
          array( "id" => "publish" )
        ); ?>
      </div>
      <div class="clear"></div>
    </div>
  </div>
</div>
