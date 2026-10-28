@extends('admin.layouts.app')

@section('title', 'Leads')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <h1 class="font-display uppercase text-2xl">Leads</h1>
    <a href="{{ route('admin.leads.create') }}"
       class="border-2 border-primary bg-primary text-white uppercase text-sm font-semibold px-6 py-2 rounded-lg">
        Capture Lead
    </a>
</div>

@if ($unassignedCount > 0 && request('rep') !== 'unassigned')
    <div class="mb-5 border-2 border-primary bg-primary/10 rounded-lg px-4 py-3 text-sm flex flex-wrap items-center justify-between gap-2">
        <span><strong>{{ $unassignedCount }}</strong> {{ $unassignedCount === 1 ? 'lead is' : 'leads are' }} not assigned to a sales rep yet.</span>
        <a href="{{ route('admin.leads.index', ['rep' => 'unassigned']) }}" class="text-primary font-semibold">Show unassigned</a>
    </div>
@endif

<div class="flex gap-2 mb-5 border-b-2 border-line">
    @foreach ([
        'in_progress' => 'In Progress',
        'converted' => 'Converted',
        'not_interested' => 'Not Interested',
    ] as $key => $label)
        <a href="{{ route('admin.leads.index', ['tab' => $key]) }}"
           class="px-4 py-2 text-sm font-semibold border-b-2 -mb-0.5 {{ $tab === $key ? 'border-primary text-primary' : 'border-transparent text-inkFaint hover:text-ink' }}">
            {{ $label }} <span class="text-xs">({{ $counts[$key] }})</span>
        </a>
    @endforeach
</div>

<form method="GET" class="mb-5 flex flex-wrap gap-3">
    <input type="hidden" name="tab" value="{{ $tab }}">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, phone or email…"
           class="border-2 border-line rounded-lg px-3 py-2 text-sm w-full max-w-xs">
    <select name="rep" class="border-2 border-line rounded-lg px-3 py-2 text-sm bg-white" onchange="this.form.submit()">
        <option value="">All sales reps</option>
        <option value="unassigned" @selected(request('rep') === 'unassigned')>Unassigned</option>
        @foreach ($reps as $rep)
            <option value="{{ $rep->id }}" @selected((string) request('rep') === (string) $rep->id)>{{ $rep->fullName() }}</option>
        @endforeach
    </select>
    <select name="source" class="border-2 border-line rounded-lg px-3 py-2 text-sm bg-white" onchange="this.form.submit()">
        <option value="">All sources</option>
        @foreach (\App\Models\Lead::SOURCES as $key => $label)
            <option value="{{ $key }}" @selected(request('source') === $key)>{{ $label }}</option>
        @endforeach
    </select>
    @if ($tab === 'in_progress')
        <select name="status" class="border-2 border-line rounded-lg px-3 py-2 text-sm bg-white" onchange="this.form.submit()">
            <option value="">New, Contacted &amp; Quoted</option>
            @foreach (\App\Models\Lead::IN_PROGRESS_STATUSES as $status)
                <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }} only</option>
            @endforeach
        </select>
    @endif
    <button type="submit" class="border-2 border-line rounded-lg px-4 py-2 text-sm font-semibold">Filter</button>
</form>

<div class="border-2 border-line rounded-xl bg-white overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-bgAlt text-left uppercase text-xs text-inkFaint">
            <tr>
                <th class="px-5 py-3">Name</th>
                <th class="px-5 py-3">Phone</th>
                <th class="px-5 py-3">Source</th>
                <th class="px-5 py-3">Assigned To</th>
                <th class="px-5 py-3">Status</th>
                @if ($tab === 'converted')
                    <th class="px-5 py-3">Account</th>
                @endif
                <th class="px-5 py-3">Date</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($leads as $lead)
                <tr class="border-t border-line">
                    <td class="px-5 py-3 font-semibold">
                        <a href="{{ route('admin.leads.show', $lead) }}" class="hover:text-primary">{{ $lead->name }}</a>
                    </td>
                    <td class="px-5 py-3 whitespace-nowrap">{{ $lead->phone ?: '—' }}</td>
                    <td class="px-5 py-3">{{ $lead->sourceLabel() }}</td>
                    <td class="px-5 py-3">
                        <form method="POST" action="{{ route('admin.leads.assign', $lead) }}">
                            @csrf
                            @method('PATCH')
                            <select name="assigned_to" onchange="this.form.submit()"
                                    class="text-xs border-2 rounded-lg px-2 py-1 bg-white {{ $lead->assigned_to ? 'border-line' : 'border-primary text-primary font-semibold' }}">
                                <option value="">Unassigned</option>
                                @foreach ($reps as $rep)
                                    <option value="{{ $rep->id }}" @selected((int) $lead->assigned_to === (int) $rep->id)>{{ $rep->fullName() }}</option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    <td class="px-5 py-3">
                        <form method="POST" action="{{ route('admin.leads.updateStatus', $lead) }}" class="flex items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <select name="status" onchange="this.form.submit()"
                                    class="text-xs font-semibold px-2 py-1 rounded-full border-0 {{ $lead->statusBadgeClasses() }}">
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}" @selected($lead->status === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    @if ($tab === 'converted')
                        <td class="px-5 py-3">
                            @if ($lead->hasCustomerAccount($customerEmailsLower))
                                <span class="text-xs font-semibold px-2 py-1 rounded-full bg-green-100 text-green-700">Has login</span>
                            @else
                                <span class="text-xs font-semibold px-2 py-1 rounded-full bg-gray-100 text-gray-500">No login yet</span>
                            @endif
                        </td>
                    @endif
                    <td class="px-5 py-3 whitespace-nowrap">{{ $lead->created_at->format('d M Y') }}</td>
                    <td class="px-5 py-3 text-right whitespace-nowrap">
                        <a href="{{ route('admin.leads.show', $lead) }}" class="text-primary font-semibold">View</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="px-5 py-6 text-center text-inkFaint">No leads here.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $leads->appends(['tab' => $tab])->links() }}</div>
@endsection
