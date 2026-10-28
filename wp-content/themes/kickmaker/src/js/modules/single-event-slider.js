export function initSingleEventSlider(container = document) {
    // Restrict this slider logic exclusively to the single event page (or ACF backend)
    const isSingleEventPage = document.querySelector("main.single-event") !== null;
    const isACF = typeof window !== "undefined" && window.acf;

    if (!isSingleEventPage && !isACF) {
        return;
    }

    const root = container instanceof Element ? container : document;
    const galleries = root.querySelectorAll('.js-module-media-gallery[data-is-event="true"]');

    if (!galleries.length) {
        return;
    }

    if (typeof window.Swiper === "undefined") {
        setTimeout(() => initSingleEventSlider(container), 50);
        return;
    }

    galleries.forEach(gallery => {
        const sliderEl = gallery.querySelector(".module-media-gallery__slider");
        const nextEl = gallery.querySelector(".module-media-gallery__nav-next");
        const prevEl = gallery.querySelector(".module-media-gallery__nav-prev");

        if (!sliderEl) {
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
            loop: slideCount > 1,
            slidesPerView: 1,
            speed: 700,
            effect: "fade",
            fadeEffect: {
                crossFade: true
            },
            autoplay:
                slideCount > 1
                    ? {
                          delay: 5500,
                          disableOnInteraction: false
                      }
                    : false,
            allowTouchMove: true,
            simulateTouch: true,
            grabCursor: true,
            observer: true,
            observeParents: true,
            pagination: false,
            navigation:
                nextEl && prevEl
                    ? {
                          nextEl: nextEl,
                          prevEl: prevEl
                      }
                    : false,
            on: {
                init: function () {
                    if (typeof window.AOS !== "undefined") {
                        window.AOS.refresh();
                    }
                }
            }
        });

        sliderEl.__swiper = swiper;

        if (nextEl) {
            nextEl.addEventListener("click", function (e) {
                e.preventDefault();
                const instance = sliderEl.swiper || sliderEl.__swiper;
                if (instance) instance.slideNext();
            });
        }
        if (prevEl) {
            prevEl.addEventListener("click", function (e) {
                e.preventDefault();
                const instance = sliderEl.swiper || sliderEl.__swiper;
                if (instance) instance.slidePrev();
            });
        }
    });
}

if (typeof window !== "undefined" && window.acf) {
    window.acf.addAction("render_block_preview", function ($el) {
        const el = $el && $el[0] ? $el[0] : $el;
        if (el && el.querySelectorAll) {
            initSingleEventSlider(el);
        }
    });
}
