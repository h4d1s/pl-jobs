<?php

/**
 * Shows an order item fee
 *
 * @var object $item The item being displayed
 * @var int $item_id The id of the item being displayed
 */

if (!defined('ABSPATH')) {
  exit;
}
?>

<?php $args = wp_parse_args($args, array()); ?>

<table class="pl-jobs-table-applicant">
  <tbody>
    <?php if (isset($args["email"])) : ?>
      <tr>
        <th class="pl-jobs-table-applicant-header"><?php esc_html_e("Email:", "pixel-labs"); ?></th>
        <td class="pl-jobs-table-applicant-cell"><?php echo $args["email"]; ?></td>
      </tr>
    <?php endif; ?>

    <?php if (isset($args["phone"])) : ?>
      <tr>
        <th class="pl-jobs-table-applicant-header"><?php esc_html_e("Phone:", "pixel-labs"); ?></th>
        <td class="pl-jobs-table-applicant-cell"><?php echo $args["phone"]; ?></td>
      </tr>
    <?php endif; ?>

    <?php if (isset($args["url"])) : ?>
      <tr>
        <th class="pl-jobs-table-applicant-header"><?php esc_html_e("CV/Resume:", "pixel-labs"); ?></th>
        <td class="pl-jobs-table-applicant-cell">
          <a href="<?php echo $args["url"]; ?>" class="button-secondary">
            <?php esc_html_e("Download CV", "pixel-labs"); ?>
          </a>
        </td>
      </tr>
    <?php endif; ?>

    <?php if (isset($args["cover_letter"])) : ?>
      <tr>
        <th class="pl-jobs-table-applicant-header">
          <?php esc_html_e("Cover letter:", "pixel-labs"); ?>
        </th>
        <td class="pl-jobs-table-applicant-cell">
          <?php echo $args["cover_letter"]; ?>
        </td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>