<?php
if (!defined("ABSPATH")) {
    exit(); // Exit if accessed directly.
}
/**
 * Shortcodes Entry Point
 *
 * Cleanly load all shortcodes from here.
 *
 * @package Kickmaker
 */

// Example: require_once __DIR__ . '/my-shortcode.php';

function buttons_header()
{
    $theme_settings_header = get_field("theme_settings_header", "options");
    if (!$theme_settings_header) {
        return "";
    }

    $buttons_block = !empty($theme_settings_header["header_buttons"])
        ? $theme_settings_header["header_buttons"]
        : "";
    if (!$buttons_block) {
        return "";
    }

    $buttons_justification = !empty($buttons_block["buttons_justification"])
        ? $buttons_block["buttons_justification"]
        : "";
    $buttons = !empty($buttons_block["buttons"]) ? $buttons_block["buttons"] : "";

    if (!$buttons) {
        return "";
    }

    return block_buttons($buttons, $buttons_justification);
}

add_shortcode("shortcode_buttons_header", "buttons_header");
