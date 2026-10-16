@extends('admin.layouts.app')

@section('title', 'Leads')

@section('content')
<h1 class="font-display uppercase text-2xl mb-6">All Leads</h1>

<form method="GET" class="mb-5 flex flex-wrap gap-3">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, phone or email…"
           class="border-2 border-line rounded-lg px-3 py-2 text-sm w-full max-w-xs">
    <select name="source" class="border-2 border-line rounded-lg px-3 py-2 text-sm bg-white" onchange="this.form.submit()">
        <option value="">All sources</option>
        <option value="contact_form" @selected(request('source') === 'contact_form')>Contact form</option>
        <option value="chatbot" @selected(request('source') === 'chatbot')>Chatbot</option>
        <option value="field_rep" @selected(request('source') === 'field_rep')>Field rep</option>
    </select>
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
                <th class="px-5 py-3">Source</th>
                <th class="px-5 py-3">Captured By</th>
                <th class="px-5 py-3">Status</th>
                <th class="px-5 py-3">Date</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($leads as $lead)
                <tr class="border-t border-line">
                    <td class="px-5 py-3 font-semibold">{{ $lead->name }}</td>
                    <td class="px-5 py-3">{{ $lead->phone ?: '—' }}</td>
                    <td class="px-5 py-3">{{ ucfirst(str_replace('_', ' ', $lead->source)) }}</td>
                    <td class="px-5 py-3">{{ $lead->capturedBy?->name ? $lead->capturedBy->name.' '.$lead->capturedBy->surname : '—' }}</td>
                    <td class="px-5 py-3">{{ $lead->statusLabel() }}</td>
                    <td class="px-5 py-3">{{ $lead->created_at->format('d M Y') }}</td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('admin.leads.show', $lead) }}" class="text-primary font-semibold">View</a>
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
