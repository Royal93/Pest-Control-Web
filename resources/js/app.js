/**
 * Scroll-reveal: any element with class="reveal" or "reveal-stagger" fades/slides
 * into view the first time it enters the viewport. No per-page setup needed —
 * this runs globally once the DOM is ready.
 */
document.addEventListener('DOMContentLoaded', () => {
    const targets = document.querySelectorAll('.reveal, .reveal-stagger');

    if (!('IntersectionObserver' in window) || targets.length === 0) {
        targets.forEach((el) => el.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15 });

    targets.forEach((el) => observer.observe(el));
});
