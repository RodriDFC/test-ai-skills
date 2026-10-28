export function initPrivacyScrollspy() {
    const navContainer = document.getElementById('privacy-nav');
    const contentArea = document.querySelector('.privacy__content');
    
    if (!navContainer || !contentArea) return;

    const contentHeadings = contentArea.querySelectorAll('h2:not(.privacy__cta h2)');

    const targets = [];
    
    contentHeadings.forEach((heading, index) => {
        if (!heading.id) {
            heading.id = `privacy-section-${index}`;
        }
        targets.push({
            element: heading,
            id: heading.id,
            text: heading.textContent
        });
    });

    const ctaBlock = document.querySelector('.privacy__cta .container[id]');
    if (ctaBlock) {
        targets.push({
            element: ctaBlock,
            id: ctaBlock.id,
            text: 'Contact Us',
            isCta: true
        });
    }

    if (targets.length === 0) return;

    // Create the navigation list
    const ul = document.createElement('ul');
    ul.className = 'privacy__nav-list';
    
    // Create a separate wrapper for the CTA so it stays outside the line
    const ctaWrapper = document.createElement('div');
    ctaWrapper.className = 'privacy__nav-cta-wrapper';
    
    const navItems = [];
    
    let isClickScrolling = false;
    let scrollTimeout;

    targets.forEach((target) => {
        const a = document.createElement('a');
        a.href = `#${target.id}`;
        a.className = 'privacy__nav-link';
        a.textContent = target.text;
        
        // Smooth scroll on click
        a.addEventListener('click', (e) => {
            e.preventDefault();
            
            isClickScrolling = true;
            
            // Update active state immediately on click
            navItems.forEach(item => item.link.classList.remove('privacy__nav-link--active'));
            a.classList.add('privacy__nav-link--active');

            // Calculate exact position with offset for sticky nav/header
            const offset = 180; // Margin to leave above the heading when clicked
            const elementTop = target.element.getBoundingClientRect().top + window.scrollY;
            
            window.scrollTo({
                 top: elementTop - offset,
                 behavior: 'smooth'
            });
            
            // Optionally update URL hash
            history.pushState(null, null, `#${target.id}`);
            
            // Re-enable scrollspy after scroll finishes
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => {
                isClickScrolling = false;
            }, 800); // 800ms gives the smooth scroll enough time to finish
        });

        if (target.isCta) {
            ctaWrapper.appendChild(a);
        } else {
            const li = document.createElement('li');
            li.className = 'privacy__nav-item';
            li.appendChild(a);
            ul.appendChild(li);
        }
        
        navItems.push({
            link: a,
            target: target
        });
    });

    navContainer.appendChild(ul);
    if (ctaWrapper.hasChildNodes()) {
        navContainer.appendChild(ctaWrapper);
    }

    // Update active section on scroll
    function updateActiveSection() {
        if (isClickScrolling) return;

        const triggerPoint = window.scrollY + (window.innerHeight * 0.4); 

        let currentActive = targets[0]; 
        for (let i = 0; i < targets.length; i++) {
            const target = targets[i];
            const targetTop = target.element.getBoundingClientRect().top + window.scrollY;

            if (targetTop <= triggerPoint) {
                currentActive = target;
            } else {

                break;
            }
        }

        const isAtBottom = Math.ceil(window.innerHeight + window.scrollY) >= document.documentElement.scrollHeight - 50;
        if (isAtBottom && targets.length > 0) {
            currentActive = targets[targets.length - 1];
        }

        // Update the classes
        if (currentActive) {
            navItems.forEach(item => {
                if (item.target.id === currentActive.id) {
                    item.link.classList.add('privacy__nav-link--active');
                } else {
                    item.link.classList.remove('privacy__nav-link--active');
                }
            });
        }
    }

    let ticking = false;
    window.addEventListener('scroll', () => {
        if (!ticking) {
            window.requestAnimationFrame(() => {
                updateActiveSection();
                ticking = false;
            });
            ticking = true;
        }
    });

    ['wheel', 'touchstart', 'pointerdown'].forEach(evt => {
        window.addEventListener(evt, () => {
            if (isClickScrolling) {
                isClickScrolling = false;
                clearTimeout(scrollTimeout);
            }
        }, { passive: true });
    });

    updateActiveSection();
}
