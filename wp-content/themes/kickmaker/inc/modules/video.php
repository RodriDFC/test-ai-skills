<?php
if (!defined("ABSPATH")) {
    exit();
}

/**
 * Video Module
 *
 * Renders a video element supporting three source types:
 *  - file  : MP4 file with optional autoplay or play button
 *  - embed : iframe/embed code (YouTube, Vimeo, etc.)
 *  - script: custom embed script
 *
 * @package Kickmaker
 */

function video_module($video_block = "")
{
    ?>
    <div class="content-video-media-module">
        <?php if ($video_block) {

            $type_video = !empty($video_block["video_type"]) ? $video_block["video_type"] : "";
            $video_file = !empty($video_block["video_mp4_module"])
                ? $video_block["video_mp4_module"]
                : "";
            $video_script = !empty($video_block["video_script"])
                ? $video_block["video_script"]
                : "";
            $video_url = !empty($video_block["video_embed"]) ? $video_block["video_embed"] : "";
            $video_placeholder = !empty($video_block["video_placeholder"])
                ? $video_block["video_placeholder"]
                : "";
            $autoplay = !empty($video_block["autoplay"]);

            if ($type_video === "file" && $video_file) { ?>
                <div class="content-video <?php echo $autoplay
                    ? "content-video--autoplay"
                    : ""; ?>">
                    <video poster="<?php echo esc_url($video_placeholder); ?>"
                           tabindex="0"
                           playsinline
                           webkit-playsinline
                           <?php echo $autoplay
                               ? 'autoplay muted loop x-webkit-airplay="deny" disablePictureInPicture preload="metadata"'
                               : ""; ?>>
                        <source src="<?php echo esc_url($video_file); ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                    <?php if (!$autoplay): ?>
                        <div id="play" class="play" role="button" tabindex="0"></div>
                    <?php endif; ?>
                </div>

                <?php if ($autoplay): ?>
                    <script>
                        (function () {
                            var videoEl = document.currentScript.previousElementSibling.querySelector('video');
                            if (videoEl) {
                                videoEl.muted = true;
                                videoEl.setAttribute('muted', '');
                                videoEl.setAttribute('playsinline', '');
                                var playPromise = videoEl.play();
                                if (playPromise !== undefined) {
                                    playPromise.catch(function () {
                                        videoEl.muted = true;
                                        videoEl.play();
                                    });
                                }
                                videoEl.addEventListener('canplay', function () {
                                    if (videoEl.paused) {
                                        videoEl.muted = true;
                                        videoEl.play();
                                    }
                                });
                            }
                        })();
                    </script>
                <?php endif; ?>

                <?php if (!$autoplay): ?>
                    <script>
                        jQuery(document).ready(function () {
                            if (window.videoModuleEventsBound) return;
                            window.videoModuleEventsBound = true;

                            jQuery(document).on('click', '.content-video .play', function () {
                                var container = jQuery(this).closest('.content-video');
                                var video = container.find('video')[0];
                                if (video) {
                                    video.play();
                                    jQuery(this).hide();
                                    video.setAttribute('controls', 'true');
                                    container.addClass('playing');
                                }
                            });

                            jQuery(document).on('play', '.content-video video', function () {
                                var container = jQuery(this).closest('.content-video');
                                var play = container.find('.play')[0];
                                if (play) play.style.display = 'none';
                                this.setAttribute('controls', 'true');
                                container.addClass('playing');
                            });

                            jQuery(document).on('pause', '.content-video video', function () {
                                var container = jQuery(this).closest('.content-video');
                                var play = container.find('.play')[0];
                                this.removeAttribute('controls');
                                if (play) play.style.display = 'block';
                            });
                        });
                    </script>
                <?php endif; ?>

            <?php } elseif ($type_video === "embed" && $video_url) { ?>
                <?php echo $video_url; ?>
            <?php } elseif ($type_video === "script" && $video_script) { ?>
                <?php echo $video_script; ?>
            <?php } else { ?>
                <p>You did not fill in the data</p>
            <?php }
            ?>
        <?php
        } ?>
    </div>
<?php
}
