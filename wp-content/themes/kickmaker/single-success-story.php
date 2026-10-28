<?php

if (!defined("ABSPATH")) {
    exit();
}

/**
 * The template for displaying all single posts
 *
 * @package Kickmaker
 */

get_header();
?>

<main id="primary" class="site-main">

    <?php while (have_posts()):
        the_post();

        get_template_part("template-parts/content/content", "single-success-story");
    endwhile; ?>

</main>

<?php get_footer();
