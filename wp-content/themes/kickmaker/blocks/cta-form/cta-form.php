<?php
if (!defined("ABSPATH")) {
    exit(); // Exit if accessed directly.
}
/**
 * Cta Form Block.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during backend preview render.
 * @param   int $post_id The post ID the block is rendering content against.
 * @param   array $context The context provided to the block.
 */

/** @var array $block */
$block = isset($block) && is_array($block) ? $block : [];

$content = isset($args["content"]) ? $args["content"] : get_field("cta_form");
if (!$content) {
    return;
}

$base_class = isset($args["base_class"]) ? $args["base_class"] : "general-section cta-form";

// Setup block with standardized helper.
$section = setup_block_section($block, $content, $base_class);

// Video Eyebrow
$video_eyebrow = isset($content["video_eyebrow"]) ? $content["video_eyebrow"] : null;

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
$content_section = isset($content["content_block"]) ? $content["content_block"] : [];
$style_text = isset($content_section["style_text"]) ? $content_section["style_text"] : [];
$text_size = !empty($style_text["text_size"]) ? esc_attr($style_text["text_size"]) : "default-text";
$text_color = !empty($style_text["text_color"]) ? esc_attr($style_text["text_color"]) : "";
$content_text = !empty($content_section["content"]) ? '<div class="content-text ' . $text_size . " text-" . $text_color . '">' . wp_kses_post($content_section["content"]) . "</div>" : "";

// Buttons Block
$buttons = get_block_field($content, "buttons_block.buttons", []);
$buttons_justification = get_block_field($content, "buttons_block.buttons_justification", "");

$content_form = !empty($content["form"]) ? $content["form"] : $content;
$type_form = !empty($content_form["form_type"]) ? $content_form["form_type"] : "";
$form_id = "";
$form_code = "";

if ($type_form === "gravity" && !empty($content_form["gravity"])) {
    $form_id = $content_form["gravity"];
} elseif ($type_form === "code" && !empty($content_form["form_code"])) {
    $form_code = $content_form["form_code"];
}

// Show Background Arrow
$show_bg_arrow = isset($content["show_background_arrow"]) ? (bool) $content["show_background_arrow"] : true;
?>

<section id="<?php echo esc_attr($section["id"]); ?>" class="<?php echo esc_attr($section["classes"]); ?> <?php echo esc_attr($show_bg_arrow ? "bg-arrow" : "bg-color"); ?>" style="<?php echo esc_attr($section["styles"]); ?>">
    <?php echo $section["video_content"]; ?>
    <?php echo $section["overlay_content"]; ?>
    <div class="container" id="<?php echo esc_attr($section["section_anchor"]); ?>" data-aos-duration="800" data-aos="fade-up">
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="title-content">

                    <?php if ($video_eyebrow && !empty($video_eyebrow["url"])): ?>
                        <div class="cta-form__video-eyebrow">
                            <video autoplay loop muted playsinline class="cta-form__video">
                                <source src="<?php echo esc_url($video_eyebrow["url"]); ?>" type="<?php echo esc_attr($video_eyebrow["mime_type"]); ?>">
                            </video>
                        </div>
                    <?php endif; ?>

                    <?php echo $eyebrow; ?>
                    <?php echo $heading; ?>
                    <?php echo $content_text; ?>
                    <?php if (!empty($buttons)): ?>
                        <?php block_buttons($buttons, $buttons_justification); ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <?php if ($type_form && $type_form !== "none"):
                    $form_class = "form-type-" . esc_attr($type_form); ?>

                    <div class="content-form text-light <?php echo esc_attr($form_class); ?>">
                        <div class="form-wrapper">
                            <?php if ($type_form === "gravity" && !empty($form_id)) {
                                echo do_shortcode('[gravityform id="' . absint($form_id) . '" title="false" description="false" ajax="true"]');
                            } elseif ($type_form === "code" && !empty($form_code)) { ?>
                                <div class="iframe-content">
                                    <?php echo $form_code; ?>
                                </div>
                            <?php } else { ?>
                                <div class="no-found">
                                    <p> No form found. Type: <?php echo esc_html($type_form); ?> | Form ID: <?php echo esc_html($form_id); ?> </p>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</section>