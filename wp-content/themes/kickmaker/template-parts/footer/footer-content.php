<?php
if (!defined("ABSPATH")) {
    exit(); // Exit if accessed directly.
}
/**
 * Footer content
 *
 * Everything but the Quick Links column comes from Theme Settings > Footer.
 * The links are a WordPress menu (footer1) so they stay editable and translatable.
 *
 * @package Kickmaker
 */

$footer = get_field("theme_settings_footer", "options");
$footer = is_array($footer) ? $footer : [];

$logo = get_block_field($footer, "logo_footer", []);
$buttons = get_block_field($footer, "footer_buttons.buttons", []);
$buttons_justification = get_block_field($footer, "footer_buttons.buttons_justification", "start");
$locations = get_block_field($footer, "locations", []);
$socials = get_block_field($footer, "socials", []);
$copyright = get_block_field($footer, "copyright", "");
$additional_links = get_block_field($footer, "additional_links", []);
?>

<div class="container">
    <div class="footer-main">
        <div class="footer-brand">
            <?php if (!empty($logo)): ?>
                <a class="footer-logo" href="<?php echo esc_url(home_url("/")); ?>">
                    <?php block_image($logo, "full"); ?>
                </a>
            <?php endif; ?>

            <?php if (!empty($buttons)) {
                block_buttons($buttons, $buttons_justification);
            } ?>
        </div>

        <?php wp_nav_menu([
            "theme_location" => "footer1",
            "container" => "nav",
            "container_class" => "footer-menu",
            "menu_class" => "footer-menu-list",
            "depth" => 2,
            "fallback_cb" => "",
        ]); ?>
    </div>

    <?php if (!empty($locations)): ?>
        <ul class="footer-locations">
            <?php foreach ($locations as $location):

                $name = isset($location["name"]) ? $location["name"] : "";
                $link = isset($location["link"]) ? $location["link"] : [];

                if (!$name) {
                    continue;
                }
                ?>
                <li class="footer-location">
                    <?php if (!empty($link["url"])): ?>
                        <a href="<?php echo esc_url($link["url"]); ?>" target="<?php echo esc_attr(
    !empty($link["target"]) ? $link["target"] : "_self",
); ?>"><?php echo esc_html($name); ?></a>
                    <?php else: ?>
                        <span><?php echo esc_html($name); ?></span>
                    <?php endif; ?>
                </li>
            <?php
            endforeach; ?>
        </ul>
    <?php endif; ?>

    <div class="footer-bottom">
        <?php $copyright_line = trim(
            sprintf("%s© %s. %s", get_bloginfo("name"), date_i18n("Y"), $copyright),
        ); ?>

        <?php if ($copyright_line || !empty($additional_links)): ?>
            <p class="footer-legal">
                <span><?php echo esc_html($copyright_line); ?></span>

                <?php foreach ($additional_links as $item):

                    $link = isset($item["link"]) ? $item["link"] : [];

                    if (empty($link["url"])) {
                        continue;
                    }
                    ?>
                    <a href="<?php echo esc_url($link["url"]); ?>" target="<?php echo esc_attr(
    !empty($link["target"]) ? $link["target"] : "_self",
); ?>"><?php echo esc_html($link["title"]); ?></a>
                <?php
                endforeach; ?>
            </p>
        <?php endif; ?>

        <?php if (!empty($socials)): ?>
            <ul class="footer-socials">
                <?php foreach ($socials as $social):

                    $icon = isset($social["logo_social"]) ? $social["logo_social"] : [];
                    $link = isset($social["link_social"]) ? $social["link_social"] : "";
                    // The field is a URL, but an ACF link array is accepted too.
                    $url = is_array($link) ? (isset($link["url"]) ? $link["url"] : "") : $link;

                    if (!$url || empty($icon["url"])) {
                        continue;
                    }

                    $label = "";
                    if (!empty($icon["alt"])) {
                        $label = $icon["alt"];
                    } elseif (!empty($icon["title"])) {
                        $label = $icon["title"];
                    }
                    ?>
                    <li class="footer-social">
                        <a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener"
                            aria-label="<?php echo esc_attr($label); ?>">
                            <span class="footer-social-icon" style="--icon: url('<?php echo esc_url(
                                $icon["url"],
                            ); ?>')"></span>
                        </a>
                    </li>
                <?php
                endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>
