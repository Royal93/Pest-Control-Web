@extends('layouts.app')

@section('title', 'Payment Declined — SP Pest Control')

@section('content')
<section class="max-w-md mx-auto px-7 py-24 text-center">
    <h1 class="font-display uppercase text-2xl mb-3">Payment Declined</h1>
    <p class="text-inkMuted mb-2">
        Unfortunately your payment could not be processed{{ $reason ? ' — ' . $reason : '' }}.
    </p>
    @if ($payment)
        <p class="text-inkFaint text-sm mb-8">Reference: {{ $payment->reference }}</p>
    @endif
    <a href="{{ url()->previous() }}" class="border-2 border-primary px-6 py-3 uppercase text-sm font-semibold">
        Try Again
    </a>
</section>
@endsection
