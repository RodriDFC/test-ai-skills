<?php
if (!defined("ABSPATH")) {
    exit(); // Exit if accessed directly.
}

/**
 * Extract nested values from an array using dot notation.
 *
 * @param array  $content    Source array.
 * @param string $field_path Dot notation path, e.g. buttons_block.buttons.
 * @param mixed  $default    Fallback value when path is missing.
 *
 * @return mixed
 */
function get_block_field($content, $field_path, $default = "")
{
    if (empty($content) || !is_array($content)) {
        return $default;
    }

    $keys = explode(".", $field_path);
    $value = $content;

    foreach ($keys as $key) {
        if (!is_array($value) || !array_key_exists($key, $value)) {
            return $default;
        }

        $value = $value[$key];
    }

    return !empty($value) ? $value : $default;
}

/**
 * Styles global settings processing for blocks.
 */
function styles_global_settings($styles_settings)
{
    $styles = [
        "content_width" => "default",
        "z-index" => "2",
        "custom_padding_mobile" => "",
        "text_align" => "left",
        "section_anchor" => "",
        "margin_top" => 0,
        "margin_bottom" => 0,
        "padding_top" => 0,
        "padding_bottom" => 0,
        "custom_class" => "",
    ];

    if ($styles_settings && is_array($styles_settings)) {
        $content_width = !empty($styles_settings["content_width"])
            ? $styles_settings["content_width"]
            : "default";
        $z_index = !empty($styles_settings["z_index"]) ? $styles_settings["z_index"] : "2";
        $custom_padding_mobile_sides = [
            "only-top" => "custom-padding-mobile-top",
            "only-bottom" => "custom-padding-mobile-bottom",
            "both" => "custom-padding-mobile-top custom-padding-mobile-bottom",
        ];
        $custom_padding_mobile_raw = !empty($styles_settings["custom_padding_mobile"])
            ? $styles_settings["custom_padding_mobile"]
            : "none";
        $custom_padding_mobile = isset($custom_padding_mobile_sides[$custom_padding_mobile_raw])
            ? $custom_padding_mobile_sides[$custom_padding_mobile_raw]
            : "";
        $text_align = !empty($styles_settings["text_align"])
            ? $styles_settings["text_align"]
            : "left";
        $section_anchor = !empty($styles_settings["section_anchor"])
            ? $styles_settings["section_anchor"]
            : "";

        $default_sizes = !empty($styles_settings["default_sizes"]);
        if ($default_sizes) {
            $margin_top = !empty($styles_settings["margin_top_default"])
                ? $styles_settings["margin_top_default"]
                : 0;
            $margin_bottom = !empty($styles_settings["margin_bottom_default"])
                ? $styles_settings["margin_bottom_default"]
                : 0;
            $padding_top = !empty($styles_settings["padding_top_default"])
                ? $styles_settings["padding_top_default"]
                : 0;
            $padding_bottom = !empty($styles_settings["padding_bottom_default"])
                ? $styles_settings["padding_bottom_default"]
                : 0;
        } else {
            $margin_top = !empty($styles_settings["margin_top"])
                ? $styles_settings["margin_top"]
                : 0;
            $margin_bottom = !empty($styles_settings["margin_bottom"])
                ? $styles_settings["margin_bottom"]
                : 0;
            $padding_top = !empty($styles_settings["padding_top"])
                ? $styles_settings["padding_top"]
                : 0;
            $padding_bottom = !empty($styles_settings["padding_bottom"])
                ? $styles_settings["padding_bottom"]
                : 0;
        }

        $custom_class = !empty($styles_settings["custom_classes"])
            ? $styles_settings["custom_classes"]
            : "";

        $styles = [
            "content_width" => $content_width,
            "z-index" => $z_index,
            "custom_padding_mobile" => $custom_padding_mobile,
            "text_align" => $text_align,
            "section_anchor" => $section_anchor,
            "margin_top" => $margin_top,
            "margin_bottom" => $margin_bottom,
            "padding_top" => $padding_top,
            "padding_bottom" => $padding_bottom,
            "custom_class" => $custom_class,
        ];
    }

    return $styles;
}

