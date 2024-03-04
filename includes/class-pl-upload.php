<?php

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

if (class_exists('PL_Geo_IP')) {
  return;
}

class PL_Upload
{
  private static function get_allowed_mimetypes()
  {
    $allowed_mimetypes = [
      "pdf" => "application/pdf",
      "doc" => "application/msword",
      "docx" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document"
    ];
    return apply_filters('pl_jobs_upload_mimes', $allowed_mimetypes);
  }

  private static function get_file_type($file)
  {
    return wp_check_filetype($file, self::get_allowed_mimetypes());
  }

  public static function upload($file)
  {
    if (UPLOAD_ERR_OK === $file["error"]) {
      $uploaded_type = self::get_file_type($file["name"]);
      if ($uploaded_type["type"] !== FALSE) {
        $upload = wp_upload_bits($file["name"], null, file_get_contents($file["tmp_name"]));
        if ($upload) {
          return $upload;
        } else {
          throw new \Exception(__("There was an error uploading CV file.", "pixel-labs"));
        }
      } else {
        throw new \Exception(__("CV file type is not allowed.", "pixel-labs"));
      }
    } else {
      throw new \Exception(__("CV file not found.", "pixel-labs"));
    }
  }
}
