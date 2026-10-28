<?php
if (!defined("ABSPATH")) {
    exit(); // Exit if accessed directly.
}
/**
 * The main template file
 *
 * @package Kickmaker
 */

get_header();
?>

<div class="wrapper main-content-site" id="page-wrapper">
    <main id="primary" class="site-main">
        <?php if (have_posts()):
            while (have_posts()):
                the_post();
                get_template_part("template-parts/content/content", get_post_type());
            endwhile;

            the_posts_navigation();
        else:
            get_template_part("template-parts/content/content", "none");
        endif; ?>
    </main>
</div><!-- #page-wrapper -->

<?php get_footer();
