@extends('layouts.app')

@section('title', 'Residential Pest Control — SP Pest Control')

@section('content')
<section class="max-w-6xl mx-auto px-7 py-16">
    <p class="font-mono text-xs tracking-widest uppercase text-primary mb-3">Residential</p>
    <h1 class="font-display uppercase text-3xl mb-4">Pests treated at the source</h1>
    <p class="text-inkMuted max-w-xl mb-12">
        Every treatment starts with an inspection, not a guess. Click any pest below for details on
        signs of infestation, health risks, and the impact it can have on a business.
    </p>

    @if ($featured)
        <a href="{{ route('services.pest', $featured) }}" class="block border-2 border-primary mb-10 grid md:grid-cols-2 hover:bg-bgAlt">
            <div class="bg-bgAlt p-10 flex items-center justify-center">
                @if ($featured->photo_path)
                    <img src="{{ asset($featured->photo_path) }}" alt="{{ $featured->name }}" class="max-h-56 object-contain">
                @endif
            </div>
            <div class="p-8">
                <p class="font-mono text-xs uppercase tracking-widest text-accent mb-2">Highest call-out volume</p>
                <h2 class="font-display uppercase text-2xl mb-3">{{ $featured->name }} Control</h2>
                <p class="text-inkMuted mb-5">{{ $featured->description }}</p>
                <span class="border-2 border-line px-5 py-3 text-sm uppercase font-semibold inline-block">
                    See Signs, Risks &amp; Impact &rarr;
                </span>
            </div>
        </a>
    @endif

    <div class="flex flex-wrap border-t-2 border-l-2 border-primary">
        @foreach ($pests as $pest)
            <a href="{{ route('services.pest', $pest) }}" class="pest-cell w-1/2 sm:w-1/2 md:w-1/4 border-r-2 border-b-2 border-primary p-6 bg-white flex flex-col gap-3 hover:bg-bgAlt">
                <div class="w-full h-28 flex items-center justify-center bg-bgAlt overflow-hidden">
                    @if ($pest->photo_path)
                        <img src="{{ asset($pest->photo_path) }}" alt="{{ $pest->name }}" class="w-full h-28 object-cover">
                    @endif
                </div>
                <h3 class="font-display uppercase text-sm">{{ $pest->name }}</h3>
                <p class="text-inkMuted text-sm">{{ $pest->description }}</p>
                <span class="text-primary text-xs font-semibold uppercase mt-auto">Learn more &rarr;</span>
            </a>
        @endforeach
    </div>
</section>
@endsection
