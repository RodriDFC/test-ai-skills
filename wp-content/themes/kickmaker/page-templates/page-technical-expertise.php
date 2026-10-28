<?php

/**
 * Template Name: Technical expertise template
 *
 * @package Kickmaker
 */

defined("ABSPATH") || exit();

get_header();

// Dynamic Asset Enqueuing for the 10 Blocks
$blocks_config = [
    "hero-bottom-image" => false,
    "content-icon-list" => false,
    "side-by-side-column-icons" => true,
    "testimonials" => true,
    "side-by-side" => true,
    "side-by-side-image-list" => true,
    "featured-content-showcase" => true,
    "side-links" => false,
    "faqs" => false,
    "cta-form" => false,
    "featured-cards-slider" => true,
];

foreach ($blocks_config as $block_name => $has_js) {
    $block_url = KICKMAKER_URI . "/blocks/{$block_name}/{$block_name}";
    $version = defined("WP_DEBUG") && WP_DEBUG ? time() : KICKMAKER_VERSION;

    wp_enqueue_style("css-for-block-{$block_name}", "{$block_url}.css", [], $version);

    if ($has_js) {
        wp_enqueue_script("js-for-block-{$block_name}", "{$block_url}.js", [], $version, true);
    }
}
?>

<main class="main-technical-expertise-page">
    <?php while (have_posts()):
        the_post(); ?>

        <div class="container custom-container">
            <?php the_content(); ?>
        </div>

        <?php
        get_template_part("blocks/hero-bottom-image/hero-bottom-image");
        get_template_part("blocks/content-icon-list/content-icon-list");
        get_template_part("blocks/side-by-side-column-icons/side-by-side-column-icons");
        get_template_part("blocks/testimonials/testimonials");
        get_template_part("blocks/side-by-side/side-by-side");
        get_template_part("blocks/side-by-side-image-list/side-by-side-image-list");
        get_template_part("blocks/featured-content-showcase/featured-content-showcase");
        get_template_part("blocks/side-links/side-links");
        get_template_part("blocks/faqs/faqs");
        get_template_part("blocks/cta-form/cta-form");
        get_template_part("blocks/featured-cards-slider/featured-cards-slider");
        ?>

    <?php
    endwhile; ?>
</main>

<?php get_footer();
