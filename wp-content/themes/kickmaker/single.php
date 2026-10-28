<?php

if (!defined("ABSPATH")) {
    exit();
}

/**
 * The template for displaying all single posts
 *
 * @package Kickmaker
 */

$blog_cta_form = get_field("global_blog_form", "option");

if ($blog_cta_form) {
    $cta_form_css = KICKMAKER_DIR . "/blocks/cta-form/cta-form.css";

    if (file_exists($cta_form_css)) {
        wp_enqueue_style(
            "cta-form-style",
            KICKMAKER_URI . "/blocks/cta-form/cta-form.css",
            [],
            filemtime($cta_form_css),
        );
    }
}

get_header();
?>

<main id="primary" class="site-main">

    <?php while (have_posts()):
        the_post();

        get_template_part("template-parts/content/content", "single");
        get_template_part("template-parts/components/related-insights");
    endwhile; ?>

    <?php if ($blog_cta_form): ?>
        <div class="single-post__subscribe">
            <?php get_template_part("blocks/cta-form/cta-form", null, [
                "content" => $blog_cta_form,
            ]); ?>
        </div>
    <?php endif; ?>

</main>

<?php get_footer();
