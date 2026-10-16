@extends('marketing.layouts.app')

@section('title', 'My Leads')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <h1 class="font-display uppercase text-2xl">My Leads</h1>
    <a href="{{ route('marketing.leads.create') }}"
       class="border-2 border-primary bg-primary text-white uppercase text-sm font-semibold px-6 py-2 rounded-lg">
        Capture Lead
    </a>
</div>

<form method="GET" class="mb-5 flex flex-wrap gap-3">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or phone…"
           class="border-2 border-line rounded-lg px-3 py-2 text-sm w-full max-w-xs">
    <select name="status" class="border-2 border-line rounded-lg px-3 py-2 text-sm bg-white" onchange="this.form.submit()">
        <option value="">All statuses</option>
        @foreach ($statuses as $status)
            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
        @endforeach
    </select>
    <button type="submit" class="border-2 border-line rounded-lg px-4 py-2 text-sm font-semibold">Filter</button>
</form>

<div class="border-2 border-line rounded-xl bg-white overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-bgAlt text-left uppercase text-xs text-inkFaint">
            <tr>
                <th class="px-5 py-3">Name</th>
                <th class="px-5 py-3">Phone</th>
                <th class="px-5 py-3">Suburb</th>
                <th class="px-5 py-3">Pest</th>
                <th class="px-5 py-3">Status</th>
                <th class="px-5 py-3">Follow-up</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($leads as $lead)
                <tr class="border-t border-line">
                    <td class="px-5 py-3 font-semibold">{{ $lead->name }}</td>
                    <td class="px-5 py-3">{{ $lead->phone }}</td>
                    <td class="px-5 py-3">{{ $lead->suburb ?: '—' }}</td>
                    <td class="px-5 py-3">{{ $lead->pest_type ?: '—' }}</td>
                    <td class="px-5 py-3">{{ $lead->statusLabel() }}</td>
                    <td class="px-5 py-3 {{ $lead->next_follow_up_date && $lead->next_follow_up_date->isPast() && !in_array($lead->status, ['converted', 'not_interested']) ? 'text-primary font-semibold' : '' }}">
                        {{ $lead->next_follow_up_date?->format('d M Y') ?: '—' }}
                    </td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('marketing.leads.edit', $lead) }}" class="text-primary font-semibold">Update</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-5 py-6 text-center text-inkFaint">No leads yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $leads->links() }}</div>
@endsection
