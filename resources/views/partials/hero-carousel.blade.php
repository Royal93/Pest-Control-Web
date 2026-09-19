{{-- Hero carousel v4. Self-contained: own CSS, vanilla JS (no Alpine/Tailwind dependency).
     - Soft light brand panel on the left; the whole photo sits on the right and its left edge fades into the panel.
     - A very large logo stays fixed on the left (wide screens); the photo grows in beside it on each slide.
     - Auto-advances continuously (hovering does not pause it).
     - Slides whose image file is missing are skipped; the logo is skipped if badge.png is missing.
     To change the panel colours, edit the --spc-p1/p2/p3 values at the top of the <style> block. --}}
@php
    $requestUrl = url('/contact');          // primary button target  (change if your route differs)
    $plansUrl   = url('/plans');            // secondary button target (your Protection Plans page)

    $all = [
        ['image' => 'images/carousel/slide-1.jpg', 'alt' => 'SP Pest Control technician treating a home',
         'tag' => 'Trusted in Kempton Park',
         'before' => 'Pest-free homes,', 'accent' => 'guaranteed', 'after' => '',
         'text' => 'Scheduled treatments that stop infestations at the source, so pests stay gone.'],
        ['image' => 'images/carousel/slide-2.jpg', 'alt' => 'SP Pest Control technician speaking with a customer',
         'tag' => 'Qualified technicians',
         'before' => 'Real experts.', 'accent' => 'Real', 'after' => 'results.',
         'text' => 'Friendly, uniformed professionals who inspect, explain and treat with care.'],
        ['image' => 'images/carousel/slide-3.jpg', 'alt' => 'A family safe at home',
         'tag' => 'Safe for your family',
         'before' => 'Kid and pet-safe', 'accent' => 'treatments', 'after' => '',
         'text' => 'Low-toxicity, targeted methods that protect your home without the worry.'],
        ['image' => 'images/carousel/slide-4.jpg', 'alt' => 'A happy family outside their home',
         'tag' => 'Residential and commercial',
         'before' => 'Protection that', 'accent' => 'lasts', 'after' => '',
         'text' => 'Ongoing plans with monitoring visits and a 30-day ant-free guarantee.'],
    ];

    $root = rtrim(request()->server('DOCUMENT_ROOT') ?: public_path(), '/');
    $hasBadge = is_file($root . '/images/carousel/badge.png');

    // Keep only slides whose image exists, and read each photo's real proportions
    // so every picture is shown whole, whatever its shape.
    $slides = [];
    foreach ($all as $s) {
        $path = $root . '/' . $s['image'];
        if (!is_file($path)) { continue; }
        $size = @getimagesize($path);
        $s['ratio'] = ($size && $size[1] > 0) ? max(1.2, min(2.2, round($size[0] / $size[1], 3))) : 1.5;
        $slides[] = $s;
    }

    // Never render an empty hero: fall back to one slide on the brand panel.
    if (count($slides) === 0) {
        $slides = [array_merge($all[0], ['image' => null, 'ratio' => 1.5])];
    }
@endphp

