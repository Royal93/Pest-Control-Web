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

<x-pest-hero scheme="a" :compact="false" eyebrow="Residential &amp; Commercial Pest Control" heading-plain="The perimeter is where the problem" heading-accent="ends.">
    <p class="text-white/80 text-lg mb-8 max-w-xl">
        We don't spray and pray. SP Pest Control identifies where pests enter, treats the source,
        and builds a lasting barrier around your property - backed by scheduled visits, not one-off guesswork.
    </p>
    <div class="flex flex-wrap gap-4 justify-center md:justify-start mb-10">
        <a href="{{ route('contact') }}" class="bg-white text-primary uppercase text-sm font-semibold px-6 py-3">Request an Inspection</a>
        <a href="{{ route('plans.index') }}" class="border-2 border-white text-white uppercase text-sm font-semibold px-6 py-3">View Protection Plans</a>
    </div>
    <div class="flex flex-wrap gap-x-8 gap-y-2 justify-center md:justify-start text-sm text-white/70 font-mono uppercase tracking-wide">
        <span>{{ $pests->count() + 1 }}+ Pests Treated</span>
        <span>·</span>
        <span>90-Day Visit Cycles</span>
        <span>·</span>
        <span>Guaranteed Callback</span>
    </div>
</x-pest-hero>

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
            $cols = 6;
            $remainder = $allPests->count() % $cols;
            $mainItems = $remainder > 0 ? $allPests->slice(0, -$remainder) : $allPests;
            $trailingItems = $remainder > 0 ? $allPests->slice(-$remainder)->values() : collect();
        @endphp

        <div class="reveal-stagger pest-grid-frame flex flex-wrap border-2 border-primary">
            @foreach ($mainItems as $p)
                <a href="{{ route('services.pest', $p) }}" class="pest-cell w-1/2 sm:w-1/3 md:w-1/6 border-r-2 border-b-2 border-primary p-5 bg-white flex flex-col items-center text-center gap-2 hover:bg-bgAlt">
                    <div class="pest-photo-circle w-16 h-16">
                        @if ($p->photo_path)
                            <img src="{{ asset($p->photo_path) }}" alt="{{ $p->name }}">
                        @endif
                    </div>
                    <span class="font-display uppercase text-xs">{{ $p->name }}</span>
                </a>
            @endforeach
        </div>

        @if ($trailingItems->count())
        <div class="pest-grid-frame flex flex-wrap justify-center bg-white border-2 border-primary mt-[-2px]">
            @foreach ($trailingItems as $p)
                <a href="{{ route('services.pest', $p) }}" class="pest-cell w-1/2 sm:w-1/3 md:w-1/6 p-5 flex flex-col items-center text-center gap-2 hover:bg-bgAlt">
                    <div class="pest-photo-circle w-16 h-16">
                        @if ($p->photo_path)
                            <img src="{{ asset($p->photo_path) }}" alt="{{ $p->name }}">
                        @endif
                    </div>
                    <span class="font-display uppercase text-xs">{{ $p->name }}</span>
                </a>
            @endforeach
        </div>
        @endif
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
        <div class="border-2 border-primary bg-white aspect-video flex items-center justify-center">
            <span class="font-mono text-xs uppercase text-inkFaint">Technician photo goes here</span>
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
