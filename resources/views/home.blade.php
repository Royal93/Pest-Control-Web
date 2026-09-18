@extends('layouts.app')

@section('title', 'SP Pest Control - Residential & Commercial Pest Control')

@section('content')

{{-- ============ TRUST BAR ============ --}}
<div class="bg-secondary text-white">
    <div class="max-w-6xl mx-auto px-7 py-3 flex flex-wrap justify-center gap-x-10 gap-y-1 text-xs font-mono uppercase tracking-widest">
        <span>Free Inspections &amp; Estimates</span>
        <span>Scheduled Visits, Not Guesswork</span>
        <span>Pet &amp; Family Safe Treatments</span>
    </div>
</div>

<div x-data="{
        slide: 0,
        total: 4,
        timer: null,
        start() { this.timer = setInterval(() => this.next(), 6000); },
        stop() { clearInterval(this.timer); },
        next() { this.slide = (this.slide + 1) % this.total; },
        prev() { this.slide = (this.slide - 1 + this.total) % this.total; },
        go(i) { this.slide = i; this.stop(); this.start(); }
     }"
     x-init="start()"
     @mouseenter="stop()" @mouseleave="start()"
     class="relative overflow-hidden border-b-2 border-primary h-[560px] md:h-[640px]">

    {{-- Slide 1 --}}
    <div class="absolute inset-0 transition-opacity duration-700 ease-out"
         :class="slide === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0'">
        <img src="{{ asset('images/carousel/slide-1.jpg') }}" alt="SP Pest Control technician treating a kitchen baseboard"
             class="absolute inset-0 w-full h-full object-cover" :class="slide === 0 ? 'kenburns' : ''">
        <div class="absolute inset-0 bg-gradient-to-r from-secondary/95 via-secondary/70 to-secondary/25"></div>
        <div class="relative h-full flex items-center px-7 md:px-16">
            <div class="max-w-xl">
                <span class="inline-block bg-white/10 backdrop-blur-sm border border-white/20 text-white text-xs uppercase tracking-widest px-4 py-2 rounded-full mb-5">Welcome to SP Pest Control</span>
                <h1 class="font-display uppercase text-3xl md:text-5xl text-white leading-tight mb-5">Professional Pest Control You Can <span class="text-accent">Trust</span></h1>
                <p class="text-white/80 mb-7 max-w-md">Targeted gel baits and low-toxicity perimeter treatments — effective protection that's safe around kids and pets.</p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('contact') }}" class="bg-primary text-white uppercase text-sm font-semibold px-6 py-3">Get Started</a>
                    <a href="{{ route('about') }}" class="border border-white text-white uppercase text-sm font-semibold px-6 py-3 hover:bg-white hover:text-secondary transition">Learn More</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Slide 2 --}}
    <div class="absolute inset-0 transition-opacity duration-700 ease-out"
         :class="slide === 1 ? 'opacity-100 z-10' : 'opacity-0 z-0'">
        <img src="{{ asset('images/carousel/slide-2.jpg') }}" alt="SP Pest Control technician discussing service with a homeowner"
             class="absolute inset-0 w-full h-full object-cover" :class="slide === 1 ? 'kenburns' : ''">
        <div class="absolute inset-0 bg-gradient-to-r from-secondary/95 via-secondary/70 to-secondary/25"></div>
        <div class="relative h-full flex items-center px-7 md:px-16">
            <div class="max-w-xl">
                <span class="inline-block bg-white/10 backdrop-blur-sm border border-white/20 text-white text-xs uppercase tracking-widest px-4 py-2 rounded-full mb-5">Welcome to SP Pest Control</span>
                <h1 class="font-display uppercase text-3xl md:text-5xl text-white leading-tight mb-5">Effective Solutions for Every Pest <span class="text-accent">Problem</span></h1>
                <p class="text-white/80 mb-7 max-w-md">From first inspection to ongoing protection, we treat the source — not just the symptoms.</p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('contact') }}" class="bg-primary text-white uppercase text-sm font-semibold px-6 py-3">Get Started</a>
                    <a href="{{ route('about') }}" class="border border-white text-white uppercase text-sm font-semibold px-6 py-3 hover:bg-white hover:text-secondary transition">Learn More</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Slide 3: branded design (gradient background, no external photo) --}}
    <div class="absolute inset-0 transition-opacity duration-700 ease-out hero-scheme-a"
         :class="slide === 2 ? 'opacity-100 z-10' : 'opacity-0 z-0'">
        <svg class="floating-pest f-1" viewBox="0 0 100 100"><g><ellipse cx="30" cy="50" rx="10" ry="7"/><ellipse cx="48" cy="50" rx="12" ry="8"/><ellipse cx="68" cy="50" rx="9" ry="7"/><rect x="60" y="28" width="3" height="20" transform="rotate(20 61 38)"/><rect x="74" y="28" width="3" height="20" transform="rotate(-20 75 38)"/></g></svg>
        <svg class="floating-pest f-3" viewBox="0 0 100 100"><g><circle cx="50" cy="50" r="14"/><rect x="10" y="49" width="26" height="3" transform="rotate(15 23 50)"/><rect x="10" y="59" width="26" height="3" transform="rotate(-15 23 60)"/><rect x="64" y="49" width="26" height="3" transform="rotate(-15 77 50)"/><rect x="64" y="59" width="26" height="3" transform="rotate(15 77 60)"/></g></svg>
        <svg class="floating-pest f-4" viewBox="0 0 100 100"><g><ellipse cx="45" cy="55" rx="24" ry="15"/><circle cx="74" cy="48" r="9"/><circle cx="83" cy="42" r="3"/><path d="M22 55 Q4 40 10 20" fill="none" stroke="#fff" stroke-width="3"/></g></svg>
        <svg class="floating-pest f-8" viewBox="0 0 100 100"><g><ellipse cx="45" cy="55" rx="22" ry="14"/><circle cx="70" cy="46" r="8"/><path d="M25 55 Q10 42 14 24" fill="none" stroke="#fff" stroke-width="3"/></g></svg>
        <div class="relative h-full flex items-center px-7 md:px-16">
            <div class="max-w-xl">
                <img src="{{ asset('images/logo.png') }}" alt="SP Pest Control" class="w-20 h-20 rounded-full border-2 border-white/50 mb-5">
                <span class="inline-block bg-white/10 backdrop-blur-sm border border-white/20 text-white text-xs uppercase tracking-widest px-4 py-2 rounded-full mb-5">Welcome to SP Pest Control</span>
                <h1 class="font-display uppercase text-3xl md:text-5xl text-white leading-tight mb-5">Say Goodbye to Unwanted <span class="text-accent">Pests</span></h1>
                <p class="text-white/80 mb-7 max-w-md">Scheduled visits, not guesswork — full coverage for every pest we treat.</p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('contact') }}" class="bg-primary text-white uppercase text-sm font-semibold px-6 py-3">Get Started</a>
                    <a href="{{ route('about') }}" class="border border-white text-white uppercase text-sm font-semibold px-6 py-3 hover:bg-white hover:text-secondary transition">Learn More</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Slide 4: family + house --}}
    <div class="absolute inset-0 transition-opacity duration-700 ease-out"
         :class="slide === 3 ? 'opacity-100 z-10' : 'opacity-0 z-0'">
        <img src="{{ asset('images/carousel/slide-4.jpg') }}" alt="Happy family in front of their home, protected by SP Pest Control"
             class="absolute inset-0 w-full h-full object-cover" :class="slide === 3 ? 'kenburns' : ''">
        <div class="absolute inset-0 bg-gradient-to-r from-secondary/95 via-secondary/70 to-secondary/25"></div>
        <div class="relative h-full flex items-center px-7 md:px-16">
            <div class="max-w-xl">
                <span class="inline-block bg-white/10 backdrop-blur-sm border border-white/20 text-white text-xs uppercase tracking-widest px-4 py-2 rounded-full mb-5">Welcome to SP Pest Control</span>
                <h1 class="font-display uppercase text-3xl md:text-5xl text-white leading-tight mb-5">Your Family's Safety Is Our Top <span class="text-accent">Priority</span></h1>
                <p class="text-white/80 mb-7 max-w-md">Protecting the homes and families you love, season after season.</p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('contact') }}" class="bg-primary text-white uppercase text-sm font-semibold px-6 py-3">Get Started</a>
                    <a href="{{ route('about') }}" class="border border-white text-white uppercase text-sm font-semibold px-6 py-3 hover:bg-white hover:text-secondary transition">Learn More</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Arrows --}}
    <button @click="prev()" aria-label="Previous slide" class="absolute left-3 md:left-6 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-white/15 hover:bg-white/30 border border-white/30 text-white flex items-center justify-center text-xl backdrop-blur-sm transition">‹</button>
    <button @click="next()" aria-label="Next slide" class="absolute right-3 md:right-6 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-white/15 hover:bg-white/30 border border-white/30 text-white flex items-center justify-center text-xl backdrop-blur-sm transition">›</button>

    {{-- Dots --}}
    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 flex gap-2">
        <button @click="go(0)" aria-label="Slide 1" :class="slide === 0 ? 'bg-white w-6' : 'bg-white/40 w-2.5'" class="h-2.5 rounded-full transition-all duration-200"></button>
        <button @click="go(1)" aria-label="Slide 2" :class="slide === 1 ? 'bg-white w-6' : 'bg-white/40 w-2.5'" class="h-2.5 rounded-full transition-all duration-200"></button>
        <button @click="go(2)" aria-label="Slide 3" :class="slide === 2 ? 'bg-white w-6' : 'bg-white/40 w-2.5'" class="h-2.5 rounded-full transition-all duration-200"></button>
        <button @click="go(3)" aria-label="Slide 4" :class="slide === 3 ? 'bg-white w-6' : 'bg-white/40 w-2.5'" class="h-2.5 rounded-full transition-all duration-200"></button>
    </div>
