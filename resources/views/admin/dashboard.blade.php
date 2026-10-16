@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<h1 class="font-display uppercase text-2xl mb-6">Dashboard</h1>

<div class="grid md:grid-cols-4 gap-4 mb-6">
    <div class="border-2 border-line rounded-xl p-5 bg-white">
        <div class="text-xs uppercase text-inkFaint mb-1">Active Plans</div>
        <div class="text-2xl font-display">{{ $stats['plans_count'] }}</div>
    </div>
    <div class="border-2 border-line rounded-xl p-5 bg-white">
        <div class="text-xs uppercase text-inkFaint mb-1">Customers</div>
        <div class="text-2xl font-display">{{ $stats['customers_count'] ?? '—' }}</div>
    </div>
    <div class="border-2 border-line rounded-xl p-5 bg-white">
        <div class="text-xs uppercase text-inkFaint mb-1">Paid This Month</div>
        <div class="text-2xl font-display">
            {{ $stats['payments_this_month'] !== null ? 'R' . number_format($stats['payments_this_month'], 2) : '—' }}
        </div>
    </div>
    <div class="border-2 border-line rounded-xl p-5 bg-white">
        <div class="text-xs uppercase text-inkFaint mb-1">Pending Payments</div>
        <div class="text-2xl font-display">{{ $stats['pending_payments'] ?? '—' }}</div>
    </div>
</div>

<div class="grid md:grid-cols-3 gap-4 mb-10">
    <div class="border-2 border-line rounded-xl p-5 bg-white">
        <div class="text-xs uppercase text-inkFaint mb-1">Marketing Team</div>
        <div class="text-2xl font-display">{{ $stats['marketing_reps_count'] ?? '—' }}</div>
    </div>
    <div class="border-2 border-line rounded-xl p-5 bg-white">
        <div class="text-xs uppercase text-inkFaint mb-1">Leads This Month</div>
        <div class="text-2xl font-display">{{ $stats['leads_this_month'] ?? '—' }}</div>
    </div>
    <div class="border-2 border-line rounded-xl p-5 bg-white">
        <div class="text-xs uppercase text-inkFaint mb-1">From Field Reps</div>
        <div class="text-2xl font-display">{{ $stats['leads_from_reps_this_month'] ?? '—' }}</div>
    </div>
</div>

<div class="flex gap-4 mb-10">
    <a href="{{ route('admin.plans.index') }}" class="border-2 border-primary rounded-lg px-5 py-3 text-sm font-semibold uppercase">
        Manage Plans &amp; Pricing
    </a>
    <a href="{{ route('admin.customers.index') }}" class="border-2 border-primary rounded-lg px-5 py-3 text-sm font-semibold uppercase">
        Manage Customers
    </a>
    <a href="{{ route('admin.marketing.index') }}" class="border-2 border-primary rounded-lg px-5 py-3 text-sm font-semibold uppercase">
        Marketing Team
    </a>
</div>

<div class="border-2 border-line rounded-xl bg-white overflow-hidden">
    <div class="px-5 py-3 border-b border-line font-semibold text-sm flex items-center justify-between">
        <span>Recent Leads</span>
        <a href="{{ route('admin.leads.index') }}" class="text-primary text-xs font-semibold uppercase">View all</a>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-bgAlt text-left uppercase text-xs text-inkFaint">
            <tr>
                <th class="px-5 py-3">Name</th>
                <th class="px-5 py-3">Source</th>
                <th class="px-5 py-3">Captured By</th>
                <th class="px-5 py-3">Status</th>
                <th class="px-5 py-3">Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($recentLeads as $lead)
                <tr class="border-t border-line">
                    <td class="px-5 py-3 font-semibold">
                        <a href="{{ route('admin.leads.show', $lead) }}" class="hover:text-primary">{{ $lead->name }}</a>
                    </td>
                    <td class="px-5 py-3">{{ ucfirst(str_replace('_', ' ', $lead->source)) }}</td>
                    <td class="px-5 py-3">{{ $lead->capturedBy?->name ? $lead->capturedBy->name.' '.$lead->capturedBy->surname : '—' }}</td>
                    <td class="px-5 py-3">{{ $lead->statusLabel() }}</td>
                    <td class="px-5 py-3">{{ $lead->created_at->format('d M Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-5 py-6 text-center text-inkFaint">No leads yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
