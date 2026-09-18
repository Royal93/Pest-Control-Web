{{-- Hero carousel. Self-contained: own CSS, vanilla JS (no Alpine/Tailwind dependency).
     Slides whose image file is missing are skipped automatically, so deleting an image can never blank the hero. --}}
@php
    $requestUrl = url('/contact');          // primary button target  (change if your route differs)
    $plansUrl   = url('/protection-plans'); // secondary button target (change if your route differs)

    $all = [
        ['image' => 'images/carousel/slide-1.jpg', 'tag' => 'Trusted in Kempton Park',
         'before' => 'Pest-free homes,', 'accent' => 'guaranteed', 'after' => '',
         'text' => 'Scheduled treatments that stop infestations at the source, so pests stay gone.'],
        ['image' => 'images/carousel/slide-2.jpg', 'tag' => 'Qualified technicians',
         'before' => 'Real experts.', 'accent' => 'Real', 'after' => 'results.',
         'text' => 'Friendly, uniformed professionals who inspect, explain and treat with care.'],
        ['image' => 'images/carousel/slide-3.jpg', 'tag' => 'Safe for your family',
         'before' => 'Kid and pet-safe', 'accent' => 'treatments', 'after' => '',
         'text' => 'Low-toxicity, targeted methods that protect your home without the worry.'],
        ['image' => 'images/carousel/slide-4.jpg', 'tag' => 'Residential and commercial',
         'before' => 'Protection that', 'accent' => 'lasts', 'after' => '',
         'text' => 'Ongoing plans with monitoring visits and a 30-day ant-free guarantee.'],
    ];

    $root = rtrim(request()->server('DOCUMENT_ROOT') ?: public_path(), '/');
    $slides = array_values(array_filter($all, fn ($s) => is_file($root . '/' . $s['image'])));

    // Never render an empty hero: fall back to one slide on the dark background.
    if (count($slides) === 0) {
        $slides = [array_merge($all[0], ['image' => null])];
    }
@endphp

<section class="spc-hero" id="spcHero" aria-roledescription="carousel" aria-label="Featured">
    @foreach ($slides as $i => $s)
        <div class="spc-hero__slide {{ $i === 0 ? 'is-active' : '' }}" aria-hidden="{{ $i === 0 ? 'false' : 'true' }}">
            @if ($s['image'])
                <div class="spc-hero__bg" style="background-image:url('{{ asset($s['image']) }}')"></div>
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
    .spc-hero{position:relative;width:100%;height:clamp(460px,78vh,680px);background:#2b333b;overflow:hidden;color:#fff}
    .spc-hero__slide{position:absolute;inset:0;opacity:0;visibility:hidden;transition:opacity .9s ease,visibility .9s}
    .spc-hero__slide.is-active{opacity:1;visibility:visible;z-index:1}
    .spc-hero__bg{position:absolute;inset:0;background-size:cover;background-position:center;transform:scale(1);will-change:transform}
    .spc-hero__slide.is-active .spc-hero__bg{animation:spcKen 9s ease-out forwards}
    @keyframes spcKen{from{transform:scale(1)}to{transform:scale(1.09)}}
    .spc-hero__shade{position:absolute;inset:0;background:linear-gradient(90deg,rgba(20,26,32,.88) 0%,rgba(20,26,32,.62) 45%,rgba(20,26,32,.15) 100%)}
    .spc-hero__content{position:relative;z-index:2;height:100%;max-width:1200px;margin:0 auto;padding:0 24px;display:flex;flex-direction:column;justify-content:center;align-items:flex-start;gap:18px}
    .spc-hero__pill{display:inline-block;padding:6px 14px;border:1px solid rgba(255,255,255,.45);border-radius:999px;font-size:12px;letter-spacing:.14em;text-transform:uppercase;background:rgba(255,255,255,.08)}
    .spc-hero__title{margin:0;max-width:720px;font-family:'Oswald',sans-serif;font-weight:700;text-transform:uppercase;line-height:1.05;font-size:clamp(34px,6vw,68px)}
    .spc-hero__title em{font-style:normal;color:#d0703c}
    .spc-hero__text{margin:0;max-width:560px;font-size:clamp(15px,1.6vw,19px);line-height:1.55;color:rgba(255,255,255,.88)}
    .spc-hero__actions{display:flex;flex-wrap:wrap;gap:12px;margin-top:6px}
    .spc-btn{display:inline-block;padding:14px 26px;font-size:13px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;text-decoration:none;border-radius:4px;transition:background .2s,color .2s,border-color .2s}
    .spc-btn--solid{background:#d0703c;color:#fff;border:2px solid #d0703c}
    .spc-btn--solid:hover{background:#b85d2d;border-color:#b85d2d}
    .spc-btn--ghost{background:transparent;color:#fff;border:2px solid rgba(255,255,255,.7)}
    .spc-btn--ghost:hover{background:#fff;color:#2b333b}
    .spc-hero__nav{position:absolute;top:50%;transform:translateY(-50%);z-index:3;width:46px;height:46px;border-radius:50%;border:1px solid rgba(255,255,255,.5);background:rgba(20,26,32,.4);color:#fff;font-size:28px;line-height:1;cursor:pointer}
    .spc-hero__nav:hover{background:#d0703c;border-color:#d0703c}
    .spc-hero__nav--prev{left:16px}.spc-hero__nav--next{right:16px}
    .spc-hero__dots{position:absolute;left:0;right:0;bottom:22px;z-index:3;display:flex;justify-content:center;gap:10px}
    .spc-hero__dot{width:10px;height:10px;padding:0;border-radius:50%;border:0;background:rgba(255,255,255,.5);cursor:pointer}
    .spc-hero__dot.is-active{background:#d0703c;transform:scale(1.25)}
    @media (max-width:640px){.spc-hero__nav{display:none}.spc-hero__shade{background:linear-gradient(180deg,rgba(20,26,32,.7),rgba(20,26,32,.85))}}
    @media (prefers-reduced-motion:reduce){.spc-hero__slide.is-active .spc-hero__bg{animation:none}.spc-hero__slide{transition:none}}
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
    hero.addEventListener('mouseenter', stop);
    hero.addEventListener('mouseleave', start);
    document.addEventListener('visibilitychange', function () { document.hidden ? stop() : start(); });
    start();
})();
</script>
