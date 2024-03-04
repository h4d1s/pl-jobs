<?php
global $pl_jobs;
$closing_date = get_post_meta( get_the_ID(), "_pl_jobs_post_closing_date", true );
$is_filled = get_post_meta( get_the_ID(), "_pl_jobs_post_position_filled", true );
$c_date = strtotime($closing_date);
$now_date  = strtotime("now");

$is_closed = empty($closing_date) ? false : ($c_date < $now_date);
$is_filled = empty($is_filled) ? false : true;
?>

<div class="pl-jobs-form">
  <?php
  if($is_filled || $is_closed): ?>
    <div class="pl-jobs-form-feedback">
      <span class="pl-feedback-invalid">
        <?php _e("Closed for applications.", "pixel-labs") ?>
      </span>
    </div>
  <?php
  else: ?>
    <?php $form_apply = isset($_SESSION["form_apply"]) ? $_SESSION["form_apply"] : []; ?>
    <?php $form_data = isset($form_apply["data"]) ? $form_apply["data"] : []; ?>
    <?php $form_errors = isset($form_apply["errors"]) ? $form_apply["errors"] : []; ?>

    <div class="pl-jobs-form-feedback">
      <?php if (array_key_exists("success", $form_apply)) : ?>
        <p class="pl-feedback-valid"><?php _e("Successfully applied!", "pixel-labs"); ?></p>
      <?php endif; ?>

      <?php if (array_key_exists("pl_jobs_cv_create", $form_errors)) : ?>
        <?php foreach ($form_errors["pl_jobs_fullname"] as $err) : ?>
          <span class="pl-feedback-invalid"><?php echo $err; ?></span>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <form method="POST" class="js-pl-form-apply" enctype="multipart/form-data" action="<?php echo admin_url('admin-post.php'); ?>">
      <?php wp_nonce_field('pl_jobs_add_application', '_pl_jobs_add_application_nonce'); ?>
      <input type="hidden" name="action" value="pl_jobs_add_application" />
      <input type="hidden" name="pl_jobs_post_id" value="<?php echo get_the_ID(); ?>">

      <div class="pl-input-group">
        <label for="pl_jobs_fullname">
          <?php esc_html_e("Full Name:", "pixel-labs"); ?>
          <span class="pl-feedback-invalid">*</span>
        </label>
        <input type="text" class="pl-form-control pl-input" name="pl_jobs_fullname" id="pl_jobs_fullname" placeholder="<?php esc_html_e("e.g. John Doe", 'pixel-labs'); ?>" value="<?php echo isset($form_data["pl_jobs_fullname"]) ? $form_data["pl_jobs_fullname"] : ""; ?>" />

        <?php if (array_key_exists("pl_jobs_fullname", $form_errors)) : ?>
          <?php foreach ($form_errors["pl_jobs_fullname"] as $err) : ?>
            <span class="pl-feedback-invalid"><?php echo $err; ?></span>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <div class="pl-input-group">
        <label for="pl_jobs_email">
          <?php esc_html_e("Email:", "pixel-labs"); ?>
          <span class="pl-feedback-invalid">*</span>
        </label>
        <input type="email" class="pl-form-control pl-input" name="pl_jobs_email" id="pl_jobs_email" placeholder="<?php esc_html_e("e.g. johndoe@example.com", 'pixel-labs'); ?>" value="<?php echo isset($form_data["pl_jobs_email"]) ? $form_data["pl_jobs_email"] : ""; ?>" />

        <?php if (array_key_exists("pl_jobs_email", $form_errors)) : ?>
          <?php foreach ($form_errors["pl_jobs_email"] as $err) : ?>
            <span class="pl-feedback-invalid"><?php echo $err; ?></span>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <div class="pl-input-group">
        <label for="pl_jobs_phone">
          <?php esc_html_e("Phone:", "pixel-labs"); ?>
        </label>
        <input type="text" class="pl-form-control pl-input" name="pl_jobs_phone" id="pl_jobs_phone" placeholder="<?php esc_html_e("e.g. +1 123 754 3010", 'pixel-labs'); ?>" value="<?php echo isset($form_data["pl_jobs_phone"]) ? $form_data["pl_jobs_phone"] : ""; ?>" />

        <?php if (array_key_exists("pl_jobs_phone", $form_errors)) : ?>
          <?php foreach ($form_errors["pl_jobs_phone"] as $err) : ?>
            <span class="pl-feedback-invalid"><?php echo $err; ?></span>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <div class="pl-input-group">
        <label for="pl_jobs_cover_letter">
          <?php esc_html_e("Cover letter:", "pixel-labs"); ?>
          <span class="pl-feedback-invalid">*</span>
        </label>
        <textarea class="pl-form-control pl-input" name="pl_jobs_cover_letter" id="pl_jobs_cover_letter" rows="5"><?php echo isset($form_data["pl_jobs_cover_letter"]) ? $form_data["pl_jobs_cover_letter"] : ""; ?></textarea>

        <?php if (array_key_exists("pl_jobs_cover_letter", $form_errors)) : ?>
          <?php foreach ($form_errors["pl_jobs_cover_letter"] as $err) : ?>
            <span class="pl-feedback-invalid"><?php echo $err; ?></span>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <div class="pl-input-group">
        <label for="pl_jobs_cv">
          <?php esc_html_e("Upload CV/Resume:", "pixel-labs"); ?>
          <span class="pl-feedback-invalid">*</span>
        </label>
        <input type="file" class="pl-form-control" name="pl_jobs_cv" id="pl_jobs_cv" />
        <small><?php esc_html_e("Allowed Type(s): .pdf, .doc, .docx", "pixel-labs"); ?></small>

        <?php if (array_key_exists("pl_jobs_cv", $form_errors)) : ?>
          <?php foreach ($form_errors["pl_jobs_cv"] as $err) : ?>
            <span class="pl-feedback-invalid"><?php echo $err; ?></span>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <div class="pl-input-group">
        <input type="submit" value="<?php esc_html_e("Submit", "pixel-labs"); ?>" class="pl-btn" />
      </div>
    </form>
  <?php
    unset($_SESSION["form_apply"]);
  endif; ?>
</div>
