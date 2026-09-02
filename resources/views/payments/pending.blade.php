@extends('layouts.app')

@section('title', 'Payment Pending — SP Pest Control')

@section('content')
<section class="max-w-md mx-auto px-7 py-24 text-center">
    <h1 class="font-display uppercase text-2xl mb-3">Payment Pending</h1>
    <p class="text-inkMuted mb-2">
        If you selected Bank EFT or Retail payment, please complete the payment
        using the reference and details shown on the previous screen. We'll
        confirm automatically once it clears.
    </p>
    @if ($payment)
        <p class="text-inkFaint text-sm mb-8">Reference: {{ $payment->reference }}</p>
    @endif
    <a href="{{ route('home') }}" class="border-2 border-primary px-6 py-3 uppercase text-sm font-semibold">
        Back to Home
    </a>
</section>
@endsection
