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

/**
 * Notification bell (admin portal and SP SALES). Every .notif-bell on the page
 * shows the same data: one poll every 30 seconds updates them all. Polling
 * pauses while the tab is hidden and stops if the session has ended.
 */
document.addEventListener('DOMContentLoaded', () => {
    const bells = Array.from(document.querySelectorAll('.notif-bell'));
    if (bells.length === 0) return;

    const first = bells[0];
    const feedUrl = first.dataset.feed;
    const readAllUrl = first.dataset.readAll;
    const csrf = first.dataset.csrf;
    let stopped = false;

    const kindColour = {
        claim: 'bg-amber-500',
        claim_withdrawn: 'bg-gray-400',
        claim_approved: 'bg-green-600',
        claim_declined: 'bg-red-500',
        lead_assigned: 'bg-blue-600',
        new_lead: 'bg-blue-600',
        sale_accepted: 'bg-amber-500',
        sale_paid: 'bg-green-600',
    };

    const render = (data) => {
        bells.forEach((bell) => {
            const count = bell.querySelector('.notif-count');
            count.textContent = data.unread > 99 ? '99+' : String(data.unread);
            count.classList.toggle('hidden', data.unread === 0);
            bell.querySelector('.notif-read-all').disabled = data.unread === 0;

            const list = bell.querySelector('.notif-list');
            list.replaceChildren();

            if (data.items.length === 0) {
                const li = document.createElement('li');
                li.className = 'px-4 py-6 text-sm text-center text-inkFaint';
                li.textContent = 'No notifications yet.';
                list.appendChild(li);
                return;
            }

            data.items.forEach((item) => {
                const li = document.createElement('li');
                li.className = 'border-b border-line last:border-b-0';

                const a = document.createElement('a');
                a.href = item.href;
                a.className = 'flex gap-3 px-4 py-3 hover:bg-bgAlt ' + (item.read ? '' : 'bg-amber-50');

                const dot = document.createElement('span');
                dot.className = 'mt-1.5 w-2.5 h-2.5 rounded-full flex-shrink-0 ' + (item.read ? 'bg-transparent' : (kindColour[item.kind] || 'bg-primary'));

                const text = document.createElement('div');
                text.className = 'min-w-0';
                const title = document.createElement('div');
                title.className = 'text-sm ' + (item.read ? '' : 'font-semibold');
                title.textContent = item.title;
                const body = document.createElement('div');
                body.className = 'text-xs text-inkFaint mt-0.5';
                body.textContent = item.body;
                const time = document.createElement('div');
                time.className = 'text-[11px] text-inkFaint mt-1';
                time.textContent = item.time;
                text.append(title, body, time);

                a.append(dot, text);
                li.appendChild(a);
                list.appendChild(li);
            });
        });
    };

    const poll = async () => {
        if (stopped || document.hidden) return;
        try {
            const res = await fetch(feedUrl, { headers: { Accept: 'application/json' }, credentials: 'same-origin' });
            const type = res.headers.get('content-type') || '';
            if (!res.ok || !type.includes('application/json')) {
                stopped = true; // logged out or session expired
                return;
            }
            render(await res.json());
        } catch (e) {
            // Network blip: try again on the next tick.
        }
    };

    const closeAll = (except) => {
        bells.forEach((bell) => {
            if (bell === except) return;
            bell.querySelector('.notif-panel').classList.add('hidden');
            bell.querySelector('.notif-btn').setAttribute('aria-expanded', 'false');
        });
    };

    bells.forEach((bell) => {
        const btn = bell.querySelector('.notif-btn');
        const panel = bell.querySelector('.notif-panel');

        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const open = panel.classList.contains('hidden');
            closeAll(bell);
            panel.classList.toggle('hidden', !open);
            btn.setAttribute('aria-expanded', open ? 'true' : 'false');
            if (open) poll();
        });

        panel.addEventListener('click', (e) => e.stopPropagation());

        bell.querySelector('.notif-read-all').addEventListener('click', async () => {
            try {
                await fetch(readAllUrl, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, Accept: 'application/json' },
                    credentials: 'same-origin',
                });
            } finally {
                poll();
            }
        });
    });

    document.addEventListener('click', () => closeAll(null));
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeAll(null); });
    document.addEventListener('visibilitychange', () => { if (!document.hidden) poll(); });

    try {
        render(JSON.parse(first.dataset.initial));
    } catch (e) {
        poll();
    }
    setInterval(poll, 30000);
});
