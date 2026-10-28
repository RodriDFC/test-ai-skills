<?php
if (!defined("ABSPATH")) {
    exit(); // Exit if accessed directly.
}
get_header();

/** @var array $block */
$block = isset($block) && is_array($block) ? $block : [];

//var_dump($block);

$content = get_field("page_404", "options");
if ($content) {
    $block_setup = setup_block_section(
        [],
        $content,
        "page-error-404 general-section js-page-error-404",
    );

    // Extract background color manually because setup_block_section ignores color if type is 'video'
    $bg_settings = isset($content["background_settings"]) ? $content["background_settings"] : [];
    $custom_bg_color = "";
    if (
        isset($bg_settings["color"]["color_picker"]) &&
        !empty($bg_settings["color"]["color_picker"])
    ) {
        $custom_bg_color = $bg_settings["color"]["color_picker"];
    }
} else {
    return;
}

// Eyebrow Block
$eyebrow_block = isset($content["eyebrow_block"]) ? $content["eyebrow_block"] : [];
$eyebrow_color = !empty($eyebrow_block["eyebrow_color"])
    ? esc_attr($eyebrow_block["eyebrow_color"])
    : "";
$eyebrow = !empty($eyebrow_block["eyebrow"])
    ? '<span class="eyebrow text-' .
        $eyebrow_color .
        '">' .
        esc_html($eyebrow_block["eyebrow"]) .
        "</span>"
    : "";

// Heading Block
$heading_block = isset($content["heading_block"]) ? $content["heading_block"] : [];
$ht = !empty($heading_block["heading_size"]) ? esc_html($heading_block["heading_size"]) : "h2";
$heading_color = !empty($heading_block["heading_color"])
    ? esc_attr($heading_block["heading_color"])
    : "";
$heading = !empty($heading_block["heading"])
    ? "<" .
        $ht .
        ' class="heading text-' .
        $heading_color .
        '">' .
        wp_kses_post($heading_block["heading"]) .
        "</" .
        $ht .
        ">"
    : "";

// Content Section
// Content Text Block
$content_section = isset($content["content_block"]) ? $content["content_block"] : [];
$style_text = isset($content_section["style_text"]) ? $content_section["style_text"] : [];
$text_size = !empty($style_text["text_size"]) ? esc_attr($style_text["text_size"]) : "default-text";
$text_color = !empty($style_text["text_color"]) ? esc_attr($style_text["text_color"]) : "";
$content_text = !empty($content_section["content"])
    ? '<div class="content-text ' .
        $text_size .
        " text-" .
        $text_color .
        '">' .
        wp_kses_post($content_section["content"]) .
        "</div>"
    : "";

// Buttons Block
$buttons = get_block_field($content, "buttons_block.buttons", []);
$buttons_justification = get_block_field($content, "buttons_block.buttons_justification", "");

// Navigation Links
$nav_links = isset($content["navigation_links"]["navigation_cards"])
    ? $content["navigation_links"]["navigation_cards"]
    : [];

$decorative_bg_image = $content["decorative_bg_image"] ?? false;
?>

<section id="<?php echo esc_attr($block_setup["id"]); ?>" class="<?php echo esc_attr(
    $block_setup["classes"],
); ?>" style="<?php echo esc_attr($block_setup["styles"]); ?> <?php if ($custom_bg_color) {
     echo "background-color: " . esc_attr($custom_bg_color) . " !important;";
 } ?>">
    <div class="page-error-404__hero-wrapper" style="position: relative;">
        <?php echo $block_setup["video_content"]; ?>
        <?php echo $block_setup["overlay_content"]; ?>
        <div class="container page-error-404__container" id="<?php echo esc_attr(
            $block_setup["section_anchor"],
        ); ?>">
            <div class="page-error-404__hero">
                <div class="page-error-404__huge-text">
                    <svg width="837" height="327" viewBox="0 0 837 327" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M804.398 4.90625V210.195H836.498V261.178H804.398V321.091H735.85V261.178H579.812V209.865L737.379 4.90625H804.398ZM738.521 97.8672L653.363 209.393L652.75 210.195H739.418V96.6914L738.521 97.8672Z" stroke="#33ABC6"/>
