<?php
if (!defined("ABSPATH")) {
    exit(); // Exit if accessed directly.
}

/**
 * Template for displaying a single Event.
 *
 * @package Kickmaker
 */

// Enqueue styles for hardcoded blocks so they don't lose their styling
wp_enqueue_style(
    "featured-events",
    get_template_directory_uri() . "/blocks/featured-events/featured-events.css",
);
wp_enqueue_style(
    "past-events-list",
    get_template_directory_uri() . "/blocks/past-events-list/past-events-list.css",
);
wp_enqueue_style(
    "side-by-side",
    get_template_directory_uri() . "/blocks/side-by-side/side-by-side.css",
);
wp_enqueue_style("cta-events", get_template_directory_uri() . "/blocks/cta-events/cta-events.css");

// Enqueue scripts if they exist (sliders, etc)
wp_enqueue_script(
    "featured-events-js",
    get_template_directory_uri() . "/blocks/featured-events/featured-events.js",
    [],
    null,
    true,
);
wp_enqueue_script(
    "side-by-side-js",
    get_template_directory_uri() . "/blocks/side-by-side/side-by-side.js",
    [],
    null,
    true,
);

get_header();
?>

<main class="single-event">
    <?php while (have_posts()):
        the_post(); ?>
    
    <section class="single-event__hero">
        <div class="container single-event__container">
            <div class="single-event__hero-content">
                <?php breadcrumb_for_single(); ?>
                <h1 class="single-event__title"><?php the_title(); ?></h1>
            </div>
        </div>
    </section>

    <?php
    $event_content = get_the_content();
    if (!empty(trim($event_content))): ?>
    <section class="single-event__body">
        <div class="container single-event__container">
            <div class="single-event__wrapper">
                <div class="single-event__content">
                    <svg class="single-event__quote-icon" width="42" height="36" viewBox="0 0 42 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M24.3377 36V23.5385C24.3377 17.3538 25.7285 12.1385 28.5099 7.8923C31.3841 3.55384 35.8808 0.923076 42 0V7.2C38.3841 7.56923 35.8808 8.86154 34.4901 11.0769C33.192 13.2 32.543 16.3846 32.543 20.6308L26.9801 19.8H41.8609V36H24.3377ZM0 36V23.5385C0 17.3538 1.39073 12.1385 4.17218 7.8923C7.04636 3.55384 11.543 0.923076 17.6623 0V7.2C14.0464 7.56923 11.543 8.86154 10.1523 11.0769C8.8543 13.2 8.2053 16.3846 8.2053 20.6308L2.64238 19.8H17.5232V36H0Z" fill="#33ABC6" />
                    </svg>
                    <?php the_content(); ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif;
    ?>

    <?php
    endwhile; ?>

    <?php get_template_part("blocks/featured-events/featured-events"); ?>
    <?php get_template_part("blocks/past-events-list/past-events-list"); ?>
    <?php get_template_part("blocks/side-by-side/side-by-side"); ?>
    <?php get_template_part("blocks/cta-events/cta-events"); ?>
</main>

<?php get_footer();
