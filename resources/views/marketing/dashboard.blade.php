@extends('marketing.layouts.app')

@section('title', 'Dashboard')

@section('content')
<h1 class="font-display uppercase text-2xl mb-6">My Dashboard</h1>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-10">
    <div class="border-2 border-line rounded-xl p-5 bg-white">
        <div class="text-xs uppercase text-inkFaint mb-1">Total Leads Assigned</div>
        <div class="text-2xl font-display">{{ $stats['total'] }}</div>
    </div>
    <div class="border-2 border-line rounded-xl p-5 bg-white">
        <div class="text-xs uppercase text-inkFaint mb-1">Assigned This Week</div>
        <div class="text-2xl font-display">{{ $stats['this_week'] }}</div>
    </div>
    <div class="border-2 border-line rounded-xl p-5 bg-white">
        <div class="text-xs uppercase text-inkFaint mb-1">Converted</div>
        <div class="text-2xl font-display">{{ $stats['converted'] }}</div>
    </div>
    <div class="border-2 border-line rounded-xl p-5 bg-white">
        <div class="text-xs uppercase text-inkFaint mb-1">Follow-ups Due</div>
        <div class="text-2xl font-display {{ $stats['follow_ups_due'] > 0 ? 'text-primary' : '' }}">{{ $stats['follow_ups_due'] }}</div>
    </div>
</div>

<div class="flex gap-4 mb-10">
    <a href="{{ route('marketing.leads.index') }}" class="border-2 border-primary rounded-lg px-5 py-3 text-sm font-semibold uppercase">
        View All My Leads
    </a>
</div>

<div class="border-2 border-line rounded-xl bg-white overflow-x-auto">
    <div class="px-5 py-3 border-b border-line font-semibold text-sm">Recently Assigned</div>
    <table class="w-full text-sm">
        <thead class="bg-bgAlt text-left uppercase text-xs text-inkFaint">
            <tr>
                <th class="px-5 py-3">Name</th>
                <th class="px-5 py-3">Phone</th>
                <th class="px-5 py-3">Pest</th>
                <th class="px-5 py-3">Status</th>
                <th class="px-5 py-3">Assigned</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($recentLeads as $lead)
                <tr class="border-t border-line">
                    <td class="px-5 py-3 font-semibold">{{ $lead->name }}</td>
                    <td class="px-5 py-3 whitespace-nowrap">{{ $lead->phone }}</td>
                    <td class="px-5 py-3">{{ $lead->pest_type ?: '—' }}</td>
                    <td class="px-5 py-3">{{ $lead->statusLabel() }}</td>
                    <td class="px-5 py-3 whitespace-nowrap">{{ ($lead->assigned_at ?? $lead->created_at)->format('d M Y') }}</td>
                    <td class="px-5 py-3 text-right"><a href="{{ route('marketing.leads.edit', $lead) }}" class="text-primary font-semibold">Open</a></td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-5 py-6 text-center text-inkFaint">No leads assigned to you yet. New leads from the office will appear here.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
