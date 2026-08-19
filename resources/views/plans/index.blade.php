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

    {{-- ============ FULL PLAN DETAILS ============ --}}
    <div class="mt-20 space-y-16">
        @foreach ($plans as $plan)
            <div id="{{ \Illuminate\Support\Str::slug($plan->name) }}" class="border-2 border-line p-8">
                <p class="font-mono text-xs tracking-widest uppercase text-primary mb-2">Full Plan Details</p>
                <h2 class="font-display uppercase text-2xl mb-6">{{ $plan->name }}</h2>

                @if (!empty($plan->meta['why']))
                    <div class="mb-8">
                        <h3 class="font-display uppercase text-sm mb-2 text-primary">Why {{ explode(' ', $plan->name)[0] }}?</h3>
                        <p class="text-inkMuted text-sm max-w-3xl">{{ $plan->meta['why'] }}</p>
                    </div>
                @endif

                @if (!empty($plan->meta['math']))
                    <div class="mb-8">
                        <h3 class="font-display uppercase text-sm mb-2 text-primary">How the Pricing Works</h3>
                        <p class="text-inkMuted text-sm max-w-3xl">{{ $plan->meta['math'] }}</p>
                    </div>
                @endif

                @if (!empty($plan->meta['value_proposition']))
                    <div class="mb-8">
                        <h3 class="font-display uppercase text-sm mb-3 text-primary">The Value Proposition</h3>
                        <table class="w-full text-sm max-w-xl">
                            <tbody>
                                @foreach ($plan->meta['value_proposition'] as $row)
                                    <tr class="border-b-2 border-line">
                                        <td class="py-2 text-inkMuted">{{ $row['label'] }}</td>
                                        <td class="py-2 font-mono text-right">{{ $row['value'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if (!empty($plan->meta['savings']))
                            <p class="text-accent text-sm font-semibold mt-3">{{ $plan->meta['savings'] }}</p>
                        @endif
                    </div>
                @endif

                @if (!empty($plan->meta['why_reasons']))
                    <div class="mb-8">
                        <h3 class="font-display uppercase text-sm mb-3 text-primary">Why {{ $plan->name }} Is the Smarter Choice</h3>
                        <ol class="space-y-3 text-sm text-inkMuted max-w-3xl">
                            @foreach ($plan->meta['why_reasons'] as $i => $reason)
                                <li class="flex gap-3">
                                    <span class="font-mono text-primary flex-shrink-0">{{ $i + 1 }}.</span>
                                    <span>{{ $reason }}</span>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                @endif

                @if (!empty($plan->meta['guarantee']))
                    <div class="mb-8 border-2 border-accent p-5">
                        <p class="text-sm">{{ $plan->meta['guarantee'] }}</p>
                    </div>
                @endif

                @if (!empty($plan->meta['fine_print']))
                    <div>
                        <h3 class="font-display uppercase text-sm mb-3 text-inkFaint">The Fine Print (Made Simple)</h3>
                        <ul class="space-y-2 text-xs text-inkFaint max-w-2xl">
                            @foreach ($plan->meta['fine_print'] as $line)
                                <li>{{ $line }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</section>
@endsection
