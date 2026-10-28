<?php
if (!defined("ABSPATH")) {
    exit();
}

/**
 * Post Card Module
 *
 * Renders the featured-image + title + "read" button card shared by the
 * Featured Cards Slider block and the automatic Related Insights section.
 *
 * @package Kickmaker
 */

if (!function_exists("render_featured_card")) {
    function render_featured_card($post_id, $text_button = null)
    {
        $post_id = (int) $post_id;
        if ($post_id <= 0) {
            return;
        }

        $text_button = $text_button ?: __("Read article", "kickmaker");
        $title = get_the_title($post_id);
        $link = get_permalink($post_id);
        $tag = $link ? "a" : "div";
        ?>
        <<?php echo $tag; ?>
            <?php if ($link): ?>
                href="<?php echo esc_url(
                    $link
                ); ?>" class="featured-card__link" aria-label="<?php echo esc_attr($title); ?>"
            <?php else: ?>
                class="featured-card__wrapper"
            <?php endif; ?>
        >
            <article class="featured-card">
                <div class="featured-card__image">
                    <?php if (has_post_thumbnail($post_id)): ?>
                        <?php echo get_the_post_thumbnail($post_id, "large", [
                            "class" => "featured-card__img",
                        ]); ?>
                    <?php else: ?>
                        <img class="featured-card__img" src="<?php echo esc_url(
                            KICKMAKER_URI . "/dist/images/defauld-feature-image.webp",
                        ); ?>" alt="<?php echo esc_attr($title); ?>">
                    <?php endif; ?>
                    <span class="featured-card__overlay featured-card__overlay--gradient"></span>
                </div>

                <div class="featured-card__content">
                    <?php if ($title): ?>
                        <h4 class="featured-card__title"><?php echo esc_html($title); ?></h4>
                    <?php endif; ?>

                    <?php if ($link): ?>
                        <span class="featured-card__button"><?php echo esc_html(
                            $text_button
                        ); ?></span>
                    <?php endif; ?>
                </div>
            </article>
        </<?php echo $tag; ?>>
        <?php
    }
}