</div>

{{-- ============ PEST TEASER ============ --}}
<section class="reveal py-20 border-b-2 border-line">
    <div class="max-w-6xl mx-auto px-7">
        <div class="flex flex-wrap justify-between items-end gap-4 mb-10">
            <div>
                <p class="font-mono text-xs tracking-widest uppercase text-primary mb-3">What We Treat</p>
                <h2 class="font-display uppercase text-2xl md:text-3xl">We treat pests other companies miss</h2>
            </div>
            <a href="{{ route('services.residential') }}" class="text-primary text-sm font-semibold uppercase whitespace-nowrap">See all pests &rarr;</a>
        </div>

        @php
            $allPests = collect([$featured])->filter()->values()->merge($pests);
            $ringColors = ['ring-primary', 'ring-secondary', 'ring-accent'];
        @endphp

        <div class="pest-badge-row reveal-stagger">
            @foreach ($allPests as $i => $p)
                <a href="{{ route('services.pest', $p) }}" class="pest-badge-wrap">
                    <div class="pest-badge {{ $ringColors[$i % 3] }}">
                        @if ($p->photo_path)
                            <img src="{{ asset($p->photo_path) }}" alt="{{ $p->name }}">
                        @endif
                    </div>
                    <span class="pest-badge-label">{{ $p->name }}</span>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ============ SERVICES IN MOTION (continuous marquee) ============ --}}
