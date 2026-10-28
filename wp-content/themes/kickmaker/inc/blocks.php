<?php
if (!defined("ABSPATH")) {
    exit(); // Exit if accessed directly.
}
/**
 * Block Registrations
 *
 * @package Kickmaker
 */

// Register a custom block category for the theme
function kickmaker_acf_block_categories($categories, $post)
{
    return array_merge($categories, [
        [
            "slug" => "kickmaker-blocks",
            "title" => __("Kickmaker Blocks", "kickmaker"),
            "icon" => "layout",
        ],
    ]);
}
add_filter("block_categories_all", "kickmaker_acf_block_categories", 10, 2);

// Auto-load ACF Blocks from the 'blocks' directory if it exists
function kickmaker_register_acf_blocks()
{
    $blocks_dir = KICKMAKER_DIR . "/blocks/";

    if (!file_exists($blocks_dir)) {
        return;
    }

    $block_folders = array_diff(scandir($blocks_dir), ["..", "."]);

    foreach ($block_folders as $folder) {
        if (is_dir($blocks_dir . $folder)) {
            $block_json = $blocks_dir . $folder . "/block.json";
            if (file_exists($block_json)) {
                register_block_type($blocks_dir . $folder);
            }
        }
    }
}
add_action("init", "kickmaker_register_acf_blocks");
