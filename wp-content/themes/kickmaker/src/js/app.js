import "../scss/main.scss";
import "swiper/swiper-bundle.css";
import "aos/dist/aos.css";

import Swiper from "swiper/bundle";
import AOS from "aos";
import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

//import "bootstrap/js/dist/collapse";

window.Swiper = Swiper;
window.AOS = AOS;
window.gsap = gsap;
window.ScrollTrigger = ScrollTrigger;

//modules
import { initHeaderModule } from "./modules/header";
import { initVideoModule } from "./modules/video";
import { initMediaGalleryModule } from "./modules/media-gallery";
import { initSingleEventSlider } from "./modules/single-event-slider";
import { initPrivacyScrollspy } from "./modules/privacy-scrollspy";
import { initGallerySlider } from "./modules/slider-gallery";
import { initPostTocModule } from "./modules/post-toc";
import { initFormFileInputs } from "./modules/forms";
import { initSinglePostHeroModule } from "./modules/single-post-hero";

window.initMediaGalleryModule = initMediaGalleryModule;

//function main
const initApp = () => {
    AOS.init();
    initHeaderModule();
    initVideoModule();
    initMediaGalleryModule();
    initSingleEventSlider();
    initPrivacyScrollspy();
    initGallerySlider();
    initPostTocModule();
    initFormFileInputs();
    initSinglePostHeroModule();
};

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initApp);
} else {
    initApp();
}
