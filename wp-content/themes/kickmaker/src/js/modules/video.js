/**
 * Video Module
 *
 * Handles play/pause interaction for .content-video elements
 * that use the native <video> tag with a .play button overlay.
 * Works for both autoplay and manual-play modes.
 */

export function initVideoModule() {
    // ── Manual play (no autoplay) ─────────────────────────────────────────
    document.querySelectorAll(".content-video:not(.content-video--autoplay)").forEach(container => {
        const video = container.querySelector("video");
        const playBtn = container.querySelector(".play");

        if (!video) return;

        // Start muted (browsers require this for autoplay policies; user can unmute via controls)
        video.muted = true;
        video.setAttribute("muted", "");

        // Click on the play overlay button
        if (playBtn) {
            playBtn.addEventListener("click", () => {
                video.play();
            });
        }

        // When video starts playing
        video.addEventListener("play", () => {
            if (playBtn) playBtn.style.display = "none";
            video.setAttribute("controls", "");
            container.classList.add("playing");
        });

        // When video is paused
        video.addEventListener("pause", () => {
            video.removeAttribute("controls");
            if (playBtn) playBtn.style.display = "";
            container.classList.remove("playing");
        });

        // When video ends
        video.addEventListener("ended", () => {
            video.removeAttribute("controls");
            if (playBtn) playBtn.style.display = "";
            container.classList.remove("playing");
        });
    });

    // ── Autoplay ──────────────────────────────────────────────────────────
    document.querySelectorAll(".content-video--autoplay video").forEach(video => {
        video.muted = true;
        video.setAttribute("muted", "");

        const tryPlay = () => {
            const p = video.play();
            if (p !== undefined) {
                p.catch(() => {
                    video.muted = true;
                    video.play();
                });
            }
        };

        tryPlay();
        video.addEventListener("canplay", () => {
            if (video.paused) tryPlay();
        });
    });
}
