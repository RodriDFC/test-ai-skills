<?php
if (!defined("ABSPATH")) {
    exit(); // Exit if accessed directly.
}

/**
 * Global General Block Component
 *
 * Renders the global general block configured in ACF Theme Options.
 *
 * @package Kickmaker
 */

$content = get_field("general_block_global", "option");
if (!$content) {
    return;
}

get_template_part("blocks/general-content/general-content", null, [
    "content" => $content,
    "base_class" => "general-section block-content global-block",
]);
