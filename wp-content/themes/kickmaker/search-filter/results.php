<?php
if (!defined("ABSPATH")) {
    exit();
}
/**
 * Search & Filter results template.
 *
 * Search & Filter Pro looks for this file in the theme before falling back to its own, and
 * includes it with $query already built. Everything the plugin swaps over AJAX has to live
 * inside `.search-filter-query-posts` and `a.page-numbers`, which are the selectors the
 * `results_shortcode` integration sets by itself.
 *
 * Labels come from the block through a global because this file runs outside its scope.
 *
 * @var WP_Query $query
 */

$results_settings = isset($GLOBALS["kickmaker_search_filter_results"])
    ? $GLOBALS["kickmaker_search_filter_results"]
    : [];

$card_button_label = !empty($results_settings["card_button_label"])
    ? $results_settings["card_button_label"]
    : __("Read more", "kickmaker");
$no_results_text = !empty($results_settings["no_results_text"])
    ? $results_settings["no_results_text"]
    : __("No results found.", "kickmaker");

$placeholder_image = KICKMAKER_URI . "/dist/images/defauld-feature-image.webp";
?>

<?php if ($query->have_posts()): ?>
    <div class="search-filter-query-posts sf-results-grid">
        <?php while ($query->have_posts()):

            $query->the_post();
            $post_title = get_the_title();
            ?>
            <article class="card-result">
                <a class="card-result__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                    <?php if (has_post_thumbnail()) {
                        the_post_thumbnail("large", ["loading" => "lazy"]);
                    } else {
                         ?>
                        <img src="<?php echo esc_url(
                            $placeholder_image,
                        ); ?>" alt="" loading="lazy" />
                    <?php
                    } ?>
                </a>

                <h3 class="card-result__title">
                    <a href="<?php the_permalink(); ?>"><?php echo esc_html($post_title); ?></a>
                </h3>

                <a class="btn-site card-result__btn" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr(
    sprintf(_x('%1$s: %2$s', "card call to action", "kickmaker"), $card_button_label, $post_title),
); ?>">
                    <span><?php echo esc_html($card_button_label); ?></span>
                </a>
            </article>
        <?php
        endwhile; ?>
    </div>

    <?php if ($query->max_num_pages > 1): ?>
        <nav class="sf-pagination" aria-label="<?php esc_attr_e("Results pages", "kickmaker"); ?>">
            <?php echo paginate_links([
                "total" => $query->max_num_pages,
                "current" => max(1, (int) $query->get("paged")),
                "mid_size" => 1,
                "end_size" => 1,
                "type" => "plain",
                "prev_text" => "&lsaquo;",
                "next_text" => "&rsaquo;",
            ]); ?>
        </nav>
    <?php endif; ?>
<?php else: ?>
    <div class="search-filter-query-posts sf-results-empty">
        <p><?php echo esc_html($no_results_text); ?></p>
    </div>
<?php endif; ?>

<?php wp_reset_postdata(); ?>
