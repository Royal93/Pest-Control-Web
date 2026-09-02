@extends('layouts.app')

@section('title', $pest->name . ' Control — SP Pest Control')

@section('content')
<x-pest-hero scheme="h" eyebrow="Residential Pest Control" :heading-plain="$pest->name" heading-accent="Control">
    <a href="{{ route('services.residential') }}" class="text-white/80 text-sm font-semibold uppercase">&larr; All Pests</a>
</x-pest-hero>

<section class="max-w-4xl mx-auto px-7 py-16">
    <div class="mb-10 flex flex-col md:flex-row gap-8 items-center">
        <div class="pest-badge pest-badge-lg ring-primary flex-shrink-0">
            @if ($pest->photo_path)
                <img src="{{ asset($pest->photo_path) }}" alt="{{ $pest->name }}">
            @endif
        </div>
        <div>
            <p class="text-inkMuted">{{ $pest->description }}</p>
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-6 mb-12">
        @if ($pest->infestation_signs)
            <div class="border-2 border-primary p-6">
                <h2 class="font-display uppercase text-sm mb-3">Signs of Infestation</h2>
                <p class="text-inkMuted text-sm">{{ $pest->infestation_signs }}</p>
            </div>
        @endif
        @if ($pest->health_risks)
            <div class="border-2 border-primary p-6">
                <h2 class="font-display uppercase text-sm mb-3">Health Risks</h2>
                <p class="text-inkMuted text-sm">{{ $pest->health_risks }}</p>
            </div>
        @endif
        @if ($pest->business_impact)
            <div class="border-2 border-accent p-6">
                <h2 class="font-display uppercase text-sm mb-3">Impact On Your Business</h2>
                <p class="text-inkMuted text-sm">{{ $pest->business_impact }}</p>
            </div>
        @endif
    </div>

    <p class="text-xs text-inkFaint mb-10 border-t-2 border-line pt-4">
        This information is provided for general awareness and is not medical advice. If you or someone
        else is experiencing a severe reaction to a bite or sting, seek medical attention.
    </p>
</section>
@endsection
