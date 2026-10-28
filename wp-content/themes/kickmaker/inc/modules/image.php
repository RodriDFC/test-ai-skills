<?php
if (!defined("ABSPATH")) {
    exit();
}

/**
 * Image Module
 *
 * Renders a WordPress attachment image at a given size.
 * Supports: thumbnail, medium, mediumlarge, large, full.
 *
 * @package Kickmaker
 */

function block_image($image_settings, $image_size)
{
    if (!is_array($image_settings) || !isset($image_settings["ID"])) {
        echo "<p>No valid image content available.</p>";
        return;
    }

    $id = $image_settings["ID"];
    $alt =
        isset($image_settings["alt"]) && $image_settings["alt"] !== ""
            ? $image_settings["alt"]
            : get_post_meta($id, "_wp_attachment_image_alt", true);

    $image_size = strtolower(trim($image_size));
    $custom_data = ["class" => "custom-img", "alt" => $alt];

    $size_map = [
        "thumbnail" => "thumbnail",
        "medium" => "medium",
        "mediumlarge" => "medium_large",
        "large" => "large",
        "full" => "full",
    ];

    $wp_size = isset($size_map[$image_size]) ? $size_map[$image_size] : "full";

    echo wp_get_attachment_image($id, $wp_size, false, $custom_data);
}
