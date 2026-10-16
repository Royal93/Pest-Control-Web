@extends('marketing.layouts.app')

@section('title', 'Update Lead')

@section('content')
<h1 class="font-display uppercase text-2xl mb-6">{{ $lead->name }}</h1>

<div class="max-w-2xl border-2 border-line rounded-xl bg-white p-6 mb-6">
    <dl class="grid md:grid-cols-2 gap-4 text-sm">
        <div><dt class="text-xs uppercase text-inkFaint">Phone</dt><dd>{{ $lead->phone }}</dd></div>
        <div><dt class="text-xs uppercase text-inkFaint">Email</dt><dd>{{ $lead->email ?: '—' }}</dd></div>
        <div><dt class="text-xs uppercase text-inkFaint">Address</dt><dd>{{ $lead->address ?: '—' }}, {{ $lead->suburb ?: '—' }}</dd></div>
        <div><dt class="text-xs uppercase text-inkFaint">Province</dt><dd>{{ $lead->province ?: '—' }}</dd></div>
        <div><dt class="text-xs uppercase text-inkFaint">Pest problem</dt><dd>{{ $lead->pest_type ?: '—' }}</dd></div>
        <div><dt class="text-xs uppercase text-inkFaint">Interested plan</dt><dd>{{ $lead->service_interest ?: '—' }}</dd></div>
        <div><dt class="text-xs uppercase text-inkFaint">Heard about us via</dt><dd>{{ $lead->referral_channel ? ucfirst(str_replace('_', ' ', $lead->referral_channel)) : '—' }}</dd></div>
        <div><dt class="text-xs uppercase text-inkFaint">Captured</dt><dd>{{ $lead->created_at->format('d M Y') }}</dd></div>
    </dl>
</div>

<form method="POST" action="{{ route('marketing.leads.update', $lead) }}"
      class="max-w-2xl border-2 border-line rounded-xl bg-white p-6 flex flex-col gap-5">
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
@endsection
