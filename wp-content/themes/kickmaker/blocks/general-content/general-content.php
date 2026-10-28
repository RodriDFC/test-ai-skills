<?php
if (!defined("ABSPATH")) {
    exit(); // Exit if accessed directly.
}
/**
 * General Content Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during backend preview render.
 * @param   int $post_id The post ID the block is rendering content against.
 * @param   array $context The context provided to the block.
 */

/** @var array $block */
$block = isset($block) && is_array($block) ? $block : [];

$content = isset($args["content"]) ? $args["content"] : get_field("acf_name");
if (!$content) {
    return;
}

$base_class = isset($args["base_class"]) ? $args["base_class"] : "general-section block-class-name";

// Setup block with standardized helper.
$section = setup_block_section($block, $content, $base_class);

// Eyebrow Block
$eyebrow_block = isset($content["eyebrow_block"]) ? $content["eyebrow_block"] : [];
$eyebrow_color = !empty($eyebrow_block["eyebrow_color"]) ? esc_attr($eyebrow_block["eyebrow_color"]): "";
$eyebrow = !empty($eyebrow_block["eyebrow"])? '<span class="eyebrow text-' .$eyebrow_color .'">' .esc_html($eyebrow_block["eyebrow"]) ."</span>": "";

// Heading Block
$heading_block = isset($content["heading_block"]) ? $content["heading_block"] : [];
$ht = !empty($heading_block["heading_size"]) ? esc_html($heading_block["heading_size"]) : "h2";
$heading_color = !empty($heading_block["heading_color"]) ? esc_attr($heading_block["heading_color"]) : "";
$heading = !empty($heading_block["heading"])? "<" .$ht .' class="heading text-' .$heading_color .'">' . wp_kses_post($heading_block["heading"]) ."</" .$ht .">" : "";

// Content Section
// Content Text Block
$content_section = isset($content["content_block"]) ? $content["content_block"] : [];
$style_text = isset($content_section["style_text"]) ? $content_section["style_text"] : [];
$text_size = !empty($style_text["text_size"]) ? esc_attr($style_text["text_size"]) : "default-text";
$text_color = !empty($style_text["text_color"]) ? esc_attr($style_text["text_color"]) : "";
$content_text = !empty($content_section["content"])? '<div class="content-text ' .$text_size ." text-" .$text_color .'">' . wp_kses_post($content_section["content"]) ."</div>" : "";

// Buttons Block
$buttons = get_block_field($content, "buttons_block.buttons", []);
$buttons_justification = get_block_field($content, "buttons_block.buttons_justification", "");

//acf custom

?>

<section id="<?php echo esc_attr($section["id"]); ?>" class="<?php echo esc_attr($section["classes"]); ?>" style="<?php echo esc_attr($section["styles"]); ?>">
    <?php echo $section["video_content"]; ?>
    <?php echo $section["overlay_content"]; ?>
    <div class="container" id="<?php echo esc_attr($section["section_anchor"]); ?>" data-aos-duration="800" data-aos="fade-up">
        <div class="title-content">
            <?php echo $eyebrow; ?>
            <?php echo $heading; ?>
            <?php echo $content_text; ?>
        </div>
        <?php if (!empty($buttons)): ?>
            <?php block_buttons($buttons, $buttons_justification); ?>
        <?php endif; ?>
    </div>
</section>