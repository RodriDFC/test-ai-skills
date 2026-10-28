<?php

/**
 * @package Kickmaker
 */

defined("ABSPATH") || exit();

// CSS for blocks
wp_enqueue_style(
    'css-for-block-cta-subscribe',
    get_template_directory_uri() . '/blocks/cta-subscribe/cta-subscribe.css',
    array(),
    filemtime(get_template_directory() . '/blocks/cta-subscribe/cta-subscribe.css')
);
wp_enqueue_style(
    'css-for-block-featured-cards-slider',
    get_template_directory_uri() . '/blocks/featured-cards-slider/featured-cards-slider.css',
    array(),
    filemtime(get_template_directory() . '/blocks/featured-cards-slider/featured-cards-slider.css')
);

// Add JS for blocks
wp_enqueue_script(
    'js-for-block-featured-cards-slider',
    get_template_directory_uri() . '/blocks/featured-cards-slider/featured-cards-slider.js',
    array(),
    filemtime(get_template_directory() . '/blocks/featured-cards-slider/featured-cards-slider.js'),
    true
);


get_header();




?>

<main class="main-single-video">
    <?php while (have_posts()): ?>
        <?php the_post(); ?>
        <section class="section-header">
            <div class="container custom-container">
                <div class="box-title">
                    <?php
                    breadcrumb_for_single();
                    ?>
                    <h1 class="heading-main"><?php the_title(); ?></h1>
                </div>
            </div>
        </section>
        <?php the_content(); ?>
    <?php endwhile; ?>

    <?php
    get_template_part('blocks/featured-cards-slider/featured-cards-slider');
    get_template_part('blocks/cta-subscribe/cta-subscribe');
    ?>
</main>


<?php get_footer();
