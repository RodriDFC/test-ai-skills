<?php
if (!defined("ABSPATH")) {
    exit(); // Exit if accessed directly.
}
/**
 * Hero Floating Image Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during backend preview render.
 * @param   int $post_id The post ID the block is rendering content against.
 * @param   array $context The context provided to the block.
 */

/** @var array $block */
$block = isset($block) && is_array($block) ? $block : [];

$content = isset($args["content"]) ? $args["content"] : get_field("hero_floating_image");
if (!$content) {
    return;
}

$base_class = isset($args["base_class"]) ? $args["base_class"] : "general-section hero-floating-image";

// Setup block with standardized helper.
$section = setup_block_section($block, $content, $base_class);

// Eyebrow Block
$eyebrow_block = isset($content["eyebrow_block"]) ? $content["eyebrow_block"] : [];
$eyebrow_color = !empty($eyebrow_block["eyebrow_color"]) ? esc_attr($eyebrow_block["eyebrow_color"]) : "";
$eyebrow = !empty($eyebrow_block["eyebrow"]) ? '<span class="eyebrow text-' . $eyebrow_color . '">' . esc_html($eyebrow_block["eyebrow"]) . "</span>" : "";

// Heading Block
$heading_block = isset($content["heading_block"]) ? $content["heading_block"] : [];
$ht = !empty($heading_block["heading_size"]) ? esc_html($heading_block["heading_size"]) : "h2";
$heading_color = !empty($heading_block["heading_color"]) ? esc_attr($heading_block["heading_color"]) : "";
$heading = !empty($heading_block["heading"]) ? "<" . $ht . ' class="heading text-' . $heading_color . '">' . wp_kses_post($heading_block["heading"]) . "</" . $ht . ">" : "";

// Content Section
// Content Text Block
$content_section = isset($content["content_block"]) ? $content["content_block"] : [];
$style_text = isset($content_section["style_text"]) ? $content_section["style_text"] : [];
$text_size = !empty($style_text["text_size"]) ? esc_attr($style_text["text_size"]) : "default-text";
$text_color = !empty($style_text["text_color"]) ? esc_attr($style_text["text_color"]) : "";
$content_text = !empty($content_section["content"]) ? '<div class="content-text ' . $text_size . " text-" . $text_color . '">' . wp_kses_post($content_section["content"]) . "</div>" : "";

// Buttons Block
$buttons = get_block_field($content, "buttons_block.buttons", []);
$buttons_justification = get_block_field($content, "buttons_block.buttons_justification", "");

//acf custom
$hero_image = isset($content["hero_image"]) ? $content["hero_image"] : null;
$navigation_items = isset($content["navigation_items"]) && is_array($content["navigation_items"]) ? $content["navigation_items"] : [];
?>

