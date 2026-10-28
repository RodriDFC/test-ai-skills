<?php
if (!defined("ABSPATH")) {
    exit();
}

/**
 * Media Module
 *
 * Renders a media container that supports three types:
 *  - image   : single image via block_image()
 *  - video   : video player via video_module()
 *  - gallery : Swiper slider of multiple images
 *
 * Depends on: block_image() (image.php), video_module() (video.php)
 *
 * @package Kickmaker
 */

function media_module($media_block = "")
{
    if (empty($media_block) || !is_array($media_block)) {
        return;
    }

    $media_type_module = !empty($media_block["media_type_module"])
        ? $media_block["media_type_module"]
        : "";
    $image_module_media = !empty($media_block["image_module_media"])
        ? $media_block["image_module_media"]
        : [];
    $video_module_media = !empty($media_block["video_module_media"])
        ? $media_block["video_module_media"]
        : [];
    $gallery_module_media = !empty($media_block["gallery_module_media"])
        ? $media_block["gallery_module_media"]
        : [];
    ?>

    <div class="content-module-media">
        <?php if ($media_type_module === "image" && $image_module_media):

            $image_settings = !empty($image_module_media["image_settings"])
                ? $image_module_media["image_settings"]
                : "";
            $image_size = !empty($image_module_media["image_size"])
                ? $image_module_media["image_size"]
                : "full";
            ?>
            <figure class="content-media-img">
                <?php block_image($image_settings, $image_size); ?>
            </figure>

        <?php
        elseif ($media_type_module === "video" && $video_module_media): ?>
            <?php video_module($video_module_media); ?>

        <?php elseif (
            $media_type_module === "gallery" &&
            !empty($gallery_module_media) &&
            is_array($gallery_module_media)
        ): 
            $is_event = is_singular('event');
        ?>
            <div class="module-media-gallery js-module-media-gallery" data-is-event="<?php echo $is_event ? 'true' : 'false'; ?>">
                <div class="swiper module-media-gallery__slider">
                    <div class="swiper-wrapper">
                        <?php foreach ($gallery_module_media as $img):

                            $img_url = !empty($img["url"]) ? esc_url($img["url"]) : "";
                            $img_alt = !empty($img["alt"]) ? esc_attr($img["alt"]) : "";
                            $is_video = (!empty($img["type"]) && $img["type"] === "video") || (!empty($img["mime_type"]) && strpos($img["mime_type"], "video") !== false) || (is_string($img_url) && preg_match('/\.(mp4|webm|ogg)$/i', $img_url));
                            if (!$img_url) {
                                continue;
                            }
                            ?>
                            <div class="swiper-slide module-media-gallery__slide">
                                <figure class="module-media-gallery__figure">
                                    <?php if ($is_video): ?>
                                        <video class="module-media-gallery__video" autoplay muted loop playsinline webkit-playsinline>
                                            <source src="<?php echo $img_url; ?>" type="<?php echo esc_attr(!empty($img["mime_type"]) ? $img["mime_type"] : "video/mp4"); ?>">
                                        </video>
                                    <?php else: ?>
                                        <img src="<?php echo $img_url; ?>"
                                             alt="<?php echo $img_alt; ?>"
                                             class="module-media-gallery__img"
                                             loading="lazy">
                                    <?php endif; ?>
                                </figure>
                            </div>
                        <?php
                        endforeach; ?>
                    </div>
                </div>
                
                <?php if ($is_event): ?>
                    <div class="module-media-gallery__nav mt-4 d-flex gap-2 justify-content-start">
                        <button type="button" class="swiper-button-prev module-media-gallery__nav-prev" aria-label="Previous slide">
                            <svg width="11" height="18" viewBox="0 0 11 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.41406 0.707031L1.41406 8.70703L9.41406 16.707" stroke="white" stroke-width="2" />
                            </svg>
                        </button>
                        <button type="button" class="swiper-button-next module-media-gallery__nav-next" aria-label="Next slide">
                            <svg width="11" height="18" viewBox="0 0 11 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.707031 0.707031L8.70703 8.70703L0.707031 16.707" stroke="white" stroke-width="2" />
                            </svg>
                        </button>
                    </div>
                <?php else: ?>
                    <div class="swiper-pagination module-media-gallery__pagination"></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
<?php
}
