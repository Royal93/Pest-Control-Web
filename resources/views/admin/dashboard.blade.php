@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<h1 class="font-display uppercase text-2xl mb-6">Dashboard</h1>

<div class="grid md:grid-cols-4 gap-4 mb-6">
    <div class="border-2 border-line rounded-xl p-5 bg-white">
        <div class="text-xs uppercase text-inkFaint mb-1">Active Plans</div>
        <div class="text-2xl font-display">{{ $stats['plans_count'] }}</div>
    </div>
    <a href="{{ route('admin.customers.index') }}" class="border-2 border-line rounded-xl p-5 bg-white hover:bg-bgAlt">
        <div class="text-xs uppercase text-inkFaint mb-1">Customers</div>
        <div class="text-2xl font-display">{{ $stats['customers_count'] ?? '—' }}</div>
    </a>
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
    <a href="{{ route('admin.marketing.index') }}" class="border-2 border-line rounded-xl p-5 bg-white hover:bg-bgAlt">
        <div class="text-xs uppercase text-inkFaint mb-1">Sales Team</div>
        <div class="text-2xl font-display">{{ $stats['sales_team_count'] ?? '—' }}</div>
    </a>
    <a href="{{ route('admin.leads.index') }}" class="border-2 border-line rounded-xl p-5 bg-white hover:bg-bgAlt">
        <div class="text-xs uppercase text-inkFaint mb-1">Leads This Month</div>
        <div class="text-2xl font-display">{{ $stats['leads_this_month'] ?? '—' }}</div>
    </a>
    <div class="border-2 border-line rounded-xl p-5 bg-white">
        <div class="text-xs uppercase text-inkFaint mb-1">Assigned Leads</div>
        <div class="text-2xl font-display">{{ $stats['assigned_leads'] ?? '—' }}</div>
        @if (($stats['unassigned_leads'] ?? 0) > 0)
            <a href="{{ route('admin.leads.index', ['rep' => 'unassigned']) }}" class="inline-block text-xs font-semibold text-primary mt-1 hover:underline">
                {{ $stats['unassigned_leads'] }} waiting to be assigned
            </a>
        @endif
    </div>
</div>

<div class="flex flex-wrap gap-4 mb-10">
    <a href="{{ route('admin.leads.create') }}" class="border-2 border-primary bg-primary text-white rounded-lg px-5 py-3 text-sm font-semibold uppercase">
        Capture Lead
    </a>
    <a href="{{ route('admin.sales.create') }}" class="border-2 border-primary rounded-lg px-5 py-3 text-sm font-semibold uppercase">
        Capture a Sale
        @if (($stats['sales_awaiting'] ?? 0) > 0)
            <span class="ml-1 text-xs bg-primary text-white rounded-full px-2 py-0.5">{{ $stats['sales_awaiting'] }} open</span>
        @endif
    </a>
    <a href="{{ route('admin.plans.index') }}" class="border-2 border-primary rounded-lg px-5 py-3 text-sm font-semibold uppercase">
        Manage Plans &amp; Pricing
    </a>
    <a href="{{ route('admin.customers.index') }}" class="border-2 border-primary rounded-lg px-5 py-3 text-sm font-semibold uppercase">
        Manage Customers
    </a>
    <a href="{{ route('admin.marketing.index') }}" class="border-2 border-primary rounded-lg px-5 py-3 text-sm font-semibold uppercase">
        Sales Team
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
                <th class="px-5 py-3">Assigned To</th>
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
                    <td class="px-5 py-3">{{ $lead->sourceLabel() }}</td>
                    <td class="px-5 py-3">
                        @if ($lead->assignedTo)
                            {{ $lead->assignedToName() }}
                        @else
                            <a href="{{ route('admin.leads.show', $lead) }}" class="text-primary font-semibold">Assign</a>
                        @endif
                    </td>
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
