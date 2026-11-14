@extends('marketing.layouts.app')

@section('title', 'My Leads')

@section('content')
<h1 class="font-display uppercase text-2xl mb-6">My Leads</h1>

<form method="GET" class="mb-5 flex flex-wrap gap-3">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or phone…"
           class="border-2 border-line rounded-lg px-3 py-2 text-sm w-full max-w-xs">
    <select name="status" class="border-2 border-line rounded-lg px-3 py-2 text-sm bg-white" onchange="this.form.submit()">
        <option value="active" @selected($status === 'active')>Still working (New, Contacted, Quoted)</option>
        @foreach ($statuses as $option)
            <option value="{{ $option }}" @selected($status === $option)>{{ ucfirst(str_replace('_', ' ', $option)) }} only</option>
        @endforeach
        <option value="all" @selected($status === 'all')>All statuses</option>
    </select>
    <button type="submit" class="border-2 border-line rounded-lg px-4 py-2 text-sm font-semibold">Filter</button>
</form>

<div class="border-2 border-line rounded-xl bg-white overflow-x-auto">
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
                    <td class="px-5 py-3 whitespace-nowrap">{{ $lead->phone }}</td>
                    <td class="px-5 py-3">{{ $lead->suburb ?: '—' }}</td>
                    <td class="px-5 py-3">{{ $lead->pest_type ?: '—' }}</td>
                    <td class="px-5 py-3">
                        <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $lead->statusBadgeClasses() }}">{{ $lead->statusLabel() }}</span>
                    </td>
                    <td class="px-5 py-3 whitespace-nowrap {{ $lead->next_follow_up_date && $lead->next_follow_up_date->isPast() && !in_array($lead->status, ['converted', 'not_interested']) ? 'text-primary font-semibold' : '' }}">
                        {{ $lead->next_follow_up_date?->format('d M Y') ?: '—' }}
                    </td>
                    <td class="px-5 py-3 text-right whitespace-nowrap">
                        <a href="{{ route('marketing.leads.edit', $lead) }}" class="text-primary font-semibold">Open</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-5 py-6 text-center text-inkFaint">
                    @if ($status === 'active')
                        No leads to work right now. Finished leads are under the status filter, and you can claim new ones on <a href="{{ route('marketing.open.index') }}" class="text-primary font-semibold">Open Leads</a>.
                    @else
                        No leads here.
                    @endif
                </td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $leads->links() }}</div>
@endsection
