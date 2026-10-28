<?php
if (!defined("ABSPATH")) {
    exit();
}

$related_post_ids = kickmaker_get_related_posts(get_the_ID(), 3);
if (empty($related_post_ids)) {
    return;
}
?>

<section class="related-insights">
    <svg aria-hidden="true" style="position:absolute;width:0;height:0;overflow:hidden;">
        <filter id="km-duotone-cards">
            <feColorMatrix type="matrix" values="0.3 0.59 0.11 0 0  0.3 0.59 0.11 0 0  0.3 0.59 0.11 0 0  0 0 0 1 0" />
            <feComponentTransfer>
                <feFuncR type="table" tableValues="0 0 0 0 0.0589 0.1589 0.2589 0.3589 0.5102 0.7551 1" />
                <feFuncG type="table" tableValues="0 0.1380 0.2760 0.4139 0.5295 0.6295 0.7295 0.8295 0.9100 0.9550 1" />
                <feFuncB type="table" tableValues="0 0.1690 0.3380 0.5071 0.6354 0.7354 0.8354 0.9354 1 1 1" />
            </feComponentTransfer>
        </filter>
    </svg>
    <div class="container">
        <h2 class="related-insights__title heading"><?php esc_html_e(
            "Read related insights",
            "kickmaker",
        ); ?></h2>

        <div class="related-insights__grid">
            <?php foreach ($related_post_ids as $related_post_id): ?>
                <div class="related-insights__item">
                    <?php render_featured_card($related_post_id); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
