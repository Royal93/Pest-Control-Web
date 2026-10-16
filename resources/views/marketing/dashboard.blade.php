@extends('marketing.layouts.app')

@section('title', 'Dashboard')

@section('content')
<h1 class="font-display uppercase text-2xl mb-6">My Dashboard</h1>

<div class="grid md:grid-cols-4 gap-4 mb-10">
    <div class="border-2 border-line rounded-xl p-5 bg-white">
        <div class="text-xs uppercase text-inkFaint mb-1">Total Leads Captured</div>
        <div class="text-2xl font-display">{{ $stats['total'] }}</div>
    </div>
    <div class="border-2 border-line rounded-xl p-5 bg-white">
        <div class="text-xs uppercase text-inkFaint mb-1">Captured This Week</div>
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
    <a href="{{ route('marketing.leads.create') }}" class="border-2 border-primary bg-primary text-white rounded-lg px-5 py-3 text-sm font-semibold uppercase">
        Capture a Lead
    </a>
    <a href="{{ route('marketing.leads.index') }}" class="border-2 border-primary rounded-lg px-5 py-3 text-sm font-semibold uppercase">
        View All My Leads
    </a>
</div>

<div class="border-2 border-line rounded-xl bg-white overflow-hidden">
    <div class="px-5 py-3 border-b border-line font-semibold text-sm">Recently Captured</div>
    <table class="w-full text-sm">
        <thead class="bg-bgAlt text-left uppercase text-xs text-inkFaint">
            <tr>
                <th class="px-5 py-3">Name</th>
                <th class="px-5 py-3">Phone</th>
                <th class="px-5 py-3">Pest</th>
                <th class="px-5 py-3">Status</th>
                <th class="px-5 py-3">Captured</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($recentLeads as $lead)
                <tr class="border-t border-line">
                    <td class="px-5 py-3 font-semibold">{{ $lead->name }}</td>
                    <td class="px-5 py-3">{{ $lead->phone }}</td>
                    <td class="px-5 py-3">{{ $lead->pest_type ?: '—' }}</td>
                    <td class="px-5 py-3">{{ $lead->statusLabel() }}</td>
                    <td class="px-5 py-3">{{ $lead->created_at->format('d M Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-5 py-6 text-center text-inkFaint">No leads captured yet. Click "Capture a Lead" to add your first one.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