<path d="M225.086 4.90625V210.195H257.186V261.178H225.086V321.091H156.537V261.178H0.5V209.865L158.066 4.90625H225.086ZM159.208 97.8672L74.0508 209.393L73.4375 210.195H160.105V96.6914L159.208 97.8672Z" fill="black" stroke="#33ABC6"/>
<path d="M419.08 3.71973C421.411 3.71973 423.718 3.77282 426.002 3.87891V59.8867C423.762 59.6481 421.454 59.5273 419.08 59.5273C404.256 59.5274 392.048 64.3241 382.456 73.916C372.864 83.2173 365.743 95.7163 361.093 111.412C356.442 127.108 354.116 144.403 354.116 163.296C354.116 182.77 356.442 200.647 361.093 216.924C365.743 233.201 372.864 246.281 382.456 256.164C392.339 265.756 404.547 270.552 419.08 270.552C421.454 270.552 423.762 270.423 426.002 270.17V326.195C423.718 326.304 421.41 326.359 419.08 326.359C392.92 326.359 369.522 319.529 348.885 305.868C328.538 291.916 312.551 272.731 300.925 248.315C289.298 223.609 283.484 195.414 283.484 163.731C283.484 132.049 289.298 104.291 300.925 80.4561C312.551 56.3307 328.538 37.5826 348.885 24.2119C369.522 10.5507 392.92 3.71978 419.08 3.71973Z" fill="black"/>
<mask id="path-4-inside-1_12052_15604" fill="white">
<path d="M419.078 326.36C392.918 326.36 369.52 319.529 348.882 305.868C328.536 291.916 312.549 272.732 300.922 248.316C289.296 223.609 283.482 195.415 283.482 163.732C283.482 132.049 289.296 104.291 300.922 80.456C312.549 56.3307 328.536 37.5827 348.882 24.212C369.52 10.5507 392.918 3.71999 419.078 3.71999C445.529 3.71999 468.928 10.5507 489.274 24.212C509.621 37.5827 525.608 56.3307 537.234 80.456C548.861 104.291 554.674 132.049 554.674 163.732C554.674 195.705 548.861 223.9 537.234 248.316C525.608 272.732 509.476 291.916 488.838 305.868C468.492 319.529 445.238 326.36 419.078 326.36ZM419.078 270.552C433.902 270.552 446.11 265.611 455.702 255.728C465.294 245.845 472.416 232.765 477.066 216.488C481.717 200.211 484.042 182.48 484.042 163.296C484.042 144.112 481.717 126.672 477.066 110.976C472.416 95.28 465.294 82.7813 455.702 73.48C446.11 64.1786 433.902 59.528 419.078 59.528C404.254 59.528 392.046 64.324 382.454 73.916C372.862 83.2173 365.741 95.716 361.09 111.412C356.44 127.108 354.114 144.403 354.114 163.296C354.114 182.771 356.44 200.647 361.09 216.924C365.741 233.201 372.862 246.281 382.454 256.164C392.337 265.756 404.545 270.552 419.078 270.552Z"/>
</mask>
<path d="M348.882 305.868L346.055 309.992L346.088 310.015L346.122 310.037L348.882 305.868ZM300.922 248.316L296.398 250.445L296.403 250.455L296.408 250.466L300.922 248.316ZM300.922 80.456L305.416 82.6481L305.421 82.6374L305.427 82.6267L300.922 80.456ZM348.882 24.212L351.628 28.3905L351.635 28.3859L351.642 28.3813L348.882 24.212ZM489.274 24.212L486.487 28.3631L486.508 28.3769L486.528 28.3905L489.274 24.212ZM537.234 80.456L532.73 82.6267L532.735 82.6374L532.741 82.6481L537.234 80.456ZM537.234 248.316L541.749 250.466V250.466L537.234 248.316ZM488.838 305.868L491.626 310.019L491.632 310.015L491.639 310.01L488.838 305.868ZM455.702 255.728L452.114 252.246V252.246L455.702 255.728ZM477.066 216.488L481.874 217.862V217.862L477.066 216.488ZM477.066 110.976L481.86 109.556V109.556L477.066 110.976ZM455.702 73.48L452.222 77.0695V77.0695L455.702 73.48ZM382.454 73.916L385.935 77.5055L385.963 77.4787L385.99 77.4515L382.454 73.916ZM361.09 111.412L356.296 109.992L361.09 111.412ZM382.454 256.164L378.866 259.646L378.918 259.7L378.972 259.752L382.454 256.164ZM419.078 326.36V321.36C393.841 321.36 371.413 314.787 351.642 301.699L348.882 305.868L346.122 310.037C367.626 324.272 391.996 331.36 419.078 331.36V326.36ZM348.882 305.868L351.71 301.744C332.158 288.337 316.722 269.866 305.437 246.166L300.922 248.316L296.408 250.466C308.376 275.598 324.913 295.495 346.055 309.992L348.882 305.868ZM300.922 248.316L305.446 246.187C294.18 222.245 288.482 194.796 288.482 163.732H283.482H278.482C278.482 196.033 284.412 224.974 296.398 250.445L300.922 248.316ZM283.482 163.732H288.482C288.482 132.663 294.181 105.68 305.416 82.6481L300.922 80.456L296.428 78.2639C284.41 102.902 278.482 131.436 278.482 163.732H283.482ZM300.922 80.456L305.427 82.6267C316.7 59.2335 332.113 41.2147 351.628 28.3905L348.882 24.212L346.136 20.0334C324.958 33.9506 308.398 53.4278 296.418 78.2853L300.922 80.456ZM348.882 24.212L351.642 28.3813C371.413 15.2933 393.841 8.72 419.078 8.72V3.72V-1.28C391.996 -1.28 367.626 5.80798 346.122 20.0427L348.882 24.212ZM419.078 3.72V8.72C444.619 8.72 467.031 15.2994 486.487 28.3631L489.274 24.212L492.062 20.0609C470.825 5.80191 446.439 -1.28 419.078 -1.28V3.72ZM489.274 24.212L486.528 28.3905C506.044 41.2147 521.456 59.2335 532.73 82.6267L537.234 80.456L541.739 78.2853C529.759 53.4278 513.199 33.9506 492.02 20.0334L489.274 24.212ZM537.234 80.456L532.741 82.6481C543.975 105.68 549.674 132.663 549.674 163.732H554.674H559.674C559.674 131.436 553.747 102.902 541.728 78.2639L537.234 80.456ZM554.674 163.732H549.674C549.674 195.096 543.974 222.533 532.72 246.166L537.234 248.316L541.749 250.466C553.748 225.267 559.674 196.315 559.674 163.732H554.674ZM537.234 248.316L532.72 246.166C521.443 269.848 505.874 288.316 486.038 301.726L488.838 305.868L491.639 310.01C513.078 295.516 529.772 275.616 541.749 250.466L537.234 248.316ZM488.838 305.868L486.051 301.717C466.585 314.787 444.316 321.36 419.078 321.36V326.36V331.36C446.161 331.36 470.398 324.272 491.626 310.019L488.838 305.868ZM419.078 270.552V275.552C435.14 275.552 448.68 270.142 459.29 259.21L455.702 255.728L452.114 252.246C443.541 261.079 432.665 265.552 419.078 265.552V270.552ZM455.702 255.728L459.29 259.21C469.574 248.615 477.048 234.752 481.874 217.862L477.066 216.488L472.259 215.114C467.783 230.778 461.015 243.075 452.114 252.246L455.702 255.728ZM477.066 216.488L481.874 217.862C486.668 201.083 489.042 182.884 489.042 163.296H484.042H479.042C479.042 182.076 476.766 199.339 472.259 215.114L477.066 216.488ZM484.042 163.296H489.042C489.042 143.711 486.669 125.784 481.86 109.556L477.066 110.976L472.272 112.396C476.765 127.56 479.042 144.513 479.042 163.296H484.042ZM477.066 110.976L481.86 109.556C477.02 93.2198 469.517 79.9108 459.183 69.8905L455.702 73.48L452.222 77.0695C461.072 85.6518 467.811 97.3401 472.272 112.396L477.066 110.976ZM455.702 73.48L459.183 69.8905C448.552 59.582 435.047 54.528 419.078 54.528V59.528V64.528C432.758 64.528 443.668 68.7753 452.222 77.0695L455.702 73.48ZM419.078 59.528V54.528C403.064 54.528 389.54 59.7592 378.919 70.3805L382.454 73.916L385.99 77.4515C394.553 68.8888 405.445 64.528 419.078 64.528V59.528ZM382.454 73.916L378.974 70.3265C368.64 80.3468 361.137 93.6558 356.296 109.992L361.09 111.412L365.884 112.832C370.345 97.7762 377.085 86.0879 385.935 77.5055L382.454 73.916ZM361.09 111.412L356.296 109.992C351.49 126.213 349.114 143.994 349.114 163.296H354.114H359.114C359.114 144.811 361.389 128.003 365.884 112.832L361.09 111.412ZM354.114 163.296H349.114C349.114 183.167 351.487 201.512 356.283 218.298L361.09 216.924L365.898 215.55C361.392 199.781 359.114 182.374 359.114 163.296H354.114ZM361.09 216.924L356.283 218.298C361.109 235.188 368.583 249.051 378.866 259.646L382.454 256.164L386.042 252.682C377.142 243.511 370.373 231.214 365.898 215.55L361.09 216.924ZM382.454 256.164L378.972 259.752C389.843 270.303 403.324 275.552 419.078 275.552V270.552V265.552C405.766 265.552 394.831 261.209 385.937 252.576L382.454 256.164Z" fill="#33ABC6" mask="url(#path-4-inside-1_12052_15604)"/>
                    </svg>
                </div>
                <?php echo $heading; ?>
                <?php echo $content_text; ?>
            </div>
        </div>
    </div>

    <div class="container page-error-404__container page-error-404__bottom-section">
        <?php if (!empty($nav_links)): ?>
            <div class="row g-4 page-error-404__nav-grid justify-content-center">
                <?php foreach ($nav_links as $card):

                    $card_heading_block = isset($card["heading_card"]) ? $card["heading_card"] : [];
                    $card_heading = isset($card_heading_block["heading"])
                        ? $card_heading_block["heading"]
                        : "";
                    $card_heading_size = !empty($card_heading_block["heading_size"])
                        ? $card_heading_block["heading_size"]
                        : "h4";
                    $card_heading_color = !empty($card_heading_block["heading_color"])
                        ? $card_heading_block["heading_color"]
                        : "";

                    $card_content_block = isset($card["content_card"]) ? $card["content_card"] : [];
                    $card_content = isset($card_content_block["content"])
                        ? $card_content_block["content"]
                        : "";
                    $card_style_text = isset($card_content_block["style_text"])
                        ? $card_content_block["style_text"]
                        : [];
                    $card_text_size = !empty($card_style_text["text_size"])
                        ? esc_attr($card_style_text["text_size"])
                        : "default-text";
                    $card_text_color = !empty($card_style_text["text_color"])
                        ? esc_attr($card_style_text["text_color"])
                        : "";

                    $card_link = isset($card["link_card"]) ? $card["link_card"] : [];
                    $url = !empty($card_link["url"]) ? $card_link["url"] : "#";
                    $target = !empty($card_link["target"])
                        ? ' target="' . esc_attr($card_link["target"]) . '"'
                        : "";
                    ?>
                    <div class="col-12 col-sm-6 col-lg-3">
                        <a href="<?php echo esc_url(
                            $url,
                        ); ?>"<?php echo $target; ?> class="page-error-404__nav-card h-100">
                            <div class="page-error-404__card-icon">
                                <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.23828 12.5L0 25V0L8.23828 12.5ZM24.7148 12.5L8.23828 25V0L24.7148 12.5Z" fill="currentColor"/>
                                </svg>
                            </div>
                            <<?php echo esc_html(
                                $card_heading_size,
                            ); ?> class="page-error-404__card-heading <?php echo !empty(
     $card_heading_color
 )
     ? "text-" . esc_attr($card_heading_color)
     : ""; ?>"><?php echo esc_html($card_heading); ?></<?php echo esc_html($card_heading_size); ?>>
                            <div class="page-error-404__card-text <?php echo $card_text_size; ?> <?php echo !empty(
     $card_text_color
 )
     ? "text-" . $card_text_color
     : ""; ?>">
                                <?php echo wp_kses_post($card_content); ?>
                            </div>
                        </a>
                    </div>
                <?php
                endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($buttons)): ?>
            <div class="page-error-404__buttons">
                <?php block_buttons($buttons, $buttons_justification); ?>
            </div>
        <?php endif; ?>
    </div>
    <?php if ($decorative_bg_image) { ?>
        <figure class="image-figure">
            <?php echo wp_get_attachment_image($decorative_bg_image["ID"], "full", false, [
                "class" => "img-fluid",
            ]); ?>
        </figure>
    <?php } ?>
</section>


<?php get_footer();
?>
