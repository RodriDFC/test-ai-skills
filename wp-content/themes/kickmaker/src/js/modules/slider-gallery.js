export function initGallerySlider() {
    document.querySelectorAll(".module-media-gallery__slider").forEach(function (el) {
        if (el.swiper) {
            el.swiper.destroy(true, true);
        }

        new Swiper(el, {
            loop: true,
            slidesPerView: 1,
            speed: 700,

            effect: "fade",

            fadeEffect: {
                crossFade: true
            },

            autoplay: {
                delay: 5500,
                disableOnInteraction: false
            },

            pagination: {
                el: el
                    .closest(".module-media-gallery")
                    ?.querySelector(".module-media-gallery__pagination"),
                clickable: true
            },

            allowTouchMove: false,
            simulateTouch: false
        });
    });
}
