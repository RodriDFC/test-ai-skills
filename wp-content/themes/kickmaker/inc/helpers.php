<?php
if (!defined("ABSPATH")) {
    exit();
}

/**
 * Theme Helpers
 *
 * Custom theme helper functions for the Kickmaker theme.
 */

/**
 * Transform Gravity Forms input submit button into a modern <button><span></span></button> tag
 */
add_filter(
    "gform_submit_button",
    function ($button, $form) {
        if (empty($button)) {
            return $button;
        }

        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $button);
        libxml_clear_errors();

        $input = $dom->getElementsByTagName("input")->item(0);
        if (!$input) {
            return $button;
        }

        $button_html = "<button";

        foreach ($input->attributes as $attribute) {
            if ($attribute->name === "value") {
                continue;
            }

            $button_html .= sprintf(
                ' %s="%s"',
                esc_attr($attribute->name),
                esc_attr($attribute->value),
            );
        }

        $button_html .= "><span>" . esc_html($input->getAttribute("value")) . "</span></button>";

        return $button_html;
    },
    10,
    2,
);

/**
 * Convert numeric decimal minutes (e.g. 18.22 or 75.5) to formatted time string ("18:13" or "1:15:30").
 *
 * @param float|int|string|null $numeric_minutes
 * @return string Formatted duration string or empty string.
 */
if (!function_exists("kickmaker_format_duration")) {
    function kickmaker_format_duration($numeric_minutes)
    {
        if (empty($numeric_minutes) || !is_numeric($numeric_minutes)) {
            return "";
        }

        $total_seconds = (int) round((float) $numeric_minutes * 60);
        if ($total_seconds <= 0) {
            return "";
        }

        $hours = floor($total_seconds / 3600);
        $minutes = floor(($total_seconds % 3600) / 60);
        $seconds = $total_seconds % 60;

        if ($hours > 0) {
            return sprintf("%d:%02d:%02d", $hours, $minutes, $seconds);
        }

        return sprintf("%02d:%02d", $minutes, $seconds);
    }
}

// Get related posts: same-category first, backfilled with latest posts. Returns post IDs.
if (!function_exists("kickmaker_get_related_posts")) {
    function kickmaker_get_related_posts($post_id, $limit = 3)
    {
        $post_id = (int) $post_id;
        $category_ids = wp_get_post_categories($post_id);
        $related_ids = [];

        if (!empty($category_ids)) {
            $related_ids = get_posts([
                "post_type" => "post",
                "posts_per_page" => $limit,
                "post__not_in" => [$post_id],
                "category__in" => $category_ids,
                "orderby" => "date",
                "order" => "DESC",
                "fields" => "ids",
                "no_found_rows" => true,
                "ignore_sticky_posts" => true,
            ]);
        }

        $remaining = $limit - count($related_ids);
        if ($remaining > 0) {
            $fallback_ids = get_posts([
                "post_type" => "post",
                "posts_per_page" => $remaining,
                "post__not_in" => array_merge([$post_id], $related_ids),
                "orderby" => "date",
                "order" => "DESC",
                "fields" => "ids",
                "no_found_rows" => true,
                "ignore_sticky_posts" => true,
            ]);
            $related_ids = array_merge($related_ids, $fallback_ids);
        }

        return $related_ids;
    }
}

add_action("wp_head", "apply_acf_page_background_color");

function apply_acf_page_background_color()
{
    if (is_page() && function_exists("get_field")) {
        $page_id = get_queried_object_id();
        $color = get_field("background_page", $page_id);

        if (!empty($color)) {
            $safe_color = esc_attr($color);

            echo "<style type='text/css'>\n";
            echo "  div#page-wrapper.main-content-site {\n";
            echo "      background-color: {$safe_color} !important;\n";
            echo "  }\n";
            echo "</style>\n";
        }
    }
}

/**
 * Custom Logo on Login Screen (wp-login.php)
 */
