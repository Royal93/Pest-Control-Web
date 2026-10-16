@extends('admin.layouts.app')

@section('title', 'Leads')

@section('content')
<h1 class="font-display uppercase text-2xl mb-6">Leads</h1>

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
    <select name="source" class="border-2 border-line rounded-lg px-3 py-2 text-sm bg-white" onchange="this.form.submit()">
        <option value="">All sources</option>
        <option value="contact_form" @selected(request('source') === 'contact_form')>Contact form</option>
        <option value="chatbot" @selected(request('source') === 'chatbot')>Chatbot</option>
        <option value="field_rep" @selected(request('source') === 'field_rep')>Field rep</option>
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

<div class="border-2 border-line rounded-xl bg-white overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-bgAlt text-left uppercase text-xs text-inkFaint">
            <tr>
                <th class="px-5 py-3">Name</th>
                <th class="px-5 py-3">Phone</th>
                <th class="px-5 py-3">Source</th>
                <th class="px-5 py-3">Captured By</th>
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
                    <td class="px-5 py-3">{{ $lead->phone ?: '—' }}</td>
                    <td class="px-5 py-3">{{ ucfirst(str_replace('_', ' ', $lead->source)) }}</td>
                    <td class="px-5 py-3">{{ $lead->capturedBy?->name ? $lead->capturedBy->name.' '.$lead->capturedBy->surname : '—' }}</td>
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
                    <td class="px-5 py-3">{{ $lead->created_at->format('d M Y') }}</td>
                    <td class="px-5 py-3 text-right">
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
