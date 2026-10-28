<?php
/**
 * The template for displaying search results pages
 *
 * @package Kickmaker
 */

get_header(); ?>

<div class="wrapper main-content-site search-results-page" id="page-wrapper">
    <main id="primary" class="site-main container">
        
        <header class="page-header">
            <h1 class="page-title">
                <?php esc_html_e("Search Results", "kickmaker"); ?>
            </h1>
            
            <form role="search" method="get" class="search-form" action="<?php echo esc_url(
                home_url("/"),
            ); ?>">
                <input type="search" class="search-field" placeholder="<?php echo esc_attr_x(
                    "Search Term Here",
                    "placeholder",
                    "kickmaker",
                ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
            </form>
        </header><!-- .page-header -->

        <div class="search-results-list">
            <?php if (have_posts()): ?>
                
                <?php while (have_posts()):

                    the_post();
                    $post_type = get_post_type();
                    $post_type_label = $post_type == "page" ? "PAGE" : "POST";
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        
                        <h2 class="entry-title">
                            <a href="<?php the_permalink(); ?>" rel="bookmark">
                                <?php the_title(); ?>
                            </a>
                        </h2>

                        <div class="entry-meta">
                            <?php echo esc_html($post_type_label); ?>
                        </div>

                        <div class="entry-summary">
                            <?php the_excerpt(); ?>
                        </div>

                        <div class="entry-url">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_permalink(); ?>
                            </a>
                        </div>

                    </article><!-- #post-<?php the_ID(); ?> -->
                <?php
                endwhile; ?>

                <?php the_posts_pagination([
                    "mid_size" => 2,
                    "prev_next" => false,
                ]); ?>

            <?php else: ?>
                <p><?php esc_html_e(
                    "Sorry, but nothing matched your search terms. Please try again with some different keywords.",
                    "kickmaker",
                ); ?></p>
            <?php endif; ?>
        </div>

    </main>
</div><!-- #page-wrapper -->

<?php get_template_part("template-parts/components/general-block-global"); ?>

<?php get_footer(); ?>
