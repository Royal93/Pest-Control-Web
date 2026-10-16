@extends('admin.layouts.app')

@section('title', $lead->name)

@section('content')
<div class="flex flex-wrap items-center gap-3 mb-1">
    <h1 class="font-display uppercase text-2xl">{{ $lead->name }}</h1>
    <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $lead->statusBadgeClasses() }}">{{ $lead->statusLabel() }}</span>
    @if ($hasAccount)
        <span class="text-xs font-semibold px-2 py-1 rounded-full bg-green-100 text-green-700">Has customer login</span>
    @endif
</div>
<p class="text-sm text-inkFaint mb-6">Captured by {{ $lead->capturedBy?->name ? $lead->capturedBy->name.' '.$lead->capturedBy->surname : 'the contact form / chatbot' }} on {{ $lead->created_at->format('d M Y') }}</p>

<div class="max-w-2xl border-2 border-line rounded-xl bg-white p-6 mb-6">
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
        <div><dt class="text-xs uppercase text-inkFaint">Next follow-up</dt><dd>{{ $lead->next_follow_up_date?->format('d M Y') ?: '—' }}</dd></div>
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

<div class="max-w-2xl border-2 border-line rounded-xl bg-white p-6 mb-6">
    <h2 class="font-display uppercase text-sm mb-3">Update Status</h2>
    <form method="POST" action="{{ route('admin.leads.updateStatus', $lead) }}" class="flex flex-wrap items-center gap-3">
        @csrf
        @method('PATCH')
        <select name="status" class="border-2 border-line rounded-lg px-3 py-2 text-sm bg-white">
            @foreach ($statuses as $status)
                <option value="{{ $status }}" @selected($lead->status === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
            @endforeach
        </select>
        <button type="submit" class="border-2 border-primary bg-primary text-white uppercase text-sm font-semibold px-5 py-2 rounded-lg">
            Save Status
        </button>
    </form>
</div>

<div class="max-w-2xl border-2 border-line rounded-xl bg-white p-6">
    <h2 class="font-display uppercase text-sm mb-3">Documents</h2>
    <table class="w-full text-sm">
        <thead class="text-left uppercase text-xs text-inkFaint">
            <tr>
                <th class="py-2">What it is</th>
                <th class="py-2">Uploaded by</th>
                <th class="py-2">Date</th>
                <th class="py-2"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($lead->documents as $document)
                <tr class="border-t border-line">
                    <td class="py-2">{{ $document->label }}</td>
                    <td class="py-2">{{ $document->uploadedBy?->name ? $document->uploadedBy->name.' '.$document->uploadedBy->surname : '—' }}</td>
                    <td class="py-2">{{ $document->created_at->format('d M Y') }}</td>
                    <td class="py-2 text-right">
                        <a href="{{ route('admin.leads.documents.download', [$lead, $document]) }}" class="text-primary font-semibold">Download</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="py-4 text-inkFaint">No documents uploaded for this lead yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<a href="{{ route('admin.leads.index') }}" class="inline-block mt-6 text-sm font-semibold text-primary">&larr; Back to Leads</a>
@endsection
