@extends('layouts.app')

@section('title', 'Secure Payment '.$sale->reference().' | SP Pest Control')

@section('content')
<section class="bg-bgAlt py-12">
    <div class="max-w-xl mx-auto px-6">
        <p class="text-xs uppercase tracking-widest text-primary font-semibold mb-1">SP Pest Control</p>
        <h1 class="font-display uppercase text-3xl mb-1">Secure Payment</h1>
        <p class="text-inkFaint text-sm mb-6">{{ $sale->reference() }} | {{ $sale->customer_name }}</p>

        @if (session('status'))
            <div class="mb-6 border-2 border-accent bg-accent/10 px-4 py-3 rounded-lg text-sm">{{ session('status') }}</div>
        @endif

        @if ($sale->isCancelled())
            <div class="border-2 border-red-500 bg-red-50 rounded-xl p-6 text-sm">This agreement is no longer valid. Please contact us on 011 394 1191.</div>
        @else
            <div class="border-2 border-line rounded-xl bg-white p-6 mb-6">
                <h2 class="font-display uppercase text-sm mb-4">{{ $sale->serviceLabel() }}</h2>
                <table class="w-full text-sm">
                    @if ((float) $sale->activation_fee > 0)
                        <tr class="border-b border-gray-200"><td class="py-2">Activation / installation fee</td><td class="py-2 text-right">R{{ number_format((float) $sale->activation_fee, 2) }}</td></tr>
                    @endif
                    <tr class="border-b border-gray-200">
                        <td class="py-2">First {{ strtolower($sale->frequencyLabel()) }} period ({{ $sale->monthsPerPeriod() }} {{ $sale->monthsPerPeriod() === 1 ? 'month' : 'months' }})</td>
                        <td class="py-2 text-right">R{{ number_format($sale->periodAmount(), 2) }}</td>
                    </tr>
                    <tr><td class="py-3 font-semibold">Pay now</td><td class="py-3 text-right font-display text-2xl text-primary">R{{ number_format($sale->initialPaymentAmount(), 2) }}</td></tr>
                </table>
                <p class="text-xs text-inkFaint mt-3">
                    By paying, you authorise SP Pest Control to debit R{{ number_format($sale->periodAmount(), 2) }} {{ strtolower($sale->frequencyLabel()) }} from the card you use, starting {{ $sale->firstDebitDate()->format('d M Y') }}, as set out in clause 6 of your agreement. Cancellation is on 30 days' written notice after the initial 12-month term.
                </p>
            </div>

            <form method="POST" action="{{ route('agreements.checkout', $sale->token) }}">
                @csrf
                <button type="submit" class="w-full border-2 border-primary bg-primary text-white uppercase text-sm font-semibold px-6 py-3 rounded-lg">
                    Pay R{{ number_format($sale->initialPaymentAmount(), 2) }} Securely
                </button>
            </form>
            <p class="text-xs text-inkFaint text-center mt-3">You will be taken to Netcash, our secure payment provider. Your card details are never stored by SP Pest Control.</p>
            <p class="text-center mt-6"><a href="{{ route('agreements.show', $sale->token) }}" class="text-primary text-sm font-semibold">View my agreement</a></p>
        @endif
    </div>
</section>
@endsection
