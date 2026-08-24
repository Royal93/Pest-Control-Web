@extends('layouts.app')

@section('title', 'My Account - SP Pest Control')

@section('content')
<x-pest-hero scheme="g" eyebrow="My Account" heading-plain="Welcome back," :heading-accent="auth()->user()->name">
</x-pest-hero>

<section class="max-w-5xl mx-auto px-7 py-16 space-y-10">

    {{-- ============ NEXT VISIT SUMMARY ============ --}}
    <div class="border-2 {{ $nextVisit ? 'border-primary' : 'border-line' }} p-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <p class="font-mono text-xs tracking-widest uppercase text-primary mb-2">Next Visit</p>
            @if ($nextVisit)
                <p class="font-display uppercase text-xl">
                    {{ $nextVisit->visit_date?->format('d F Y') ?? 'Scheduling in progress' }}
                </p>
                <p class="text-inkMuted text-sm mt-1">{{ $subscription->plan->name }}</p>
            @elseif ($subscription)
                <p class="font-display uppercase text-xl">Technician will confirm by phone</p>
                <p class="text-inkMuted text-sm mt-1">{{ $subscription->plan->name }}</p>
            @else
                <p class="font-display uppercase text-xl">No active plan</p>
                <p class="text-inkMuted text-sm mt-1">Choose a protection plan below to get started.</p>
            @endif
        </div>
        <a href="#request-visit" class="border-2 border-line px-5 py-3 text-sm uppercase font-semibold">
            Report an Issue
        </a>
    </div>

    {{-- ============ PLAN + PAYMENT ============ --}}
    <div class="grid md:grid-cols-2 gap-6">
        <div class="border-2 border-line p-6">
            @livewire('plan-card')
        </div>
        <div class="border-2 border-line p-6">
            @livewire('payment-method-form')
        </div>
    </div>

    {{-- ============ REQUEST EXTRA VISIT ============ --}}
    <div id="request-visit" class="border-2 border-line p-6 scroll-mt-28">
        @livewire('request-visit-form')
    </div>

    {{-- ============ SERVICE + BILLING HISTORY ============ --}}
    <div class="border-2 border-line p-6">
        @livewire('service-history')
    </div>

    <div class="border-2 border-line p-6">
        @livewire('billing-history')
    </div>

    {{-- ============ ACCOUNT SETTINGS ============ --}}
    <div class="border-2 border-line p-6">
        @livewire('profile-settings')
    </div>

</section>
@endsection
