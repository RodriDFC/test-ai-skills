export function initSinglePostHeroModule() {
    const article = document.querySelector(".single-post");
    const title = document.querySelector(".single-post__hero .single-post__title");
    const header = document.querySelector("#masthead") || document.querySelector(".header-site");

    if (!article || !title || !header) return;

    let ticking = false;

    const update = () => {
        const headerHeight = header.getBoundingClientRect().height;

        document.documentElement.style.setProperty("--header-height", `${headerHeight}px`);
        article.classList.toggle(
            "is-compact",
            title.getBoundingClientRect().bottom <= headerHeight
        );

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

    window.addEventListener("resize", update);
}
