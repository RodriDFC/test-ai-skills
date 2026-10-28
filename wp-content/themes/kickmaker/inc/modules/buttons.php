<?php
if (!defined("ABSPATH")) {
    exit();
}

/**
 * Buttons Module
 *
 * Renders a group of CTA buttons with icon support.
 * Supports: arrow icon, custom icon, no icon.
 *
 * @package Kickmaker
 */

function block_buttons($buttons, $buttons_justification)
{
    if (!empty($buttons) && $buttons_justification) { ?>
        <div class="content-buttons buttons-justification-<?php echo esc_attr(
            $buttons_justification,
        ); ?>">
            <?php foreach ($buttons as $item) {

                $button_link = isset($item["button_link"]) ? $item["button_link"] : "";
                $button_style = isset($item["button_style"]) ? $item["button_style"] : "";
                $button_color = isset($item["button_color"]) ? $item["button_color"] : "";
                $button_text_color = isset($item["button_text_color"])
                    ? $item["button_text_color"]
                    : "";
                $button_arrow = isset($item["button_arrow"]) ? $item["button_arrow"] : "none";
                $button_hover_white = isset($item["button_hover_white"])
                    ? $item["button_hover_white"]
                    : "";
                $button_size = isset($item["button_size"]) ? $item["button_size"] : "";
                $inline_style = "";
                if (!empty($button_text_color)) {
                    $inline_style .= "--btn-color: " . esc_attr($button_text_color) . ";";
                }
                if (!empty($button_color)) {
                    $inline_style .= "--btn-bg: " . esc_attr($button_color) . ";";
                }
                ?>
                <?php if ($button_link) { ?>
                    <a class="btn-site btn-<?php echo esc_attr(
                        $button_style,
                    ); ?> <?php echo $button_arrow === "arrow"
     ? " btn-has-arrow"
     : ""; ?> <?php echo $button_hover_white ? " btn-hover-white" : ""; ?> <?php echo $button_size
     ? " btn-site-" . esc_attr($button_size)
     : ""; ?>"
                        target="<?php echo esc_attr($button_link["target"])
                            ? $button_link["target"]
                            : "_self"; ?>"
                        href="<?php echo esc_url($button_link["url"]); ?>"
                        style="<?php echo esc_attr($inline_style); ?>">
                        <span><?php echo esc_html($button_link["title"]); ?></span>
                        <?php if ($button_arrow === "arrow") { ?>
                            <svg class="btn-arrow" xmlns="http://www.w3.org/2000/svg" width="19" height="16" viewBox="0 0 18.882 15.278" aria-hidden="true" focusable="false">
                                <g transform="translate(-1175 -3141.284)">
                                    <path d="M37.607,18H55.238" transform="translate(1137.394 3130.923)" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="2.5" />
                                    <path d="M7.5,21.01l6.755-6.755L7.5,7.5" transform="translate(1178.377 3134.668)" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="2.5" />
                                </g>
                            </svg>
                        <?php } ?>
                    </a>
                <?php } ?>
            <?php
            } ?>
        </div>
<?php }
}
