<?php
/**
 * Performance enhancements
 *
 * @package Kickmaker
 */

if (!defined("ABSPATH")) {
    exit(); // Exit if accessed directly.
}

// 1. Remove WordPress Bloat

// Disable Emojis
function kickmaker_disable_emojis()
{
    remove_action("wp_head", "print_emoji_detection_script", 7);
    remove_action("admin_print_scripts", "print_emoji_detection_script");
    remove_action("wp_print_styles", "print_emoji_styles");
    remove_action("admin_print_styles", "print_emoji_styles");
    remove_filter("the_content_feed", "wp_staticize_emoji");
    remove_filter("comment_text_rss", "wp_staticize_emoji");
    remove_filter("wp_mail", "wp_staticize_emoji_for_email");
    add_filter("tiny_mce_plugins", "kickmaker_disable_emojis_tinymce");
    add_filter("wp_resource_hints", "kickmaker_disable_emojis_remove_dns_prefetch", 10, 2);
}
add_action("init", "kickmaker_disable_emojis");

function kickmaker_disable_emojis_tinymce($plugins)
{
    if (is_array($plugins)) {
        return array_diff($plugins, ["wpemoji"]);
    } else {
        return [];
    }
}

function kickmaker_disable_emojis_remove_dns_prefetch($urls, $relation_type)
{
    if ("dns-prefetch" == $relation_type) {
        /** This filter is documented in wp-includes/formatting.php */
        $emoji_svg_url = apply_filters("emoji_svg_url", "https://s.w.org/images/core/emoji/2/svg/");
        $urls = array_diff($urls, [$emoji_svg_url]);
    }
    return $urls;
}

// Disable Dashicons on Frontend for Guests
function kickmaker_dequeue_dashicons()
{
    if (!is_user_logged_in()) {
        wp_dequeue_style("dashicons");
        wp_deregister_style("dashicons");
    }
}
add_action("wp_enqueue_scripts", "kickmaker_dequeue_dashicons", 100);

// Remove Global Styles SVG (duotone filters) to save DOM size
// Note: Keep wp_enqueue_global_styles active so theme.json palette variables load on frontend
remove_action("wp_body_open", "wp_global_styles_render_svg_filters");

// 2. Optimize Block CSS Loading
// Loads core block CSS only when the block is used on the page, instead of one massive stylesheet
add_filter("should_load_separate_core_block_assets", "__return_true");

// 3. JavaScript Deferral
// Add 'defer' attribute to all enqueued JS files on the frontend to prevent render blocking
function kickmaker_defer_scripts($tag, $handle, $src)
{
    // The handles of scripts we DO NOT want to defer (e.g. jquery) can be added here
    $exclude = ["jquery", "jquery-core", "jquery-migrate"];

    if (is_admin() || in_array($handle, $exclude)) {
        return $tag;
    }

    // Add defer attribute if it doesn't already have it
    if (false === strpos($tag, 'defer="defer"') && false === strpos($tag, "defer")) {
        $tag = str_replace(" src", ' defer="defer" src', $tag);
    }

    return $tag;
}
add_filter("script_loader_tag", "kickmaker_defer_scripts", 10, 3);
