@extends('marketing.layouts.app')

@section('title', 'Update Lead')

@section('content')
<div class="flex flex-wrap items-center gap-3 mb-6">
    <h1 class="font-display uppercase text-2xl">{{ $lead->name }}</h1>
    <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $lead->statusBadgeClasses() }}">{{ $lead->statusLabel() }}</span>
</div>

@if ($errors->any())
    <div class="mb-6 max-w-2xl border-2 border-red-500 bg-red-50 text-ink px-4 py-3 rounded-lg text-sm">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="max-w-2xl border-2 border-line rounded-xl bg-white p-6 mb-6">
    <dl class="grid md:grid-cols-2 gap-4 text-sm">
        <div><dt class="text-xs uppercase text-inkFaint">Phone</dt><dd>{{ $lead->phone }}</dd></div>
        <div><dt class="text-xs uppercase text-inkFaint">Email</dt><dd>{{ $lead->email ?: '—' }}</dd></div>
        <div><dt class="text-xs uppercase text-inkFaint">Address</dt><dd>{{ $lead->address ?: '—' }}, {{ $lead->suburb ?: '—' }}</dd></div>
        <div><dt class="text-xs uppercase text-inkFaint">Province</dt><dd>{{ $lead->province ?: '—' }}</dd></div>
        <div><dt class="text-xs uppercase text-inkFaint">Pest problem</dt><dd>{{ $lead->pest_type ?: '—' }}</dd></div>
        <div><dt class="text-xs uppercase text-inkFaint">Interested plan</dt><dd>{{ $lead->service_interest ?: '—' }}</dd></div>
        <div><dt class="text-xs uppercase text-inkFaint">Heard about us via</dt><dd>{{ $lead->referral_channel ? ucfirst(str_replace('_', ' ', $lead->referral_channel)) : '—' }}</dd></div>
        <div><dt class="text-xs uppercase text-inkFaint">Assigned to you</dt><dd>{{ ($lead->assigned_at ?? $lead->created_at)->format('d M Y') }}</dd></div>
    </dl>

    @if ($lead->message)
        <div class="mt-4 text-sm">
            <dt class="text-xs uppercase text-inkFaint">What they need</dt>
            <dd class="whitespace-pre-line">{{ $lead->message }}</dd>
        </div>
    @endif
</div>

<div class="max-w-2xl border-2 border-primary rounded-xl bg-white p-6 mb-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="font-display uppercase text-sm">Capture a Sale</h2>
            <p class="text-xs text-inkFaint mt-1">Customer agreed? Complete the sale questionnaire and their service agreement is emailed to them to accept and pay online.</p>
        </div>
        @unless ($lead->status === 'converted')
            <a href="{{ route('marketing.sales.create', ['lead' => $lead->id]) }}"
               class="border-2 border-primary bg-primary text-white uppercase text-sm font-semibold px-5 py-2 rounded-lg whitespace-nowrap">
                Capture a Sale
            </a>
        @endunless
    </div>

    @if ($lead->sales->isNotEmpty())
        <table class="w-full text-sm mt-4">
            <tbody>
                @foreach ($lead->sales as $sale)
                    <tr class="border-t border-line">
                        <td class="py-2 font-semibold">{{ $sale->reference() }}</td>
                        <td class="py-2">{{ $sale->serviceLabel() }}</td>
                        <td class="py-2"><span class="text-xs font-semibold px-2 py-1 rounded-full {{ $sale->statusBadgeClasses() }}">{{ $sale->statusLabel() }}</span></td>
                        <td class="py-2 text-right">
                            @if ((int) $sale->sales_rep_id === (int) auth()->id())
                                <a href="{{ route('marketing.sales.show', $sale) }}" class="text-primary font-semibold">View</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<form method="POST" action="{{ route('marketing.leads.update', $lead) }}"
      class="max-w-2xl border-2 border-line rounded-xl bg-white p-6 flex flex-col gap-5 mb-6">
    @csrf
    @method('PUT')

    <div>
        <label class="block text-xs uppercase font-semibold mb-1">Status</label>
        <select name="status" class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm bg-white">
            @foreach ($statuses as $status)
                <option value="{{ $status }}" @selected(old('status', $lead->status) === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-xs uppercase font-semibold mb-1">Notes</label>
        <textarea name="notes" rows="4" maxlength="2000"
                  class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm">{{ old('notes', $lead->notes) }}</textarea>
    </div>

    <div>
        <label class="block text-xs uppercase font-semibold mb-1">Next follow-up date</label>
        <input type="date" name="next_follow_up_date" value="{{ old('next_follow_up_date', $lead->next_follow_up_date?->format('Y-m-d')) }}"
               class="border-2 border-line rounded-lg px-3 py-2 text-sm">
    </div>

    <div class="flex gap-3 mt-2">
        <button type="submit" class="border-2 border-primary bg-primary text-white uppercase text-sm font-semibold px-6 py-2 rounded-lg">
            Save
        </button>
        <a href="{{ route('marketing.leads.index') }}" class="border-2 border-line uppercase text-sm font-semibold px-6 py-2 rounded-lg">
            Back
        </a>
    </div>
</form>

<div class="max-w-2xl border-2 border-line rounded-xl bg-white p-6 mb-6">
    <h2 class="font-display uppercase text-sm mb-3">Documents</h2>

    <table class="w-full text-sm mb-5">
        <thead class="text-left uppercase text-xs text-inkFaint">
            <tr>
                <th class="py-2">What it is</th>
                <th class="py-2">Date</th>
                <th class="py-2"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($lead->documents as $document)
                <tr class="border-t border-line">
                    <td class="py-2">{{ $document->label }}</td>
                    <td class="py-2">{{ $document->created_at->format('d M Y') }}</td>
                    <td class="py-2 text-right whitespace-nowrap">
                        <a href="{{ route('marketing.leads.documents.download', [$lead, $document]) }}" class="text-primary font-semibold">Download</a>
                        <form method="POST" action="{{ route('marketing.leads.documents.destroy', [$lead, $document]) }}" class="inline ml-3"
                              onsubmit="return confirm('Remove this document?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 font-semibold">Remove</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3" class="py-3 text-inkFaint">No documents uploaded yet.</td></tr>
            @endforelse
        </tbody>
    </table>

    <form method="POST" action="{{ route('marketing.leads.documents.store', $lead) }}" enctype="multipart/form-data" class="flex flex-col gap-3">
        @csrf
        <div>
            <label class="block text-xs uppercase font-semibold mb-1">What is this document?</label>
            <input type="text" name="label" placeholder="e.g. Signed quote, ID copy, photo of the problem area" maxlength="100" required
                   class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs uppercase font-semibold mb-1">File</label>
            <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" required
                   class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm bg-white">
            <p class="text-xs text-inkFaint mt-1">PDF, JPG or PNG, up to 10 MB.</p>
        </div>
        <div>
            <button type="submit" class="border-2 border-primary bg-primary text-white uppercase text-sm font-semibold px-6 py-2 rounded-lg">
                Upload Document
            </button>
        </div>
    </form>
</div>
@endsection
