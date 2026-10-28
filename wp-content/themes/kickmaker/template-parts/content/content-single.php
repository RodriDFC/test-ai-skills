<?php

/**
 * Template part for displaying single blog posts.
 *
 * @package Kickmaker
 */

$author_name = get_field("author_name"); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class("single-post"); ?>>

    <header class="single-post__hero">
        <div class="container">
            <?php breadcrumb_blog_single(); ?>

            <?php the_title('<h1 class="single-post__title">', "</h1>"); ?>

            <div class="single-post__meta">
                <span class="single-post__author">
                    <?php echo esc_html($author_name ? $author_name : get_the_author()); ?>
                </span>
                <span class="single-post__meta-divider" aria-hidden="true">|</span>
                <span class="single-post__date"><?php echo esc_html(get_the_date()); ?></span>
            </div>
        </div>
    </header>

    <div class="single-post__sticky-title" aria-hidden="true">
        <div class="container">
            <p class="single-post__sticky-title-text"><?php echo esc_html(get_the_title()); ?></p>
        </div>
    </div>

    <section class="single-post__body">
        <div class="container">
            <div class="single-post__layout">

                <aside class="single-post__sidebar">
                    <details class="single-post__nav-toggle">
                        <summary class="single-post__nav-toggle-label"><?php esc_html_e(
                            "Scroll to topic",
                            "kickmaker",
                        ); ?></summary>
                        <nav id="post-toc-nav" class="single-post__nav" aria-label="<?php esc_attr_e(
                            "Table of contents",
                            "kickmaker",
                        ); ?>">
                        </nav>
                    </details>
                </aside>

                <div class="single-post__content-col">
                    <div class="single-post__content">
                        <?php
                        the_content();

                        wp_link_pages([
                            "before" =>
                                '<div class="page-links">' . esc_html__("Pages:", "kickmaker"),
                            "after" => "</div>",
                        ]);
                        ?>
                    </div>
                </div>

            </div>
        </div>
    </section>

</article>
