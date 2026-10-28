export function initPostTocModule() {
    const navContainer = document.getElementById("post-toc-nav");
    const sidebar = document.querySelector(".single-post__sidebar");
    const contentArea = document.querySelector(".single-post__content");

    if (!navContainer || !sidebar || !contentArea) return;

    const headings = contentArea.querySelectorAll(":scope > h2, :scope > h3");

    const ul = document.createElement("ul");
    ul.className = "single-post__nav-list";
    const items = [];

    headings.forEach((heading, index) => {
        const text = heading.textContent.replace(/\s+/g, " ").trim();

        if (!text) return;

        if (!heading.id) {
            heading.id = `post-toc-section-${index}`;
        }

        const link = document.createElement("a");
        link.href = `#${heading.id}`;
        link.className = "single-post__nav-link";
        link.textContent = text;

        link.addEventListener("click", event => {
            event.preventDefault();
            history.pushState(null, "", `#${heading.id}`);
            heading.scrollIntoView({ behavior: "smooth" });
        });

        const li = document.createElement("li");
        li.className = "single-post__nav-item";
        li.appendChild(link);
        ul.appendChild(li);

        items.push({ heading, link });
    });

    if (items.length === 0) {
        sidebar.classList.add("single-post__sidebar--empty");
        return;
    }

    navContainer.appendChild(ul);

    // Desktop: open; mobile: collapsed.
    const navToggle = document.querySelector(".single-post__nav-toggle");
    if (navToggle && window.matchMedia("(min-width: 992px)").matches) {
        navToggle.open = true;
    }

    function updateActiveSection() {
        const trigger = window.innerHeight * 0.4;
        const atBottom =
            Math.ceil(window.innerHeight + window.scrollY) >=
            document.documentElement.scrollHeight - 50;

        let active = items[0];

        if (atBottom) {
            active = items[items.length - 1];
        } else {
            for (const item of items) {
                if (item.heading.getBoundingClientRect().top > trigger) break;
                active = item;
            }
        }

        items.forEach(item => {
            const isActive = item === active;

            item.link.classList.toggle("single-post__nav-link--active", isActive);

            if (isActive) {
                item.link.setAttribute("aria-current", "true");
            } else {
                item.link.removeAttribute("aria-current");
            }
        });
    }

    let ticking = false;
    window.addEventListener(
        "scroll",
        () => {
            if (ticking) return;

            ticking = true;
            window.requestAnimationFrame(() => {
                updateActiveSection();
                ticking = false;
            });
        },
        { passive: true }
    );

    updateActiveSection();
}