<section id="<?php echo esc_attr($section["id"]); ?>" class="<?php echo esc_attr($section["classes"]); ?>" style="<?php echo esc_attr($section["styles"]); ?>">
    <?php echo $section["video_content"]; ?>
    <?php echo $section["overlay_content"]; ?>
    <div class="container" id="<?php echo esc_attr($section["section_anchor"]); ?>" data-aos-duration="800" data-aos="fade-up">
        <div class="hero-floating-image__row">
            <div class="hero-floating-image__content-col">
                <div class="title-content">
                    <?php echo $eyebrow; ?>
                    <?php echo $heading; ?>
                    <?php echo $content_text; ?>
                </div>

                <?php if (!empty($buttons)): ?>
                    <?php block_buttons($buttons, $buttons_justification); ?>
                <?php endif; ?>

                <?php if (!empty($navigation_items)): ?>
                    <nav class="hero-floating-image__nav" aria-label="<?php esc_attr_e("Hero Navigation", "kickmaker"); ?>">
                        <ul class="hero-floating-image__nav-list">
                            <?php foreach ($navigation_items as $item):
                                $item_link = !empty($item["link"]) && is_array($item["link"]) ? $item["link"] : [];
                                $link_url = !empty($item_link["url"]) ? $item_link["url"] : "";
                                if (!$link_url) {
                                    continue;
                                }

                                $item_image = !empty($item["image"]) ? $item["image"] : null;
                                $link_title = !empty($item_link["title"]) ? $item_link["title"] : "";
                                $link_target = !empty($item_link["target"]) ? $item_link["target"] : "_self";

                                $img_src = "";
                                $img_alt = "";
                                if (is_array($item_image)) {
                                    $img_src = !empty($item_image["url"]) ? $item_image["url"] : "";
                                    $img_alt = !empty($item_image["alt"]) ? $item_image["alt"] : "";
                                } elseif (is_numeric($item_image)) {
                                    $img_src = wp_get_attachment_image_url($item_image, "thumbnail");
                                    $img_alt = (string) get_post_meta($item_image, "_wp_attachment_image_alt", true);
                                } elseif (is_string($item_image)) {
                                    $img_src = $item_image;
                                }

                                if (empty($link_title) && !empty($img_alt)) {
                                    $link_title = $img_alt;
                                }
                                ?>
                                <li class="hero-floating-image__nav-item">
                                    <a class="hero-floating-image__nav-link" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>"<?php echo $link_target === "_blank" ? ' rel="noopener noreferrer"' : ""; ?>>
                                        <?php if ($img_src): ?>
                                            <span class="hero-floating-image__nav-icon-wrap">
                                                <img class="hero-floating-image__nav-icon" src="<?php echo esc_url($img_src); ?>" alt="<?php echo esc_attr($img_alt); ?>" width="36" height="36" loading="lazy">
                                            </span>
                                        <?php endif; ?>
                                        <span class="hero-floating-image__nav-text"><?php echo esc_html($link_title); ?></span>
                                        <span class="hero-floating-image__nav-arrow" aria-hidden="true">
                                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M2.5 4.5L6 8L9.5 4.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>

            <?php if (!empty($hero_image)):
                $hero_img_src = "";
                $hero_img_alt = "";
                $hero_img_id = 0;
                $hero_img_width = "";
                $hero_img_height = "";

                if (is_array($hero_image)) {
                    $hero_img_src = !empty($hero_image["url"]) ? $hero_image["url"] : "";
                    $hero_img_alt = !empty($hero_image["alt"]) ? $hero_image["alt"] : "";
                    $hero_img_id = !empty($hero_image["ID"]) ? absint($hero_image["ID"]) : 0;
                    $hero_img_width = !empty($hero_image["width"]) ? $hero_image["width"] : "";
                    $hero_img_height = !empty($hero_image["height"]) ? $hero_image["height"] : "";
                } elseif (is_numeric($hero_image)) {
                    $hero_img_id = absint($hero_image);
                    $hero_img_src = wp_get_attachment_image_url($hero_img_id, "full");
                    $hero_img_alt = (string) get_post_meta($hero_img_id, "_wp_attachment_image_alt", true);
                } elseif (is_string($hero_image)) {
                    $hero_img_src = $hero_image;
                }
                ?>
                <div class="hero-floating-image__media-col">
                    <div class="hero-floating-image__image-container">
                        <?php if ($hero_img_id): ?>
                            <?php echo wp_get_attachment_image($hero_img_id, "full", false, [
                                "class" => "hero-floating-image__img",
                                "alt" => esc_attr($hero_img_alt),
                                "loading" => "eager",
                                "fetchpriority" => "high",
                            ]); ?>
                        <?php elseif ($hero_img_src): ?>
                            <img class="hero-floating-image__img" src="<?php echo esc_url($hero_img_src); ?>" alt="<?php echo esc_attr($hero_img_alt); ?>" <?php if ($hero_img_width && $hero_img_height): ?>width="<?php echo esc_attr($hero_img_width); ?>" height="<?php echo esc_attr($hero_img_height); ?>"<?php endif; ?> loading="eager" fetchpriority="high">
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($navigation_items)): ?>
                <div class="hero-floating-image__select-wrapper">
                    <label for="<?php echo esc_attr("hero-select-" . $section["id"]); ?>" class="visually-hidden">
                        <?php esc_html_e("Scroll to Market", "kickmaker"); ?>
                    </label>
                    <div class="hero-floating-image__select-custom">
                        <select id="<?php echo esc_attr("hero-select-" . $section["id"]); ?>" class="hero-floating-image__select js-hero-selector" aria-label="<?php esc_attr_e("Scroll to Market", "kickmaker"); ?>">
                            <option value="" disabled selected><?php esc_html_e("Scroll to Market", "kickmaker"); ?></option>
                            <?php foreach ($navigation_items as $item):
                                $item_link = !empty($item["link"]) && is_array($item["link"]) ? $item["link"] : [];
                                $link_url = !empty($item_link["url"]) ? $item_link["url"] : "";
                                if (!$link_url) {
                                    continue;
                                }

                                $link_title = !empty($item_link["title"]) ? $item_link["title"] : "";
                                $link_target = !empty($item_link["target"]) ? $item_link["target"] : "_self";
                                ?>
                                <option value="<?php echo esc_url($link_url); ?>" data-target="<?php echo esc_attr($link_target); ?>">
                                    <?php echo esc_html($link_title); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <span class="hero-floating-image__select-arrow" aria-hidden="true">
                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 5L7 9L11 5" stroke="#e02b20" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
