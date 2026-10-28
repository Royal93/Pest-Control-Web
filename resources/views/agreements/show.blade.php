@extends('layouts.app')

@section('title', 'Service Agreement '.$sale->reference().' | SP Pest Control')

@section('content')
<section class="bg-bgAlt py-12">
    <div class="max-w-3xl mx-auto px-6">
        <p class="text-xs uppercase tracking-widest text-primary font-semibold mb-1">SP Pest Control</p>
        <h1 class="font-display uppercase text-3xl mb-1">Service Agreement</h1>
        <p class="text-inkFaint text-sm mb-6">{{ $sale->reference() }} | Prepared for {{ $sale->contact_person }}</p>

        @if (session('status'))
            <div class="mb-6 border-2 border-accent bg-accent/10 px-4 py-3 rounded-lg text-sm">{{ session('status') }}</div>
        @endif

        @if ($sale->isCancelled())
            <div class="border-2 border-red-500 bg-red-50 rounded-xl p-6">
                <h2 class="font-display uppercase text-lg mb-2">This agreement is no longer valid</h2>
                <p class="text-sm">It was withdrawn by SP Pest Control. Please contact us on 011 394 1191 or admin@nomorepest.co.za if you have any questions.</p>
            </div>
        @else
            @if ($sale->isPaid())
                <div class="mb-6 border-2 border-accent bg-white rounded-xl p-6">
                    <h2 class="font-display uppercase text-lg mb-1 text-accentDark">Your service is active</h2>
                    <p class="text-sm">Thank you. Your first payment was received on {{ $sale->paid_at?->format('d M Y') }}. Check your email for your customer portal login.</p>
                    <a href="{{ route('login') }}" class="inline-block mt-4 border-2 border-primary uppercase text-sm font-semibold px-5 py-2 rounded-lg">Customer Login</a>
                </div>
            @elseif ($sale->isAwaitingPayment())
                <div class="mb-6 border-2 border-primary bg-white rounded-xl p-6">
                    <h2 class="font-display uppercase text-lg mb-1">Accepted. One step left.</h2>
                    <p class="text-sm">You accepted this agreement on {{ $sale->accepted_at?->format('d M Y \a\t H:i') }}. Please complete your first payment to activate your service.</p>
                    <a href="{{ route('agreements.pay', $sale->token) }}" class="inline-block mt-4 border-2 border-primary bg-primary text-white uppercase text-sm font-semibold px-6 py-3 rounded-lg">Continue to Payment</a>
                </div>
            @endif

            {{-- The agreement itself --}}
            <div class="border-2 border-line rounded-xl bg-white p-6 mb-6">
                <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                    <h2 class="font-display uppercase text-sm">Your details and service</h2>
                    <a href="{{ route('agreements.download', $sale->token) }}" class="text-primary text-sm font-semibold">Download PDF</a>
                </div>
                <dl class="grid sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
                    <div><dt class="text-xs uppercase text-inkFaint">Customer / business</dt><dd>{{ $sale->customer_name }}</dd></div>
                    <div><dt class="text-xs uppercase text-inkFaint">Contact person</dt><dd>{{ $sale->contact_person }}</dd></div>
                    <div><dt class="text-xs uppercase text-inkFaint">Phone</dt><dd>{{ $sale->phone }}</dd></div>
                    <div><dt class="text-xs uppercase text-inkFaint">Email</dt><dd>{{ $sale->email }}</dd></div>
                    <div><dt class="text-xs uppercase text-inkFaint">Service address</dt><dd>{{ $sale->service_address }}</dd></div>
                    <div><dt class="text-xs uppercase text-inkFaint">Suburb / city</dt><dd>{{ $sale->suburb }}</dd></div>
                    <div><dt class="text-xs uppercase text-inkFaint">Property</dt><dd>{{ $sale->propertyLabel() }}</dd></div>
                    <div><dt class="text-xs uppercase text-inkFaint">Service selected</dt><dd class="font-semibold">{{ $sale->serviceLabel() }}</dd></div>
                    <div><dt class="text-xs uppercase text-inkFaint">Children or pets on premises</dt><dd>{{ $sale->children_or_pets ? 'Yes' : 'No' }}</dd></div>
                    @if ($sale->includesRodent())
                        <div><dt class="text-xs uppercase text-inkFaint">Proposed bait stations</dt><dd>{{ $sale->bait_stations }}</dd></div>
                    @endif
                    @if ($sale->includesCockroach())
                        <div><dt class="text-xs uppercase text-inkFaint">Treatment areas</dt><dd>{{ $sale->treatment_areas }}</dd></div>
                    @endif
                    <div><dt class="text-xs uppercase text-inkFaint">Agreement date</dt><dd>{{ $sale->agreement_date?->format('d M Y') }}</dd></div>
                    <div><dt class="text-xs uppercase text-inkFaint">Inception contract date</dt><dd>{{ $sale->inception_date?->format('d M Y') }}</dd></div>
                    <div><dt class="text-xs uppercase text-inkFaint">Sales representative</dt><dd>{{ $sale->salesRepName() ?: 'SP Pest Control' }}</dd></div>
                </dl>
            </div>

            <div class="border-2 border-line rounded-xl bg-white p-6 mb-6">
                <h2 class="font-display uppercase text-sm mb-4">Charges</h2>
                <table class="w-full text-sm">
                    @if ((float) $sale->activation_fee > 0)
                        <tr class="border-b border-gray-200"><td class="py-2">Activation / installation fee (once off)</td><td class="py-2 text-right">R{{ number_format((float) $sale->activation_fee, 2) }}</td></tr>
                    @endif
                    <tr class="border-b border-gray-200"><td class="py-2">Monthly subscription</td><td class="py-2 text-right">R{{ number_format((float) $sale->monthly_subscription, 2) }}</td></tr>
                    <tr class="border-b border-gray-200"><td class="py-2">Payment frequency</td><td class="py-2 text-right">{{ $sale->frequencyLabel() }} (R{{ number_format($sale->periodAmount(), 2) }} per debit)</td></tr>
                    <tr><td class="py-3 font-semibold">First payment, via secure payment link</td><td class="py-3 text-right font-display text-xl text-primary">R{{ number_format($sale->initialPaymentAmount(), 2) }}</td></tr>
                </table>
                <p class="text-xs text-inkFaint mt-2">Then R{{ number_format($sale->periodAmount(), 2) }} debited {{ strtolower($sale->frequencyLabel()) }}, starting {{ $sale->firstDebitDate()->format('d M Y') }}.</p>
            </div>

            <div class="border-2 border-line rounded-xl bg-white p-6 mb-6">
                <h2 class="font-display uppercase text-sm mb-4">Service Terms &amp; Conditions</h2>
                <ol class="list-decimal pl-5 text-sm flex flex-col gap-2 text-inkMuted">
                    <li>SP Pest Control will provide only the selected service(s) at the address above. Cockroach treatments are generally scheduled approximately every three months; rodent maintenance is scheduled every month.</li>
                    <li>Subscription charges are billed and debited monthly unless a different payment frequency (quarterly, biannual or annual) is expressly agreed and recorded above. Initial activation/installation charges are separate.</li>
                    <li>The customer must provide safe access, disclose relevant hazards and follow reasonable treatment and safety instructions. Bait-station quantities and treatment requirements may be adjusted after site assessment, with any additional charge approved before proceeding.</li>
                    <li>This agreement covers the selected pests only. Other pests, structural repairs, damage caused by infestation and services outside the agreed scope are excluded unless separately quoted and accepted.</li>
                    <li>The initial contract term is twelve (12) months. After the initial term, the agreement continues month-to-month and may be cancelled on thirty (30) days' written notice. Statutory early-cancellation rights remain unaffected. Any price change requires prior written notice and must comply with applicable law.</li>
                    <li>A secure payment link will be sent for the first initial payment. Following successful payment, recurring monthly debits will commence on the agreed debit date, unless another frequency is recorded above. Recurring debits require the customer's express authorisation through the payment link or a separate debit-order mandate. Service activation follows payment confirmation.</li>
                </ol>
            </div>

            @if ($sale->isAwaitingAcceptance())
                <div class="border-2 border-primary rounded-xl bg-white p-6" id="accept">
                    <h2 class="font-display uppercase text-sm mb-3">Customer Acceptance</h2>

                    @if ($errors->any())
                        <div class="mb-4 border-2 border-red-500 bg-red-50 px-4 py-3 rounded-lg text-sm">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('agreements.accept', $sale->token) }}" class="flex flex-col gap-4">
                        @csrf
                        <label class="flex items-start gap-3 text-sm">
                            <input type="checkbox" name="confirm" value="1" required class="mt-1" @checked(old('confirm'))>
                            <span>I confirm that the information above is correct, the selected service and charges have been explained, and I have received or can access these terms before accepting.</span>
                        </label>
                        <div>
                            <label for="accepted_name" class="block text-xs uppercase font-semibold mb-1">Type your full name to accept</label>
                            <input id="accepted_name" type="text" name="accepted_name" value="{{ old('accepted_name') }}" required minlength="3" maxlength="255"
                                   autocomplete="name" class="w-full max-w-md border-2 border-line rounded-lg px-3 py-2 text-sm">
                        </div>
                        <p class="text-xs text-inkFaint">Your name, the date and time, and your IP address are recorded as your electronic acceptance.</p>
                        <div>
                            <button type="submit" class="border-2 border-primary bg-primary text-white uppercase text-sm font-semibold px-6 py-3 rounded-lg">
                                Accept Agreement
                            </button>
                        </div>
                    </form>
                </div>
            @elseif ($sale->accepted_at)
                <div class="border-2 border-line rounded-xl bg-white p-6 text-sm">
                    <h2 class="font-display uppercase text-sm mb-2">Customer Acceptance</h2>
                    <p>Accepted electronically by <strong>{{ $sale->accepted_name }}</strong> on {{ $sale->accepted_at->format('d M Y \a\t H:i') }}.</p>
                </div>
            @endif
        @endif
    </div>
</section>
@endsection
