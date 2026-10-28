// Header behaviour: scrolled state, header buttons relocation on mobile and the
// mega menu card accordion (mobile only).

// Matches the `lg` Bootstrap breakpoint. Keep in sync with the Max Mega Menu
// responsive breakpoint (Mega Menu > Menu Themes) or the buttons jump at the wrong width.
const MOBILE_QUERY = "(max-width: 991.98px)";

function initScrollState(header) {
    let ticking = false;

    const update = () => {
        header.classList.toggle("is-scrolled", window.scrollY > 10);
        ticking = false;
    };

    update();

    window.addEventListener(
        "scroll",
        () => {
            if (ticking) return;
            ticking = true;
            window.requestAnimationFrame(update);
        },
        { passive: true }
    );
}

// Moves a header element into/out of the mobile menu based on viewport width.
function relocateIntoMobileMenu(selector, itemClass, position, onSync) {
    const element = document.querySelector(selector);
    const megaMenu = document.querySelector("#mega-menu-primary");

    if (!element || !megaMenu) return;

    const desktopParent = element.parentElement;
    const menuItem = document.createElement("li");
    menuItem.className = `mega-menu-item mega-menu-item-type-custom mega-align-bottom-left mega-menu-flyout ${itemClass}`;

    const query = window.matchMedia(MOBILE_QUERY);

    const sync = () => {
        if (query.matches) {
            if (!menuItem.parentElement) {
                menuItem.appendChild(element);
                megaMenu[position](menuItem);
            }
        } else if (menuItem.parentElement) {
            desktopParent.appendChild(element);
            menuItem.remove();
        }

        if (onSync) onSync(element, query.matches);
    };

    sync();
    query.addEventListener("change", sync);
}

// Numbered cards and link columns inside a mega menu panel collapse below lg;
// clicks on the head toggle them.
function initMegaMenuCards() {
    const cards = Array.from(
        document.querySelectorAll(".mm-numbered-card, .mm-link-column")
    ).filter(card => card.querySelector(".mm-card-toggle"));

    if (!cards.length) return;

    const setCardState = (card, expanded) => {
        const toggle = card.querySelector(".mm-card-toggle");

        card.classList.toggle("is-collapsed", !expanded);

        if (toggle) {
            toggle.setAttribute("aria-expanded", expanded ? "true" : "false");
        }
    };

    cards.forEach(card => {
        const head = card.querySelector(".mm-numbered-card-head, .mm-link-column-head");

        if (!head) return;

        head.addEventListener("click", () => {
            setCardState(card, card.classList.contains("is-collapsed"));
        });
    });

    const query = window.matchMedia(MOBILE_QUERY);

    // Collapsed on mobile, always open from lg up.
    const sync = () => cards.forEach(card => setCardState(card, !query.matches));

    sync();
    query.addEventListener("change", sync);
}

// Syncs header and WordPress admin bar heights to CSS custom properties.
function initHeaderOffset(header) {
    const update = () => {
        const headerHeight = header.offsetHeight;
        document.documentElement.style.setProperty("--header-height", `${headerHeight}px`);

        const adminBar = document.getElementById("wpadminbar");
        if (adminBar) {
            const isMobileAbsolute = window.innerWidth <= 600;
            const adminBarHeight = isMobileAbsolute ? 0 : adminBar.offsetHeight;
            document.documentElement.style.setProperty("--admin-bar-height", `${adminBarHeight}px`);
        } else {
            document.documentElement.style.setProperty("--admin-bar-height", "0px");
        }
    };

    update();
    window.addEventListener("resize", update, { passive: true });

    if (typeof ResizeObserver !== "undefined") {
        const observer = new ResizeObserver(update);
        observer.observe(header);
    }
}

// Re-aligns hash target on initial load once external assets are rendered.
function initHashScrollAlignment() {
    if (!window.location.hash) return;

    window.addEventListener(
        "load",
        () => {
            try {
                const target = document.querySelector(window.location.hash);
                if (target) {
                    target.scrollIntoView({ behavior: "smooth" });
                }
            } catch {
                // Ignore invalid selector in hash.
            }
        },
        { once: true }
    );
}

export function initHeaderModule() {
    const header = document.querySelector("#masthead");

    if (!header) return;

    initHeaderOffset(header);
    initHashScrollAlignment();
    initScrollState(header);
    relocateIntoMobileMenu(".header-buttons-container", "menu-item-header-buttons", "append");
    // The switcher shows both languages inline inside the panel, so it stays open there.
    relocateIntoMobileMenu(
        ".language-switcher",
        "menu-item-languages",
        "prepend",
        (el, isMobile) => {
            el.open = isMobile;
        }
    );
    initMegaMenuCards();
}
