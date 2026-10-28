<?php
if (!defined("ABSPATH")) {
    exit();
}

/**
 * Event Card Component
 *
 * @package Kickmaker
 */

$event = $args["event"] ?? null;

if (!$event) {
    return;
}

$show_date = $args["show_date"] ?? true;
?>

<article class="event-card">
    <div class="event-card__image">
        <?php if (has_post_thumbnail($event->ID)): ?>
            <?php echo get_the_post_thumbnail($event->ID, "large"); ?>
        <?php else: ?>
            <img
                src="<?php echo esc_url(KICKMAKER_URI . "/assets/img/placeholder.png"); ?>"
                alt="<?php echo esc_attr(get_the_title($event->ID)); ?>"
            >
        <?php endif; ?>
    </div>

    <div class="event-card__content">
        <?php
        $event_type = get_the_terms($event->ID, "type-event");
        if ($event_type && !is_wp_error($event_type)): ?>
            <span class="event-card__type">
                <?php echo esc_html($event_type[0]->name); ?>
            </span>
        <?php endif;
        ?>

        <h4 class="title">
            <?php echo esc_html(get_the_title($event->ID)); ?>
        </h4>

        <?php if ($show_date): ?>
            <?php
            $date = get_field("event_date", $event->ID);
            if ($date): ?>
                <span class="event-card__date">
                    <?php echo esc_html(date_i18n("F j, Y", strtotime($date))); ?>
                </span>
            <?php endif;
            ?>
        <?php endif; ?>

        <a href="<?php echo esc_url(
            get_field("external_link", $event->ID) ?: get_permalink($event->ID),
        ); ?>">
            <?php esc_html_e("Learn More", "kickmaker"); ?>
        </a>
    </div>
</article>
