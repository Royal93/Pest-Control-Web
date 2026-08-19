@extends('layouts.app')

@section('title', 'Commercial Pest Control - SP Pest Control')

@section('content')
<x-pest-hero scheme="b" eyebrow="Commercial" heading-plain="Built around" heading-accent="your industry">
    <p class="text-white/80 text-lg max-w-xl">
        In shared and public-facing spaces, one pest issue rarely stays contained. We build
        service plans around the layout, traffic and compliance needs of your business.
    </p>
</x-pest-hero>

<section class="max-w-6xl mx-auto px-7 py-16">
    <div class="divide-y-2 divide-line border-y-2 border-line">
        @foreach ($industries as $i => $industry)
            <div id="{{ \Illuminate\Support\Str::slug($industry->name) }}" class="grid md:grid-cols-[0.9fr_1.6fr] gap-6 py-8 scroll-mt-28">
                <div>
                    <p class="font-mono text-inkFaint text-sm">0{{ $i + 1 }}</p>
                    <h2 class="font-display uppercase text-xl mt-2">{{ $industry->name }}</h2>
                </div>
                <div>
                    <p class="text-inkMuted mb-4">{{ $industry->description }}</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($industry->common_pests ?? [] as $pest)
                            <span class="font-mono text-xs uppercase text-primary border-2 border-line px-3 py-1">{{ $pest }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ============ SERVICES ============ --}}
    <div class="mt-20">
        <p class="font-mono text-xs tracking-widest uppercase text-primary mb-3">Services</p>
        <h2 class="font-display uppercase text-2xl mb-8">Reliable commercial pest control that works</h2>
        <div class="grid md:grid-cols-2 gap-6">
            <div class="border-2 border-primary p-6">
                <h3 class="font-display uppercase text-lg mb-3">Pest Control Services</h3>
                <p class="text-inkMuted text-sm">
                    Pests don't stand a chance with our comprehensive pest control services. From bed bug
                    treatments to fly control, small fly and drain services, general pest control, and rat
                    removal, we have your business covered. Our expert team knows how to stop infestations
                    fast and keep them from coming back, so you can enjoy peace of mind all year long.
                </p>
            </div>
            <div class="border-2 border-primary p-6">
                <h3 class="font-display uppercase text-lg mb-3">General Pest Control</h3>
                <p class="text-inkMuted text-sm">
                    You've put everything into your business - time, energy, passion. You've got enough on
                    your plate without worrying about pests showing up where they don't belong. From
                    restaurants to warehouses and retail spaces, we're here to help you protect what you've
                    built, so you can focus on what matters most - your business.
                </p>
            </div>
        </div>
    </div>
</section>
@endsection
