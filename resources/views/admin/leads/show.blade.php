@extends('admin.layouts.app')

@section('title', $lead->name)

@section('content')
<h1 class="font-display uppercase text-2xl mb-6">{{ $lead->name }}</h1>

<div class="max-w-2xl border-2 border-line rounded-xl bg-white p-6">
    <dl class="grid md:grid-cols-2 gap-4 text-sm">
        <div><dt class="text-xs uppercase text-inkFaint">Phone</dt><dd>{{ $lead->phone ?: '—' }}</dd></div>
        <div><dt class="text-xs uppercase text-inkFaint">Email</dt><dd>{{ $lead->email ?: '—' }}</dd></div>
        <div><dt class="text-xs uppercase text-inkFaint">Address</dt><dd>{{ $lead->address ?: '—' }}, {{ $lead->suburb ?: '—' }}</dd></div>
        <div><dt class="text-xs uppercase text-inkFaint">Province</dt><dd>{{ $lead->province ?: '—' }}</dd></div>
        <div><dt class="text-xs uppercase text-inkFaint">Property type</dt><dd>{{ $lead->property_type ?: '—' }}</dd></div>
        <div><dt class="text-xs uppercase text-inkFaint">Pest problem</dt><dd>{{ $lead->pest_type ?: '—' }}</dd></div>
        <div><dt class="text-xs uppercase text-inkFaint">Interested plan</dt><dd>{{ $lead->service_interest ?: '—' }}</dd></div>
        <div><dt class="text-xs uppercase text-inkFaint">Heard about us via</dt><dd>{{ $lead->referral_channel ? ucfirst(str_replace('_', ' ', $lead->referral_channel)) : '—' }}</dd></div>
        <div><dt class="text-xs uppercase text-inkFaint">Source</dt><dd>{{ ucfirst(str_replace('_', ' ', $lead->source)) }}</dd></div>
        <div><dt class="text-xs uppercase text-inkFaint">Captured by</dt><dd>{{ $lead->capturedBy?->name ? $lead->capturedBy->name.' '.$lead->capturedBy->surname : '—' }}</dd></div>
        <div><dt class="text-xs uppercase text-inkFaint">Status</dt><dd>{{ $lead->statusLabel() }}</dd></div>
        <div><dt class="text-xs uppercase text-inkFaint">Next follow-up</dt><dd>{{ $lead->next_follow_up_date?->format('d M Y') ?: '—' }}</dd></div>
        <div><dt class="text-xs uppercase text-inkFaint">Captured</dt><dd>{{ $lead->created_at->format('d M Y H:i') }}</dd></div>
        <div><dt class="text-xs uppercase text-inkFaint">Consent on record</dt><dd>{{ $lead->consent ? 'Yes' : 'No' }}</dd></div>
    </dl>

    @if ($lead->message)
        <div class="mt-4">
            <dt class="text-xs uppercase text-inkFaint">Message</dt>
            <dd class="text-sm">{{ $lead->message }}</dd>
        </div>
    @endif

    @if ($lead->notes)
        <div class="mt-4">
            <dt class="text-xs uppercase text-inkFaint">Rep notes</dt>
            <dd class="text-sm">{{ $lead->notes }}</dd>
        </div>
    @endif
</div>

<a href="{{ route('admin.leads.index') }}" class="inline-block mt-6 text-sm font-semibold text-primary">&larr; Back to Leads</a>
@endsection