<section class="py-14 border-b-2 border-line bg-white">
    <div class="max-w-6xl mx-auto px-7 mb-6 text-center">
        <p class="font-mono text-xs tracking-widest uppercase text-primary mb-2">Full Coverage</p>
        <h2 class="font-display uppercase text-xl">Every pest we treat, always on call</h2>
    </div>
    @php
        $marqueePests = collect([$featured])->filter()->values()->merge($pests);
    @endphp
    <div class="marquee">
        <div class="marquee-track">
            @foreach ($marqueePests as $p)
                <span class="marquee-item">
                    @if ($p->photo_path)
                        <img src="{{ asset($p->photo_path) }}" alt="">
                    @endif
                    {{ $p->name }}
                </span>
            @endforeach
            {{-- duplicated so the loop is seamless --}}
            @foreach ($marqueePests as $p)
                <span class="marquee-item">
                    @if ($p->photo_path)
                        <img src="{{ asset($p->photo_path) }}" alt="">
                    @endif
                    {{ $p->name }}
                </span>
            @endforeach
        </div>
    </div>
</section>

{{-- ============ EXPERTISE / TRUST ============ --}}
<section class="reveal py-20 border-b-2 border-line bg-bgAlt">
    <div class="max-w-6xl mx-auto px-7 grid md:grid-cols-2 gap-12 items-center">
        <div>
            <p class="font-mono text-xs tracking-widest uppercase text-primary mb-3">Why SP Pest Control</p>
            <h2 class="font-display uppercase text-2xl md:text-3xl mb-5">Pest control that treats the source</h2>
            <p class="text-inkMuted mb-6 max-w-xl">
                Most pest problems are prevented, not just treated. Our technicians are trained to find the
                entry point, treat the source, and set up a monitoring schedule - so you're not calling us
                back every few weeks. We use targeted gel baits and low-toxicity perimeter treatments rather
                than blanket chemical spraying, so it's safe around kids and pets.
            </p>
            <a href="{{ route('about') }}" class="text-primary text-sm font-semibold uppercase">Learn more about us &rarr;</a>
        </div>
      <div class="border-2 border-primary bg-white aspect-video flex items-center justify-center overflow-hidden">
    <img src="{{ asset('images/technician.jpg') }}" alt="SP Pest Control technician" class="w-full h-full object-cover" style="object-position: top;">>
</div>
    </div>
    </div>
</section>

