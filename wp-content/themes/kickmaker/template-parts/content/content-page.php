<?php
/**
 * Template part to display general content
 *
 * @package Kickmaker
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="entry-content">
        <?php
        the_content();

        wp_link_pages([
            "before" => '<div class="page-links">' . esc_html__("pages:", "kickmaker"),
            "after" => "</div>",
        ]);
        ?>
    </div>
</article>