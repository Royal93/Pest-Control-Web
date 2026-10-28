@extends('marketing.layouts.app')

@section('title', $sale->reference())

@section('content')
<div class="flex flex-wrap items-center gap-3 mb-1">
    <h1 class="font-display uppercase text-2xl">{{ $sale->reference() }}</h1>
    <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $sale->statusBadgeClasses() }}">{{ $sale->statusLabel() }}</span>
</div>
<p class="text-sm text-inkFaint mb-6">
    {{ $sale->customer_name }}
    @if ($sale->lead) | Lead: <a href="{{ route('marketing.leads.edit', $sale->lead) }}" class="text-primary font-semibold">{{ $sale->lead->name }}</a>@endif
</p>

<div class="max-w-3xl flex flex-col gap-6">
    <div class="flex flex-wrap gap-3">
        <a href="{{ route('marketing.sales.download', $sale) }}" class="border-2 border-primary uppercase text-sm font-semibold px-5 py-2 rounded-lg">
            Download Agreement PDF
        </a>
    </div>

    @if ($sale->isAwaitingAcceptance() || $sale->isAwaitingPayment())
        <div class="border-2 border-line rounded-xl bg-white p-5 text-sm">
            <p class="mb-2">
                {{ $sale->isAwaitingPayment()
                    ? 'The customer accepted. Waiting for their first payment. If they lost the email, share this payment link:'
                    : 'Waiting for the customer to accept. If they lost the email, share this link:' }}
            </p>
            <input type="text" readonly value="{{ $sale->isAwaitingPayment() ? $sale->paymentUrl() : $sale->agreementUrl() }}"
                   onclick="this.select()" class="w-full border-2 border-line rounded-lg px-3 py-2 text-xs bg-bgAlt">
        </div>
    @endif

    @include('sales._details', ['sale' => $sale])
</div>

<a href="{{ route('marketing.sales.index') }}" class="inline-block mt-6 text-sm font-semibold text-primary">&larr; Back to My Sales</a>
@endsection
