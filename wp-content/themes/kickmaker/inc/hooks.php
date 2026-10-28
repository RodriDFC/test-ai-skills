<?php
if (!defined("ABSPATH")) {
    exit();
}

/**
 * Theme Hooks
 *
 * Custom action and filter hooks for the Kickmaker theme.
 *
 * @package Kickmaker
 */

/**
 * Fixes column misalignment in ACF Table Repeaters when fields use conditional logic rules.
 * Uses lightweight pure CSS table-cell layout preservation instead of heavy JavaScript observers.
 */
add_action("admin_head", function () {
    echo '<style>
        /* Preserve table-cell layout flow for hidden fields to prevent column shift */
        .acf-repeater.-table td.acf-field[data-hidden="1"],
        .acf-repeater.-table td.acf-field.acf-hidden {
            display: table-cell !important;
            visibility: hidden !important;
            opacity: 0 !important;
            pointer-events: none !important;
        }
    </style>';
});

/**
 * Limit search results to 5 posts per page
 */
add_action("pre_get_posts", function ($query) {
    if (!is_admin() && $query->is_main_query() && $query->is_search()) {
        $query->set("posts_per_page", 5);
    }
});

// Adds stable IDs derived from text to h2/h3 headings in blog posts for table of contents linking.

if (!function_exists("kickmaker_add_heading_ids")) {
    function kickmaker_add_heading_ids($content)
    {
        if (!is_singular("post") || !in_the_loop() || !is_main_query()) {
            return $content;
        }

        $used_slugs = [];

        return preg_replace_callback(
            '/<h([23])(\s[^>]*)?>(.*?)<\/h\1>/is',
            function ($matches) use (&$used_slugs) {
                $attributes = isset($matches[2]) ? $matches[2] : "";

                if (preg_match("/\sid\s*=/i", $attributes)) {
                    return $matches[0];
                }

                $slug = sanitize_title(wp_strip_all_tags($matches[3]));

                if (!$slug) {
                    return $matches[0];
                }

                if (isset($used_slugs[$slug])) {
                    $used_slugs[$slug]++;
                    $slug .= "-" . $used_slugs[$slug];
                } else {
                    $used_slugs[$slug] = 1;
                }

                return sprintf(
                    '<h%1$s%2$s id="%3$s">%4$s</h%1$s>',
                    $matches[1],
                    $attributes,
                    esc_attr($slug),
                    $matches[3],
                );
            },
            $content,
        );
    }

    add_filter("the_content", "kickmaker_add_heading_ids", 20);
}
