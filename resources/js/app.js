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
