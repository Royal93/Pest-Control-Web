{{-- Read-only sale details, shared by admin and SP SALES. Expects $sale. --}}
<div class="border-2 border-line rounded-xl bg-white p-6">
    <h2 class="font-display uppercase text-sm mb-4">Agreement details</h2>
    <dl class="grid md:grid-cols-2 gap-4 text-sm">
        <div><dt class="text-xs uppercase text-inkFaint">Customer / business</dt><dd>{{ $sale->customer_name }}</dd></div>
        <div><dt class="text-xs uppercase text-inkFaint">Contact person</dt><dd>{{ $sale->contact_person }}</dd></div>
        <div><dt class="text-xs uppercase text-inkFaint">Phone</dt><dd>{{ $sale->phone }}</dd></div>
        <div><dt class="text-xs uppercase text-inkFaint">Email</dt><dd>{{ $sale->email }}</dd></div>
        <div><dt class="text-xs uppercase text-inkFaint">Service address</dt><dd>{{ $sale->service_address }}, {{ $sale->suburb }}</dd></div>
        <div><dt class="text-xs uppercase text-inkFaint">Property</dt><dd>{{ $sale->propertyLabel() }}</dd></div>
        <div><dt class="text-xs uppercase text-inkFaint">Service selected</dt><dd>{{ $sale->serviceLabel() }}</dd></div>
        <div><dt class="text-xs uppercase text-inkFaint">Children or pets on premises</dt><dd>{{ $sale->children_or_pets ? 'Yes' : 'No' }}</dd></div>
        @if ($sale->includesRodent())
            <div><dt class="text-xs uppercase text-inkFaint">Proposed bait stations</dt><dd>{{ $sale->bait_stations }}</dd></div>
        @endif
        @if ($sale->includesCockroach())
            <div><dt class="text-xs uppercase text-inkFaint">Treatment areas</dt><dd>{{ $sale->treatment_areas }}</dd></div>
        @endif
        <div><dt class="text-xs uppercase text-inkFaint">Agreement date</dt><dd>{{ $sale->agreement_date?->format('d M Y') }}</dd></div>
        <div><dt class="text-xs uppercase text-inkFaint">Inception contract date</dt><dd>{{ $sale->inception_date?->format('d M Y') }}</dd></div>
        <div><dt class="text-xs uppercase text-inkFaint">Activation / installation fee</dt><dd>R{{ number_format((float) $sale->activation_fee, 2) }}</dd></div>
        <div><dt class="text-xs uppercase text-inkFaint">Monthly subscription</dt><dd>R{{ number_format((float) $sale->monthly_subscription, 2) }}</dd></div>
        <div><dt class="text-xs uppercase text-inkFaint">Payment frequency</dt><dd>{{ $sale->frequencyLabel() }} (R{{ number_format($sale->periodAmount(), 2) }} per debit)</dd></div>
        <div><dt class="text-xs uppercase text-inkFaint">First payment</dt><dd class="font-semibold">R{{ number_format($sale->initialPaymentAmount(), 2) }}</dd></div>
    </dl>
</div>

<div class="border-2 border-line rounded-xl bg-white p-6">
    <h2 class="font-display uppercase text-sm mb-4">Progress</h2>
    @php
        $steps = [
            ['Agreement sent', $sale->sent_at ?? $sale->created_at, true],
            ['Accepted by customer', $sale->accepted_at, (bool) $sale->accepted_at],
            ['First payment received', $sale->paid_at, (bool) $sale->paid_at],
        ];
    @endphp
    <ol class="flex flex-col gap-3 text-sm">
        @foreach ($steps as [$label, $at, $done])
            <li class="flex items-center gap-3">
                <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold {{ $done ? 'bg-accent text-white' : 'bg-gray-100 text-inkFaint' }}">{{ $done ? '✓' : $loop->iteration }}</span>
                <span class="{{ $done ? 'font-semibold' : 'text-inkFaint' }}">{{ $label }}</span>
                @if ($done && $at)<span class="text-inkFaint">{{ $at->format('d M Y H:i') }}</span>@endif
            </li>
        @endforeach
        @if ($sale->isCancelled())
            <li class="text-red-600 font-semibold">Cancelled {{ $sale->cancelled_at?->format('d M Y H:i') }}</li>
        @endif
    </ol>
    @if ($sale->accepted_at)
        <p class="text-xs text-inkFaint mt-4">Accepted electronically by "{{ $sale->accepted_name }}" from IP {{ $sale->accepted_ip }}.</p>
    @endif
</div>
