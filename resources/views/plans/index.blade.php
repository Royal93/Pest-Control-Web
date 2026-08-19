@extends('layouts.app')

@section('title', 'Protection Plans — SP Pest Control')

@section('content')
<x-pest-hero scheme="d" eyebrow="Home Protection Plans" heading-plain="Subscriptions, not" heading-accent="surprise invoices">
    <p class="text-white/80 text-lg max-w-xl">
        A once-off call-out solves today's problem. A subscription plan stops the next one
        from starting — at a fixed monthly cost, with scheduled visits built in.
    </p>
</x-pest-hero>

<section class="max-w-6xl mx-auto px-7 py-16">
    <div class="grid md:grid-cols-3 gap-6">
        @foreach ($plans as $plan)
            <div class="border-2 {{ $plan->meta['featured'] ?? false ? 'border-accent' : 'border-primary' }} flex flex-col">
                <div class="p-7 border-b-2 border-line">
                    <h2 class="font-display uppercase text-lg mb-2">{{ $plan->name }}</h2>
                    <p class="font-mono text-3xl">R{{ number_format($plan->price, 0) }}<span class="text-sm text-inkFaint">/mo</span></p>
                    <p class="text-inkMuted text-sm mt-3">{{ $plan->description }}</p>
                </div>
                <div class="p-7 flex-1">
                    <ul class="space-y-3 text-sm text-inkMuted">
                        @foreach ($plan->meta['includes'] ?? [] as $item)
                            <li class="pl-4 relative">
                                <span class="absolute left-0 top-2 w-2 h-px bg-primary"></span>
                                {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                    <p class="font-mono text-xs text-inkFaint border-t-2 border-line mt-5 pt-4">
                        {{ $plan->meta['terms'] ?? '' }}
                    </p>
                </div>
                <div class="p-7">
                    <a href="{{ route('contact') }}" class="block text-center bg-primary text-white uppercase text-sm font-semibold py-3">
                        Get Started
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endsection