/**
 * Overlay content HTML generator.
 */
function overlay_content(
    $key = "vertical-black-transparent",
    $opacity = "1",
    $direction = "180",
    $overlay_blend_mode = "initial",
) {
    $item_class = [
        "vertical-black-transparent" =>
            '<div class="overlay-content" style="background: linear-gradient(' .
            esc_attr($direction) .
            "deg, rgba(0, 0, 0, 0.00) 35.58%, #000 75.3%); opacity: " .
            esc_attr($opacity) .
            "; mix-blend-mode: " .
            esc_attr($overlay_blend_mode) .
            "; max-height: 400px;" .
            "top: auto; bottom: 0" .
            ';"></div>',
        "horizontal-black-transparent" =>
            '<div class="overlay-content" style="background: linear-gradient(' .
            esc_attr($direction) .
            "deg, rgba(0, 0, 0, 0.00) 0%, #000 100%); opacity: " .
            esc_attr($opacity) .
            "; mix-blend-mode: " .
            esc_attr($overlay_blend_mode) .
            ';"></div>',
    ];

    if (empty($item_class[$key])) {
        $key = "vertical-black-transparent";
    }

    return $item_class[$key];
}

/**
 * Gradient background string generator.
 */
function gradient_bg($key = "vertical-black-transparent")
{
    $item_class = [
        "vertical-black-transparent" =>
            " linear-gradient(180deg, rgba(0, 0, 0, 0.00) 35.58%, #000 75.3%)",
        "horizontal-black-transparent" =>
            " linear-gradient(270deg, rgba(0, 0, 0, 0.00) 0%, #000 100%)",
    ];

    if (empty($item_class[$key])) {
        $key = "vertical-black-transparent";
    }

    return $item_class[$key];
}

/**
 * Background settings processing for blocks.
 */
function bg_global_settings($background_settings)
{
    $bg = "none";
    $video_content = "";
    $overContent = "";
    $parallax = "";
    $border_section = "none";
    $type = "none";

    if ($background_settings && is_array($background_settings)) {
        $border_section = !empty($background_settings["border_section"])
            ? $background_settings["border_section"]
            : "none";
        $type = !empty($background_settings["media_type"])
            ? $background_settings["media_type"]
            : "none";
        $parallax = !empty($background_settings["parallax"]) ? "parallax-effect" : "";

        if ("color" === $type) {
            $bgcolor = !empty($background_settings["color"]) ? $background_settings["color"] : [];
            $bg = !empty($bgcolor["color_picker"]) ? $bgcolor["color_picker"] : "";
        } elseif ("image" === $type) {
            $bgimage = !empty($background_settings["image"]) ? $background_settings["image"] : [];
            $bg = !empty($bgimage["url"]) ? "url(" . esc_url($bgimage["url"]) . ")" : "";
        } elseif ("video" === $type) {
            $bg_video = !empty($background_settings["video_mp4"])
                ? $background_settings["video_mp4"]
                : "";
            $video_content = $bg_video
                ? '<div class="video-container"><video id="video-bg" width="100%" height="660" autoplay loop muted><source src="' .
                    esc_url($bg_video) .
                    '" type="video/mp4" /></video></div>'
                : "";
        } elseif ("gradient" === $type) {
            $grad = !empty($background_settings["gradient_bg"])
                ? $background_settings["gradient_bg"]
                : [];
            $bg = !empty($grad["gradient"]) ? gradient_bg($grad["gradient"]) : "";
        }

        // Overlay
        $overlay_type = !empty($background_settings["overlay_type"])
            ? $background_settings["overlay_type"]
            : "";
        $overlay_opacity = isset($background_settings["overlay_opacity"])
            ? $background_settings["overlay_opacity"]
            : "0";
        $overlay_blend_mode = !empty($background_settings["blend_mode"])
            ? $background_settings["blend_mode"]
            : "initial";

        if ("image" === $type || "video" === $type) {
            if ("color" === $overlay_type) {
                $overlay_color = !empty($background_settings["overlay_color"])
                    ? $background_settings["overlay_color"]
                    : [];
                $color_val = !empty($overlay_color["color_picker"])
                    ? $overlay_color["color_picker"]
                    : "";
                $overContent =
                    '<div class="overlay-content" style="background: ' .
                    esc_attr($color_val) .
                    "; opacity: " .
                    esc_attr($overlay_opacity) .
                    "; mix-blend-mode: " .
                    esc_attr($overlay_blend_mode) .
                    ';"></div>';
            } else {
                $overlay_gradient = !empty($background_settings["overlay_gradient"])
                    ? $background_settings["overlay_gradient"]
                    : [];
                $ovelay_gradient_direction = !empty(
                    $background_settings["ovelay_gradient_direction"]
                )
                    ? $background_settings["ovelay_gradient_direction"]
                    : "180";
                $grad_key = !empty($overlay_gradient["gradient"])
                    ? $overlay_gradient["gradient"]
                    : "gradient-rainbow";
                $overContent = overlay_content(
                    $grad_key,
                    $overlay_opacity,
                    $ovelay_gradient_direction,
                    $overlay_blend_mode,
                );
            }
        }
    }

    return [
        "type" => $type,
        "bg" => $bg,
        "video_content" => $video_content,
        "overContent" => $overContent,
        "parallax" => $parallax,
        "border_section" => $border_section,
    ];
}