{{-- ============ GUARANTEE (from real plan terms) ============ --}}
<section class="reveal py-20 border-b-2 border-line">
    <div class="max-w-4xl mx-auto px-7 text-center">
        <p class="font-mono text-xs tracking-widest uppercase text-accent mb-3">Our Guarantee</p>
        <h2 class="font-display uppercase text-2xl md:text-3xl mb-6">We stand behind every scheduled plan</h2>
        <p class="text-inkMuted max-w-2xl mx-auto mb-8">
            Every protection plan includes a guarantee built into its terms - not a marketing add-on.
            RoachGuard 360 is priced to break the breeding cycle across every season, and AntArmor 365
            includes a 30-day Ant-Free guarantee: if you see a significant trail within 30 days of a
            scheduled treatment, we come back and spot-treat the area at no charge.
        </p>
        <a href="{{ route('plans.index') }}" class="inline-block bg-primary text-white uppercase text-sm font-semibold px-6 py-3">See Plan Details</a>
    </div>
</section>

{{-- ============ PROTECTION PLANS ============ --}}
<section class="reveal py-20 border-b-2 border-line bg-bgAlt">
    <div class="max-w-6xl mx-auto px-7">
        <p class="font-mono text-xs tracking-widest uppercase text-primary mb-3">Home Protection Plans</p>
        <h2 class="font-display uppercase text-2xl md:text-3xl mb-10">Subscriptions, not surprise invoices</h2>
        <div class="reveal-stagger grid md:grid-cols-3 gap-6">
            @foreach ($plans as $plan)
                <div class="plan-card border-2 {{ ($plan->meta['featured'] ?? false) ? 'border-accent' : 'border-primary' }} bg-white p-6 flex flex-col">
                    <h3 class="font-display uppercase text-lg mb-2">{{ $plan->name }}</h3>
                    <p class="font-mono text-2xl mb-3">R{{ number_format($plan->price, 0) }}<span class="text-sm text-inkFaint">/mo</span></p>
                    <p class="text-inkMuted text-sm mb-5 flex-1">{{ $plan->description }}</p>
                    <a href="{{ route('plans.index') }}" class="text-primary text-sm font-semibold uppercase">Learn more &rarr;</a>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============ COMMERCIAL TEASER ============ --}}
@if ($industries->count())
<section class="reveal py-20 border-b-2 border-line">
    <div class="max-w-6xl mx-auto px-7 grid md:grid-cols-2 gap-12 items-center">
        <div>
            <p class="font-mono text-xs tracking-widest uppercase text-primary mb-3">Commercial</p>
            <h2 class="font-display uppercase text-2xl md:text-3xl mb-5">Love us at home? We cover your business too.</h2>
            <p class="text-inkMuted mb-6 max-w-xl">
                SP Pest Control also builds custom plans for multi-family housing, retail, restaurants, and
                schools - tailored to the layout, traffic, and compliance needs of your business.
            </p>
            <a href="{{ route('services.commercial') }}" class="text-primary text-sm font-semibold uppercase">See commercial services &rarr;</a>
        </div>
        <div class="border-2 border-line divide-y-2 divide-line">
            @foreach ($industries as $industry)
                <div class="p-5">
                    <h4 class="font-display uppercase text-sm mb-1">{{ $industry->name }}</h4>
                    <p class="text-inkMuted text-sm">{{ \Illuminate\Support\Str::limit($industry->description, 110) }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ============ VIDEO SECTION (drop-in ready) ============ --}}
<section class="reveal py-20 border-b-2 border-line bg-bgAlt">
    <div class="max-w-4xl mx-auto px-7 text-center">
        <p class="font-mono text-xs tracking-widest uppercase text-primary mb-3">See It In Action</p>
        <h2 class="font-display uppercase text-2xl md:text-3xl mb-8">How a scheduled visit works</h2>
        <div class="border-2 border-primary bg-white aspect-video flex items-center justify-center max-w-2xl mx-auto">
            <video controls class="w-full h-full object-cover" poster="{{ asset('images/video-poster.jpg') }}">
                <source src="{{ asset('videos/how-it-works.mp4') }}" type="video/mp4">
                Your browser doesn't support embedded video.
            </video>
        </div>
    </div>
</section>

{{-- ============ FINAL CTA ============ --}}
<section class="reveal py-16 bg-secondary text-white text-center">
    <div class="max-w-2xl mx-auto px-7">
        <h2 class="font-display uppercase text-2xl md:text-3xl mb-4">Ready to get started?</h2>
        <p class="text-white/70 mb-8">Request a free inspection, or ask us anything about pricing and scheduling.</p>
        <a href="{{ route('contact') }}" class="inline-block bg-primary text-white uppercase text-sm font-semibold px-8 py-3">Request an Inspection</a>
    </div>
</section>

@endsection