<section class="spc-hero" id="spcHero" aria-roledescription="carousel" aria-label="Featured">
    @if ($hasBadge)
        <img class="spc-hero__logo" src="{{ asset('images/carousel/badge.png') }}" alt="SP Pest Control">
    @endif
    @foreach ($slides as $i => $s)
        <div class="spc-hero__slide {{ $i === 0 ? 'is-active' : '' }}" aria-hidden="{{ $i === 0 ? 'false' : 'true' }}" style="--spc-r:{{ $s['ratio'] }}">
            @if ($s['image'])
                <div class="spc-hero__photo">
                    <div class="spc-hero__edge">
                        <div class="spc-hero__reveal">
                            <img class="spc-hero__img" src="{{ asset($s['image']) }}" alt="{{ $s['alt'] }}" decoding="async">
                        </div>
                    </div>
                </div>
            @endif
            <div class="spc-hero__scrim"></div>
            <div class="spc-hero__content">
                <span class="spc-hero__pill">{{ $s['tag'] }}</span>
                <h1 class="spc-hero__title">
                    {{ $s['before'] }} <em>{{ $s['accent'] }}</em> {{ $s['after'] }}
                </h1>
                <p class="spc-hero__text">{{ $s['text'] }}</p>
                <div class="spc-hero__actions">
                    <a href="{{ $requestUrl }}" class="spc-btn spc-btn--solid">Request Inspection</a>
                    <a href="{{ $plansUrl }}" class="spc-btn spc-btn--ghost">View Protection Plans</a>
                </div>
            </div>
        </div>
    @endforeach

    @if (count($slides) > 1)
        <button type="button" class="spc-hero__nav spc-hero__nav--prev" aria-label="Previous slide">&#8249;</button>
        <button type="button" class="spc-hero__nav spc-hero__nav--next" aria-label="Next slide">&#8250;</button>
        <div class="spc-hero__dots">
            @foreach ($slides as $i => $s)
                <button type="button" class="spc-hero__dot {{ $i === 0 ? 'is-active' : '' }}" aria-label="Go to slide {{ $i + 1 }}"></button>
            @endforeach
        </div>
    @endif
</section>

