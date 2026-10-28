/**
 * Hero Floating Image Block Script.
 *
 * Scoped interactive behavior for mobile selector navigation.
 */

(function () {
    "use strict";

    function initHeroSelector(block) {
        if (!block || block.getAttribute("data-hero-initialized") === "true") {
            return;
        }

        var selector = block.querySelector(".js-hero-selector");
        if (!selector) {
            return;
        }

        block.setAttribute("data-hero-initialized", "true");

        selector.addEventListener("change", function () {
            var selectedOption = selector.options[selector.selectedIndex];
            if (!selectedOption) {
                return;
            }

            var url = selectedOption.value;
            var target = selectedOption.getAttribute("data-target") || "_self";

            if (!url) {
                return;
            }

            // Handle same-page hash links with smooth scroll
            if (url.charAt(0) === "#") {
                var targetId = url.substring(1);
                var targetElement = document.getElementById(targetId);

                if (targetElement) {
                    targetElement.scrollIntoView({ behavior: "smooth" });
                    if (window.history && window.history.pushState) {
                        window.history.pushState(null, "", url);
                    } else {
                        window.location.hash = url;
                    }
                } else {
                    window.location.hash = url;
                }
            } else if (target === "_blank") {
                window.open(url, "_blank", "noopener,noreferrer");
            } else {
                window.location.href = url;
            }
        });
    }

    function initAllHeroBlocks() {
        var blocks = document.querySelectorAll(".hero-floating-image");
        for (var i = 0; i < blocks.length; i++) {
            initHeroSelector(blocks[i]);
        }
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", initAllHeroBlocks);
    } else {
        initAllHeroBlocks();
    }
})();
