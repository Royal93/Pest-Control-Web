@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<h1 class="font-display uppercase text-2xl mb-6">Dashboard</h1>

<div class="grid md:grid-cols-4 gap-4 mb-10">
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

<div class="flex gap-4">
    <a href="{{ route('admin.plans.index') }}" class="border-2 border-primary rounded-lg px-5 py-3 text-sm font-semibold uppercase">
        Manage Plans &amp; Pricing
    </a>
    <a href="{{ route('admin.customers.index') }}" class="border-2 border-primary rounded-lg px-5 py-3 text-sm font-semibold uppercase">
        Manage Customers
    </a>
</div>
@endsection
