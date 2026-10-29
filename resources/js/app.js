/**
 * Scroll-reveal: any element with class="reveal" or "reveal-stagger" fades/slides
 * into view the first time it enters the viewport.
 */
document.addEventListener('DOMContentLoaded', () => {
    const targets = document.querySelectorAll('.reveal, .reveal-stagger');

    if (!('IntersectionObserver' in window) || targets.length === 0) {
        targets.forEach((el) => el.classList.add('is-visible'));
    } else {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 });

        targets.forEach((el) => observer.observe(el));
    }

    // Compact the sticky nav (adds a shadow) once the page is scrolled down.
    const topbar = document.getElementById('siteTopbar');
    if (topbar) {
        const onScroll = () => {
            topbar.classList.toggle('topbar-compact', window.scrollY > 30);
        };
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
    }
});

/**
 * Mobile menus. A button with data-menu-toggle="someId" opens and closes the
 * element with that id (adds/removes "is-open"). Used by the public site menu
 * and the admin / SP SALES sidebars. Anything with data-menu-close="someId"
 * (the dark overlay) closes it, as do Escape and following a link inside it.
 */
document.addEventListener('DOMContentLoaded', () => {
    const setOpen = (target, open) => {
        target.classList.toggle('is-open', open);
        document.querySelectorAll(`[data-menu-toggle="${target.id}"]`).forEach((btn) => {
            btn.setAttribute('aria-expanded', open ? 'true' : 'false');
            btn.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
        });
        document.querySelectorAll(`[data-menu-close="${target.id}"]`).forEach((el) => el.classList.toggle('is-open', open));
        document.body.classList.toggle('menu-open', open && target.classList.contains('drawer'));
    };

    document.querySelectorAll('[data-menu-toggle]').forEach((btn) => {
        const target = document.getElementById(btn.dataset.menuToggle);
        if (!target) return;
        btn.addEventListener('click', () => setOpen(target, !target.classList.contains('is-open')));
        target.querySelectorAll('a[href]').forEach((link) => link.addEventListener('click', () => setOpen(target, false)));
    });

    document.querySelectorAll('[data-menu-close]').forEach((el) => {
        const target = document.getElementById(el.dataset.menuClose);
        if (target) el.addEventListener('click', () => setOpen(target, false));
    });

    document.addEventListener('keydown', (e) => {
        if (e.key !== 'Escape') return;
        document.querySelectorAll('.mobile-menu.is-open, .drawer.is-open').forEach((el) => setOpen(el, false));
    });
});
