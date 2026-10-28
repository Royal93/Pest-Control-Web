@extends('admin.layouts.app')

@section('title', $sale->reference())

@section('content')
<div class="flex flex-wrap items-center gap-3 mb-1">
    <h1 class="font-display uppercase text-2xl">{{ $sale->reference() }}</h1>
    <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $sale->statusBadgeClasses() }}">{{ $sale->statusLabel() }}</span>
</div>
<p class="text-sm text-inkFaint mb-6">
    {{ $sale->customer_name }} | Sales rep: {{ $sale->salesRepName() ?: 'Office' }}
    @if ($sale->capturedByAdmin) | Captured by {{ $sale->capturedByAdmin->name }} (admin) @endif
    @if ($sale->lead) | Lead: <a href="{{ route('admin.leads.show', $sale->lead) }}" class="text-primary font-semibold">{{ $sale->lead->name }}</a> @endif
    @if ($sale->customer) | Customer: <a href="{{ route('admin.customers.show', $sale->customer) }}" class="text-primary font-semibold">{{ $sale->customer->email }}</a> @endif
</p>

<div class="grid lg:grid-cols-3 gap-6 items-start">
    <div class="lg:col-span-2 flex flex-col gap-6">
        @include('sales._details', ['sale' => $sale])

        <div class="border-2 border-line rounded-xl bg-white p-6">
            <h2 class="font-display uppercase text-sm mb-3">Payments</h2>
            <table class="w-full text-sm">
                <thead class="text-left uppercase text-xs text-inkFaint">
                    <tr><th class="py-2">Reference</th><th class="py-2">Amount</th><th class="py-2">Status</th><th class="py-2">Date</th></tr>
                </thead>
                <tbody>
                    @forelse ($sale->payments as $payment)
                        <tr class="border-t border-line">
                            <td class="py-2">{{ $payment->reference }}</td>
                            <td class="py-2">R{{ number_format((float) $payment->amount, 2) }}</td>
                            <td class="py-2">{{ ucfirst($payment->status) }}@if ($payment->decline_reason) ({{ $payment->decline_reason }})@endif</td>
                            <td class="py-2">{{ $payment->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-3 text-inkFaint">No payment attempts yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="flex flex-col gap-4">
        <div class="border-2 border-line rounded-xl bg-white p-6 flex flex-col gap-3">
            <h2 class="font-display uppercase text-sm">Actions</h2>
            <a href="{{ route('admin.sales.download', $sale) }}" class="border-2 border-primary text-center uppercase text-sm font-semibold px-5 py-2 rounded-lg">
                Download Agreement PDF
            </a>

            @if ($sale->isAwaitingAcceptance() || $sale->isAwaitingPayment())
                <form method="POST" action="{{ route('admin.sales.resend', $sale) }}">
                    @csrf
                    <button type="submit" class="w-full border-2 border-primary bg-primary text-white uppercase text-sm font-semibold px-5 py-2 rounded-lg">
                        {{ $sale->isAwaitingPayment() ? 'Resend Payment Link' : 'Resend Agreement' }}
                    </button>
                </form>

                <div>
                    <label class="block text-xs uppercase font-semibold mb-1">Customer link</label>
                    <input type="text" readonly value="{{ $sale->isAwaitingPayment() ? $sale->paymentUrl() : $sale->agreementUrl() }}"
                           onclick="this.select()" class="w-full border-2 border-line rounded-lg px-3 py-2 text-xs bg-bgAlt">
                    <p class="text-xs text-inkFaint mt-1">Click to select, then copy into WhatsApp or SMS if needed.</p>
                </div>

                <form method="POST" action="{{ route('admin.sales.markPaid', $sale) }}"
                      onsubmit="return confirm('Only do this if the first payment of R{{ number_format($sale->initialPaymentAmount(), 2) }} has been received some other way (e.g. EFT). This activates the customer and sends their portal login. Continue?');">
                    @csrf
                    <button type="submit" class="w-full border-2 border-accent text-accentDark uppercase text-sm font-semibold px-5 py-2 rounded-lg">
                        Record First Payment Manually
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.sales.cancel', $sale) }}"
                      onsubmit="return confirm('Cancel this agreement? The customer\'s link will stop working.');">
                    @csrf
                    <button type="submit" class="w-full text-red-600 uppercase text-xs font-semibold px-5 py-2">
                        Cancel Agreement
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

<a href="{{ route('admin.sales.index') }}" class="inline-block mt-6 text-sm font-semibold text-primary">&larr; Back to Sales</a>
@endsection