/**
 * Standard block section wrapper data.
 */
function setup_block_section(
    $block,
    $content,
    $base_class = "general-section",
    $additional_classes = [],
) {
    $block = is_array($block) ? $block : [];
    $content = is_array($content) ? $content : [];

    $id = isset($block["id"]) ? esc_attr($block["id"]) : "";
    $align_class = !empty($block["align"]) ? "align" . esc_attr($block["align"]) : "";

    $background_settings = get_block_field($content, "background_settings", []);
    $styles_settings = get_block_field($content, "styles_settings", []);

    $styles = styles_global_settings($styles_settings);
    $background = bg_global_settings($background_settings);

    $classes = array_merge(
        [
            $base_class,
            $align_class,
            isset($background["parallax"]) ? $background["parallax"] : "",
            isset($styles["custom_class"]) ? $styles["custom_class"] : "",
            isset($styles["content_width"]) ? "size-{$styles["content_width"]}" : "size-default",
            isset($background["border_section"]) ? $background["border_section"] : "none",
            isset($styles["custom_padding_mobile"]) ? $styles["custom_padding_mobile"] : "none",
        ],
        (array) $additional_classes,
    );

    $section_classes = trim(implode(" ", array_filter($classes)));

    $bg_val = isset($background["bg"]) ? $background["bg"] : "none";
    $text_align_val = isset($styles["text_align"]) ? $styles["text_align"] : "left";
    $margin_top = isset($styles["margin_top"]) ? $styles["margin_top"] : 0;
    $margin_bottom = isset($styles["margin_bottom"]) ? $styles["margin_bottom"] : 0;
    $z_index = isset($styles["z-index"]) ? $styles["z-index"] : "2";
    $padding_top = isset($styles["padding_top"]) ? $styles["padding_top"] : 0;
    $padding_bottom = isset($styles["padding_bottom"]) ? $styles["padding_bottom"] : 0;

    $inline_styles = trim(
        "background:{$bg_val} no-repeat; " .
            "text-align: {$text_align_val} !important; " .
            "margin-top: {$margin_top}px; " .
            "margin-bottom: {$margin_bottom}px; " .
            "z-index: {$z_index}; " .
            "padding: {$padding_top}px 0 {$padding_bottom}px;",
    );

    return [
        "id" => $id,
        "classes" => $section_classes,
        "styles" => $inline_styles,
        "section_anchor" => isset($styles["section_anchor"]) ? $styles["section_anchor"] : "",
        "video_content" => isset($background["video_content"]) ? $background["video_content"] : "",
        "overlay_content" => isset($background["overContent"]) ? $background["overContent"] : "",
    ];
}