add_action("login_enqueue_scripts", "custom_login_logo");
function custom_login_logo()
{
    $theme_settings_header = get_field("theme_settings_header", "options");

    if (is_array($theme_settings_header) && isset($theme_settings_header["login_logo"])) {
        $admin_logo = $theme_settings_header["login_logo"];

        if (is_array($admin_logo) && isset($admin_logo["url"])) {
            $logo_url = esc_url($admin_logo["url"]);

            echo "
            <style>
                body.login div#login h1 a {
                    background-image: url('{$logo_url}');
                    background-size: contain;
                    width: 100%;
                    max-width: 250px;
                    height: 80px;
                    margin-bottom: 20px;
                }
            </style>
            ";
        }
    }
}
add_filter("upload_mimes", function ($mimes) {
    $mimes["glb"] = "model/gltf-binary";
    $mimes["gltf"] = "model/gltf+json";

    return $mimes;
});

/**
*
* Dynamic breadcrumb for Single CPTs with Taxonomies.
* @param string $post_type Slug of the Custom Post Type (e.g. 'success-story').
* @param string $taxonomy Slug of the taxonomy (e.g. 'industry').
* @param bool $echo true to output directly, false to return.
*/
function breadcrumb_single($post_type, $taxonomy, $permalink_base, $echo = true) {
    // If we are not in a single of the requested CPT, do not execute anything
    if (!is_singular($post_type)) {
        return;
    }

    // Get dynamic information of the Custom Post Type
    $post_type_obj = get_post_type_object( $post_type );
    $cpt_label     = $post_type_obj ? $post_type_obj->labels->name : ucfirst( $post_type );
    $cpt_link      = get_post_type_archive_link( $post_type );

    // Fallback for if the CPT does not have an archive page activated
    if ( $permalink_base ) {
        $cpt_link = home_url( $permalink_base . '/' );
    }

    // Get terms of the taxonomy of the current post
    $terms     = get_the_terms( get_the_ID(), $taxonomy );
    $term_name = '';
    $term_slug = '';

    if ( $terms && ! is_wp_error( $terms ) ) {
        $first_term = $terms[0];
        $term_name  = $first_term->name;
        $term_slug  = $first_term->slug;
    }

    // Construct the filter URL (?_industry=slug)
    $term_link = add_query_arg( '_' . $taxonomy, $term_slug, $cpt_link );

    ob_start();
    ?>
    <div class="breadcrumb-site">
        <div class="items-breadcrumb">
            
            <!-- 1. Link to CPT (e.g. Success Stories) -->
            <span property="itemListElement" typeof="ListItem">
                <a property="item" typeof="WebPage" title="Go to <?php echo esc_attr( $cpt_label ); ?>." href="<?php echo esc_url( $cpt_link ); ?>" class="post post-page">
                    <span property="name"><?php echo esc_html( $cpt_label ); ?></span>
                </a>
            </span>
            <span class="breadcrumb-separator" aria-hidden="true"></span>

            <!-- 2. Link to Taxonomy filter (e.g. Industry) -->
            <?php if ( ! empty( $term_name ) ) : ?>
                <span property="itemListElement" typeof="ListItem">
                    <a property="item" typeof="WebPage" title="Go to <?php echo esc_attr( $term_name ); ?>." href="<?php echo esc_url( $term_link ); ?>" class="post post-page">
                        <span property="name"><?php echo esc_html( $term_name ); ?></span>
                    </a>
                </span>
                <span class="breadcrumb-separator" aria-hidden="true"></span>
            <?php endif; ?>

            <!-- 3. Title of the current single -->
            <span property="itemListElement" typeof="ListItem">
                <span property="name" class="post post-page current-item"><?php the_title(); ?></span>
            </span>

        </div>
    </div>
    <?php
    $html = ob_get_clean();

    if ( $echo ) {
        echo $html;
    } else {
        return $html;
    }
}
