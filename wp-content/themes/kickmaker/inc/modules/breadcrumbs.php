<?php
if (!defined("ABSPATH")) {
    exit(); // Exit if accessed directly.
}

/**
 * Breadcrumbs Module
 *
 * Provides breadcrumb navigation helpers for pages and posts.
 *
 * @package Kickmaker
 */

/**
 * Global breadcrumb display function using Breadcrumb NavXT plugin.
 */
if (!function_exists("breadcrumb_hero")) {
    function breadcrumb_hero()
    {
        if (function_exists("bcn_display")) { ?>
            <div class="breadcrumb-site">
                <div class="items-breadcrumb"><?php bcn_display(); ?></div>
            </div>
        <?php }
    }
}

/**
 * Native fallback breadcrumb helper for hierarchical pages.
 */
if (!function_exists("breadcrumb_hero_simple")) {
    function breadcrumb_hero_simple()
    {
        echo '<p class="breadcrumbs">';

        if (is_page()) {
            global $post;

            if ($post && !empty($post->ID)) {
                $parents = get_post_ancestors($post->ID);
                $parents = array_reverse($parents);

                foreach ($parents as $parent_id) {
                    echo ' <a href="' .
                        esc_url(get_permalink($parent_id)) .
                        '">' .
                        esc_html(get_the_title($parent_id)) .
                        "</a> / ";
                }

                echo "<span>" . esc_html(get_the_title($post->ID)) . "</span>";
            }
        }

        echo "</p>";
    }
}

/**
 * Breadcrumb for single
 * Compatible with WPML.
 */
if (!function_exists("breadcrumb_for_single")) {
    function breadcrumb_for_single()
    {
        if (function_exists("bcn_display")) { ?>
            <div class="breadcrumb-site">
                <div class="items-breadcrumb"><?php bcn_display(); ?></div>
            </div>
        <?php }
    }
}

//for single video
if (!function_exists("custom_breadcrumb_for_single_video")) {
    function custom_breadcrumb_for_single_video($title, $type, $id)
    {
        if (
            is_singular("video") &&
            $id === get_queried_object_id() &&
            in_array("post-video", $type, true)
        ) {
            return __("Video", "kickmaker");
        }

        return $title;
    }

    add_filter("bcn_breadcrumb_title", "custom_breadcrumb_for_single_video", 10, 3);
}

//for single individual bio
if (!function_exists("custom_breadcrumb_for_single_individual_bio")) {
    function custom_breadcrumb_for_single_individual_bio($title, $type, $id)
    {
        if (
            is_singular("individual-bio") &&
            $id === get_queried_object_id() &&
            in_array("post-individual-bio", $type, true)
        ) {
            return __("Leadership", "kickmaker");
        }

        return $title;
    }

    add_filter("bcn_breadcrumb_title", "custom_breadcrumb_for_single_individual_bio", 10, 3);
}

//for single event
if (!function_exists("custom_breadcrumb_for_single_event")) {
    function custom_breadcrumb_for_single_event($title, $type, $id)
    {
        if (
            is_singular("event") &&
            $id === get_queried_object_id() &&
            in_array("post-event", $type, true)
        ) {
            return __("Events", "kickmaker");
        }

        return $title;
    }

    add_filter("bcn_breadcrumb_title", "custom_breadcrumb_for_single_event", 10, 3);
}

/**
 * Breadcrumb for single blog posts: "Insights » Blog".
 * Compatible with WPML.
 */
if (!function_exists("breadcrumb_blog_single")) {
    function breadcrumb_blog_single()
    {
        // Get Insights page by its original slug.
        $insights_page = get_page_by_path("insights");

        // Get the translated Insights page for the current language.
        if ($insights_page && function_exists("apply_filters")) {
            $translated_insights_id = apply_filters(
                "wpml_object_id",
                $insights_page->ID,
                "page",
                true,
            );

            if ($translated_insights_id) {
                $insights_page = get_post($translated_insights_id);
            }
        }

        $insights_url =
            $insights_page && get_post_status($insights_page->ID) === "publish"
                ? get_permalink($insights_page->ID)
                : home_url("/insights/");
        ?>

        <div class="breadcrumb-site">
            <div class="items-breadcrumb">

                <a href="<?php echo esc_url($insights_url); ?>">
                    <span>
                        <?php echo esc_html__("Insights", "kickmaker"); ?>
                    </span>
                </a>

                <span class="breadcrumb-separator"></span>

                <span class="current-item">
                    <?php echo esc_html__("Blog", "kickmaker"); ?>
                </span>

            </div>
        </div>

<?php
    }
}
