<?php
/**
 * SEO enhancements
 *
 * @package Kickmaker
 */

if (!defined("ABSPATH")) {
    exit(); // Exit if accessed directly.
}

// Ensure theme supports title-tag (this might already be in setup.php, but ensures SEO fallback)
add_action(
    "after_setup_theme",
    function () {
        add_theme_support("title-tag");
    },
    20,
);

// Add Open Graph Meta Tags (acts as fallback if no dedicated SEO plugin is active)
function kickmaker_add_opengraph_tags()
{
    // Bypass if a major SEO plugin is already handling Meta & Open Graph tags
    if (
        defined("WPSEO_VERSION") ||
        defined("RANK_MATH_VERSION") ||
        defined("SEOPRESS_VERSION") ||
        class_exists("All_in_One_SEO_Pack")
    ) {
        return;
    }

    if (is_single() || is_page()) {
        global $post;

        $title = get_the_title($post->ID);
        $url = get_permalink($post->ID);

        // Setup Description
        $description = "";
        if (has_excerpt($post->ID)) {
            $description = wp_strip_all_tags(get_the_excerpt($post->ID));
        } else {
            $description = wp_trim_words(wp_strip_all_tags($post->post_content), 30, "...");
        }

        // Setup Image
        $image = "";
        if (has_post_thumbnail($post->ID)) {
            $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), "large");
            if ($thumbnail_src) {
                $image = esc_attr($thumbnail_src[0]);
            }
        }

        echo '<meta property="og:title" content="' . esc_attr($title) . '" />' . "\n";
        echo '<meta property="og:type" content="article" />' . "\n";
        echo '<meta property="og:url" content="' . esc_url($url) . '" />' . "\n";
        if ($description) {
            echo '<meta property="og:description" content="' .
                esc_attr($description) .
                '" />' .
                "\n";
            echo '<meta name="description" content="' . esc_attr($description) . '" />' . "\n";
        }
        if ($image) {
            echo '<meta property="og:image" content="' . esc_url($image) . '" />' . "\n";
        }
        echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
    }
}
add_action("wp_head", "kickmaker_add_opengraph_tags", 5);
