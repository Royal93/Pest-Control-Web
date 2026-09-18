{{-- Hero carousel v3. Self-contained: own CSS, vanilla JS (no Alpine/Tailwind dependency).
     - The whole photo is always visible (never cropped), sitting on the right.
     - Its left edge fades into the brand-coloured panel, so there is no hard line between them.
     - Auto-advances continuously (hovering does not pause it).
     - Slides whose image file is missing are skipped automatically.
     To change the panel colour, edit the three --spc-c1/c2/c3 values at the top of the <style> block. --}}
@php
    $requestUrl = url('/contact');          // primary button target  (change if your route differs)
    $plansUrl   = url('/protection-plans'); // secondary button target (change if your route differs)

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
    $slides = array_values(array_filter($all, fn ($s) => is_file($root . '/' . $s['image'])));

    // Never render an empty hero: fall back to one slide on the brand panel.
    if (count($slides) === 0) {
        $slides = [array_merge($all[0], ['image' => null])];
    }
@endphp

<section class="spc-hero" id="spcHero" aria-roledescription="carousel" aria-label="Featured">
    @foreach ($slides as $i => $s)
        <div class="spc-hero__slide {{ $i === 0 ? 'is-active' : '' }}" aria-hidden="{{ $i === 0 ? 'false' : 'true' }}">
            @if ($s['image'])
                <div class="spc-hero__photo">
                    <img class="spc-hero__img" src="{{ asset($s['image']) }}" alt="{{ $s['alt'] }}" decoding="async">
                </div>
            @endif
            <div class="spc-hero__shade"></div>
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
        /* Brand panel colours, sampled from the SP Pest Control logo background. */
        --spc-c1:#bd6432; --spc-c2:#a55a30; --spc-c3:#8a5132;
        position:relative;width:100%;display:grid;
        height:clamp(480px,min(82vh,60vw),720px);
        background:linear-gradient(135deg,var(--spc-c1) 0%,var(--spc-c2) 45%,var(--spc-c3) 100%);
        overflow:hidden;color:#fff
    }
    /* All slides stack in one grid cell. */
    .spc-hero__slide{grid-area:1/1;position:relative;display:flex;align-items:center;overflow:hidden;opacity:0;visibility:hidden;transition:opacity .9s ease,visibility .9s}
    .spc-hero__slide.is-active{opacity:1;visibility:visible;z-index:1}

    /* The whole photo (3:2) at full hero height, on the right. Its left edge fades into the panel. */
    .spc-hero__photo{position:absolute;top:0;right:0;height:100%;aspect-ratio:3/2;max-width:100%;overflow:hidden;
        -webkit-mask-image:linear-gradient(90deg,transparent 0%,rgba(0,0,0,.06) 5%,rgba(0,0,0,.2) 11%,rgba(0,0,0,.45) 18%,rgba(0,0,0,.72) 26%,rgba(0,0,0,.92) 33%,#000 40%);
                mask-image:linear-gradient(90deg,transparent 0%,rgba(0,0,0,.06) 5%,rgba(0,0,0,.2) 11%,rgba(0,0,0,.45) 18%,rgba(0,0,0,.72) 26%,rgba(0,0,0,.92) 33%,#000 40%)}
    .spc-hero__img{display:block;width:100%;height:100%;object-fit:cover;transform-origin:right center}
    .spc-hero__slide.is-active .spc-hero__img{animation:spcKen 8s ease-out forwards}
    @keyframes spcKen{from{transform:scale(1)}to{transform:scale(1.03)}}

    /* Soft warm shade behind the text so it stays readable where the photo fades in. */
    .spc-hero__shade{position:absolute;inset:0;pointer-events:none;background:linear-gradient(90deg,rgba(70,32,14,.42) 0%,rgba(70,32,14,.26) 38%,rgba(70,32,14,0) 64%)}

    .spc-hero__content{position:relative;z-index:2;width:100%;max-width:1152px;margin:0 auto;padding:0 28px;display:flex;flex-direction:column;align-items:flex-start;gap:18px}
    .spc-hero__pill{display:inline-block;padding:6px 14px;border:1px solid rgba(255,255,255,.6);border-radius:999px;font-size:12px;letter-spacing:.14em;text-transform:uppercase;background:rgba(255,255,255,.12)}
    .spc-hero__title{margin:0;max-width:600px;font-family:'Oswald',sans-serif;font-weight:700;text-transform:uppercase;line-height:1.05;font-size:clamp(32px,5vw,60px);text-shadow:0 2px 12px rgba(60,26,10,.45)}
    .spc-hero__title em{font-style:normal;color:#ffe2c4}
    .spc-hero__text{margin:0;max-width:520px;font-size:clamp(15px,1.5vw,18px);line-height:1.55;color:#fff;text-shadow:0 1px 8px rgba(60,26,10,.5)}
    .spc-hero__actions{display:flex;flex-wrap:wrap;gap:12px;margin-top:6px}
    .spc-btn{display:inline-block;padding:14px 26px;font-size:13px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;text-decoration:none;border-radius:4px;transition:background .2s,color .2s,border-color .2s}
    .spc-btn--solid{background:#fff;color:#a04d1e;border:2px solid #fff}
    .spc-btn--solid:hover{background:#ffe9d6;border-color:#ffe9d6}
    .spc-btn--ghost{background:rgba(60,26,10,.25);color:#fff;border:2px solid rgba(255,255,255,.85)}
    .spc-btn--ghost:hover{background:#fff;color:#a04d1e}
    .spc-hero__nav{position:absolute;top:50%;transform:translateY(-50%);z-index:3;width:46px;height:46px;border-radius:50%;border:1px solid rgba(255,255,255,.6);background:rgba(60,26,10,.35);color:#fff;font-size:28px;line-height:1;cursor:pointer}
    .spc-hero__nav:hover{background:#fff;color:#a04d1e;border-color:#fff}
    .spc-hero__nav--prev{left:16px}.spc-hero__nav--next{right:16px}
    .spc-hero__dots{position:absolute;left:0;right:0;bottom:22px;z-index:3;display:flex;justify-content:center;gap:10px}
    .spc-hero__dot{width:10px;height:10px;padding:0;border-radius:50%;border:0;background:rgba(255,255,255,.5);cursor:pointer}
    .spc-hero__dot.is-active{background:#fff;transform:scale(1.25)}

    /* Phones and small tablets: whole photo on top, text underneath on the brand panel. */
    @media (max-width:820px){
        .spc-hero{height:auto}
        .spc-hero__slide{flex-direction:column;align-items:stretch}
        .spc-hero__photo{position:relative;top:auto;right:auto;height:auto;width:100%;aspect-ratio:3/2;-webkit-mask-image:none;mask-image:none}
        .spc-hero__shade{display:none}
        .spc-hero__content{padding:24px 24px 64px;gap:14px}
        .spc-hero__title{max-width:none;font-size:clamp(28px,8vw,40px)}
        .spc-hero__nav{display:none}
    }
    @media (prefers-reduced-motion:reduce){.spc-hero__slide.is-active .spc-hero__img{animation:none}.spc-hero__slide{transition:none}}
</style>

<script>
(function () {
    var hero = document.getElementById('spcHero');
    if (!hero) return;
    var slides = hero.querySelectorAll('.spc-hero__slide');
    var dots = hero.querySelectorAll('.spc-hero__dot');
    if (slides.length < 2) return;
    var current = 0, timer = null, DELAY = 5500;

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