<style>
    .spc-hero{
        --spc-ink:#2b333b; --spc-accent:#c8622c; --spc-accent-dark:#a04d1e;
        /* Soft brand panel (light peach, matching the logo's orange). */
        --spc-p1:#fff8f1; --spc-p2:#fcebdc; --spc-p3:#f6d9c3;
        --spc-logo:clamp(260px,20vw,430px); --spc-logo-left:clamp(64px,4.5vw,110px);
        position:relative;width:100%;display:grid;
        height:clamp(460px,min(80vh,38vw),700px);
        background:linear-gradient(120deg,var(--spc-p1) 0%,var(--spc-p2) 50%,var(--spc-p3) 100%);
        overflow:hidden;color:var(--spc-ink)
    }
    .spc-hero,.spc-hero *{box-sizing:border-box}
    /* All slides stack in one grid cell. */
    .spc-hero__slide{grid-area:1/1;position:relative;display:flex;align-items:center;overflow:hidden;opacity:0;visibility:hidden;transition:opacity .8s ease,visibility .8s}
    .spc-hero__slide.is-active{opacity:1;visibility:visible;z-index:1}

    /* Photo box = the whole picture (its real proportions) at full hero height, on the right.
       Resting (base) state = fully revealed, so nothing snaps when a slide fades out. */
    .spc-hero__photo{position:absolute;top:0;right:0;height:100%;aspect-ratio:var(--spc-r,1.5);max-width:100%}
    .spc-hero__edge{position:absolute;top:0;right:0;height:100%;width:100%}
    .spc-hero__reveal{position:absolute;inset:0;overflow:hidden;
        -webkit-mask-image:linear-gradient(90deg,transparent 0%,rgba(0,0,0,.1) 3%,rgba(0,0,0,.32) 7%,rgba(0,0,0,.62) 12%,rgba(0,0,0,.86) 17%,rgba(0,0,0,.97) 21%,#000 25%);
                mask-image:linear-gradient(90deg,transparent 0%,rgba(0,0,0,.1) 3%,rgba(0,0,0,.32) 7%,rgba(0,0,0,.62) 12%,rgba(0,0,0,.86) 17%,rgba(0,0,0,.97) 21%,#000 25%)}
    .spc-hero__img{position:absolute;top:0;right:0;display:block;height:100%;width:auto;aspect-ratio:var(--spc-r,1.5);object-fit:cover;transform-origin:right center}

    /* Per-slide animation: the photo grows leftwards into place. */
    .spc-hero__slide.is-active .spc-hero__edge{animation:spcGrow 3.4s cubic-bezier(.22,.7,.2,1) both}
    .spc-hero__slide.is-active .spc-hero__img{animation:spcKen 9s ease-out both}
    @keyframes spcGrow{from{width:56%}to{width:100%}}
    @keyframes spcKen{from{transform:scale(1)}to{transform:scale(1.03)}}

    /* Very large fixed logo on the left (wide screens only, see media query below). */
    .spc-hero__logo{display:none;position:absolute;z-index:2;top:50%;left:var(--spc-logo-left);width:var(--spc-logo);height:auto;
        transform:translateY(-50%);pointer-events:none;
        filter:drop-shadow(0 18px 40px rgba(160,77,30,.30));animation:spcLogoIn 1s cubic-bezier(.22,.7,.2,1) both}
    @keyframes spcLogoIn{from{opacity:0;transform:translateY(-50%) scale(.85)}to{opacity:1;transform:translateY(-50%) scale(1)}}

    /* Light panel-coloured wash at the far left only; the text carries its own soft glow. */
    .spc-hero__scrim{position:absolute;inset:0;pointer-events:none;
        background:linear-gradient(90deg,rgba(255,248,241,.9) 0%,rgba(255,248,241,.7) 26%,rgba(255,248,241,.22) 40%,rgba(255,248,241,0) 50%)}

    /* Text: outgoing fades quickly, incoming waits a moment, so the two never overlap. */
    .spc-hero__content{position:relative;z-index:2;width:100%;max-width:1152px;margin:0 auto;padding:0 28px;display:flex;flex-direction:column;align-items:flex-start;gap:18px;
        opacity:0;transition:opacity .3s ease}
    .spc-hero__slide.is-active .spc-hero__content{opacity:1;transition:opacity .6s ease .45s}
    .spc-hero__pill{display:inline-block;padding:6px 14px;border:1px solid var(--spc-accent);border-radius:999px;font-size:12px;letter-spacing:.14em;text-transform:uppercase;color:var(--spc-accent-dark);background:rgba(200,98,44,.08)}
    .spc-hero__title{margin:0;max-width:500px;font-family:'Oswald',sans-serif;font-weight:700;text-transform:uppercase;line-height:1.05;font-size:clamp(32px,4.4vw,58px);color:var(--spc-ink);text-shadow:0 0 22px rgba(255,248,241,.9),0 0 8px rgba(255,248,241,.75)}
    .spc-hero__title em{font-style:normal;color:var(--spc-accent)}
    .spc-hero__text{margin:0;max-width:440px;font-size:clamp(15px,1.5vw,18px);line-height:1.55;color:#3e454c;text-shadow:0 0 14px rgba(255,248,241,.95),0 0 5px rgba(255,248,241,.8)}
    .spc-hero__actions{display:flex;flex-wrap:wrap;gap:12px;margin-top:6px}
    .spc-btn{display:inline-block;padding:14px 26px;font-size:13px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;text-decoration:none;border-radius:4px;transition:background .2s,color .2s,border-color .2s}
    .spc-btn--solid{background:var(--spc-accent);color:#fff;border:2px solid var(--spc-accent)}
    .spc-btn--solid:hover{background:var(--spc-accent-dark);border-color:var(--spc-accent-dark)}
    .spc-btn--ghost{background:rgba(255,255,255,.6);color:var(--spc-ink);border:2px solid var(--spc-ink)}
    .spc-btn--ghost:hover{background:var(--spc-ink);color:#fff}
    .spc-hero__nav{position:absolute;top:50%;transform:translateY(-50%);z-index:3;width:46px;height:46px;border-radius:50%;border:1px solid rgba(43,51,59,.15);background:rgba(255,255,255,.88);color:var(--spc-ink);font-size:28px;line-height:1;cursor:pointer;box-shadow:0 2px 10px rgba(60,26,10,.18)}
    .spc-hero__nav:hover{background:var(--spc-accent);color:#fff;border-color:var(--spc-accent)}
    .spc-hero__nav--prev{left:16px}.spc-hero__nav--next{right:16px}
    .spc-hero__dots{position:absolute;left:50%;bottom:20px;transform:translateX(-50%);z-index:3;display:flex;gap:10px;padding:7px 12px;border-radius:999px;background:rgba(255,255,255,.78)}
    .spc-hero__dot{width:10px;height:10px;padding:0;border-radius:50%;border:0;background:rgba(43,51,59,.28);cursor:pointer}
    .spc-hero__dot.is-active{background:var(--spc-accent);transform:scale(1.25)}

    /* Wide screens: very large logo on the left, text beside it, photo on the right. */
    @media (min-width:1500px){
        .spc-hero__logo{display:block}
        .spc-hero__content{max-width:none;margin:0;padding-left:calc(var(--spc-logo-left) + var(--spc-logo) + 56px);padding-right:28px}
        .spc-hero__scrim{background:linear-gradient(90deg,rgba(255,248,241,.9) 0%,rgba(255,248,241,.75) 30%,rgba(255,248,241,.2) 42%,rgba(255,248,241,0) 52%)}
    }

    /* Phones and small tablets: whole photo on top (fading into the panel below), text underneath. */
    @media (max-width:1000px){
        .spc-hero{height:auto}
        .spc-hero__slide{flex-direction:column;align-items:stretch}
        .spc-hero__photo{position:relative;top:auto;right:auto;height:auto;width:100%;aspect-ratio:var(--spc-r,1.5)}
        .spc-hero__slide.is-active .spc-hero__edge,.spc-hero__slide.is-active .spc-hero__img{animation:none}
        .spc-hero__reveal{-webkit-mask-image:linear-gradient(180deg,#000 72%,transparent 100%);mask-image:linear-gradient(180deg,#000 72%,transparent 100%)}
        .spc-hero__img{width:100%}
        .spc-hero__content{padding:0 24px 64px;gap:14px;margin-top:-18px}
        .spc-hero__title{max-width:none;font-size:clamp(28px,8vw,40px)}
        .spc-hero__scrim{display:none}
        .spc-hero__nav{display:none}
    }
    @media (prefers-reduced-motion:reduce){
        .spc-hero__slide.is-active .spc-hero__edge,.spc-hero__slide.is-active .spc-hero__img,.spc-hero__logo{animation:none}
        .spc-hero__slide,.spc-hero__content{transition:none}
    }
</style>

<script>
(function () {
    var hero = document.getElementById('spcHero');
    if (!hero) return;
    var slides = hero.querySelectorAll('.spc-hero__slide');
    var dots = hero.querySelectorAll('.spc-hero__dot');
    if (slides.length < 2) return;
    var current = 0, timer = null, DELAY = 6500;

    function show(n) {
        current = (n + slides.length) % slides.length;
        slides.forEach(function (s, i) {
            var on = i === current;
            s.classList.toggle('is-active', on);
            s.setAttribute('aria-hidden', on ? 'false' : 'true');
        });
        dots.forEach(function (d, i) { d.classList.toggle('is-active', i === current); });
    }
    function start() { stop(); timer = setInterval(function () { show(current + 1); }, DELAY); }
    function stop() { if (timer) { clearInterval(timer); timer = null; } }

    hero.querySelector('.spc-hero__nav--prev').addEventListener('click', function () { show(current - 1); start(); });
    hero.querySelector('.spc-hero__nav--next').addEventListener('click', function () { show(current + 1); start(); });
    dots.forEach(function (d, i) { d.addEventListener('click', function () { show(i); start(); }); });

    // Only pause when the tab is hidden; hovering does NOT stop the slideshow.
    document.addEventListener('visibilitychange', function () { document.hidden ? stop() : start(); });
    start();
})();
</script>
