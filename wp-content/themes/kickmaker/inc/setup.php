<?php
if (!defined("ABSPATH")) {
    exit(); // Exit if accessed directly.
}
/**
 * Theme setup functions
 *
 * @package Kickmaker
 */

if (!function_exists("kickmaker_setup")):
    function kickmaker_setup()
    {
        // Add default posts and comments RSS feed links to head.
        add_theme_support("automatic-feed-links");

        // Let WordPress manage the document title.
        add_theme_support("title-tag");

        // Enable support for Post Thumbnails on posts and pages.
        add_theme_support("post-thumbnails");

        // Register Menus
        register_nav_menus([
            "primary" => esc_html__("Primary", "kickmaker"),
            "footer1" => esc_html__("Footer Menu 1", "kickmaker"),
            "footer2" => esc_html__("Footer Menu 2", "kickmaker"),
            "footer3" => esc_html__("Footer Menu 3", "kickmaker"),
            "footer4" => esc_html__("Footer Menu 4", "kickmaker"),
            "footer5" => esc_html__("Footer Menu 5", "kickmaker"),
        ]);

        // Switch default core markup to output valid HTML5.
        add_theme_support("html5", [
            "search-form",
            "comment-form",
            "comment-list",
            "gallery",
            "caption",
            "style",
            "script",
        ]);

        // Add theme support for block editor features
        add_theme_support("align-wide");
        add_theme_support("wp-block-styles");

        // Add support for editor styles
        add_theme_support("editor-styles");

        // Enqueue editor styles (main CSS + custom ACF block styles)
        add_editor_style("dist/css/main.css");

        // Automatically load individual ACF block CSS files into the Gutenberg block editor
        $block_styles = glob(get_template_directory() . "/blocks/*/*.css");
        if (!empty($block_styles)) {
            foreach ($block_styles as $style_path) {
                $relative_path = str_replace(get_template_directory() . "/", "", $style_path);
                add_editor_style($relative_path);
            }
        }
    }
endif;
add_action("after_setup_theme", "kickmaker_setup");
