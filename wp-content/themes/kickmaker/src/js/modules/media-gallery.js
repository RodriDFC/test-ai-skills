/**
 * Media Gallery Module (Swiper)
 *
 * Initializes Swiper sliders for all .js-module-media-gallery elements.
 */

export function initMediaGalleryModule(container = document) {
    const root = container instanceof Element ? container : document;
    const galleries = root.querySelectorAll(".js-module-media-gallery");

    if (!galleries.length) {
        return;
    }

    if (typeof window.Swiper === "undefined") {
        setTimeout(() => initMediaGalleryModule(container), 50);
        return;
    }

    galleries.forEach(gallery => {
        const sliderEl = gallery.querySelector(".module-media-gallery__slider");
        const paginationEl = gallery.querySelector(".module-media-gallery__pagination");

        if (!sliderEl) {
            return;
        }

        const isEvent = gallery.getAttribute("data-is-event") === "true";
        if (isEvent) {
            // Leave this for the single-event-slider module
            return;
        }

        // Always destroy existing instance to avoid bugs on re-initialization (e.g., ACF previews)
        if (sliderEl.swiper) {
            sliderEl.swiper.destroy(true, true);
        } else if (sliderEl.__swiper) {
            sliderEl.__swiper.destroy(true, true);
            delete sliderEl.__swiper;
        }

        const slides = sliderEl.querySelectorAll(".swiper-slide");
        const slideCount = slides.length;

        const swiper = new window.Swiper(sliderEl, {
            slidesPerView: 1,
            spaceBetween: 0,
            speed: 600,
            grabCursor: true,
            loop: slideCount > 1,
            autoplay:
                slideCount > 1
                    ? {
                          delay: 5000,
                          disableOnInteraction: false
                      }
                    : false,
            pagination: {
                el: paginationEl,
                clickable: true
            },
            on: {
                init: function () {
                    if (typeof window.AOS !== "undefined") {
                        window.AOS.refresh();
                    }
                }
            }
        });

        sliderEl.__swiper = swiper;
    });
}

if (typeof window !== "undefined" && window.acf) {
    window.acf.addAction("render_block_preview", function ($el) {
        const el = $el && $el[0] ? $el[0] : $el;
        if (el && el.querySelectorAll) {
            el.querySelectorAll(".js-module-media-gallery").forEach(gallery => {
                const sliderEl = gallery.querySelector(".module-media-gallery__slider");
                if (sliderEl) {
                    if (sliderEl.swiper) {
                        sliderEl.swiper.destroy(true, true);
                    } else if (sliderEl.__swiper) {
                        sliderEl.__swiper.destroy(true, true);
                        delete sliderEl.__swiper;
                    }
                }
            });
            initMediaGalleryModule(el);
        }
    });
}
