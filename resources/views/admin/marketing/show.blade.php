@extends('admin.layouts.app')

@section('title', $rep->fullName())

@section('content')
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div>
        <h1 class="font-display uppercase text-2xl mb-1">{{ $rep->fullName() }}</h1>
        <p class="text-sm text-inkFaint">{{ $rep->email }}@if ($rep->phone) | {{ $rep->phone }}@endif</p>
    </div>
    <a href="{{ route('admin.leads.create', ['rep' => $rep->id]) }}"
       class="border-2 border-primary bg-primary text-white uppercase text-sm font-semibold px-6 py-2 rounded-lg">
        Capture Lead for {{ $rep->name }}
    </a>
</div>

<h2 class="font-display uppercase text-sm mb-3">Assigned Leads</h2>
<div class="border-2 border-line rounded-xl bg-white overflow-x-auto mb-4">
    <table class="w-full text-sm">
        <thead class="bg-bgAlt text-left uppercase text-xs text-inkFaint">
            <tr>
                <th class="px-5 py-3">Name</th>
                <th class="px-5 py-3">Phone</th>
                <th class="px-5 py-3">Suburb</th>
                <th class="px-5 py-3">Pest</th>
                <th class="px-5 py-3">Status</th>
                <th class="px-5 py-3">Assigned</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($leads as $lead)
                <tr class="border-t border-line">
                    <td class="px-5 py-3 font-semibold">
                        <a href="{{ route('admin.leads.show', $lead) }}" class="hover:text-primary">{{ $lead->name }}</a>
                    </td>
                    <td class="px-5 py-3">{{ $lead->phone }}</td>
                    <td class="px-5 py-3">{{ $lead->suburb ?: '—' }}</td>
                    <td class="px-5 py-3">{{ $lead->pest_type ?: '—' }}</td>
                    <td class="px-5 py-3">
                        <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $lead->statusBadgeClasses() }}">{{ $lead->statusLabel() }}</span>
                    </td>
                    <td class="px-5 py-3">{{ ($lead->assigned_at ?? $lead->created_at)->format('d M Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-5 py-6 text-center text-inkFaint">No leads assigned yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mb-10">{{ $leads->links() }}</div>

<h2 class="font-display uppercase text-sm mb-3">Recent Sales</h2>
<div class="border-2 border-line rounded-xl bg-white overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-bgAlt text-left uppercase text-xs text-inkFaint">
            <tr>
                <th class="px-5 py-3">Agreement</th>
                <th class="px-5 py-3">Customer</th>
                <th class="px-5 py-3">Service</th>
                <th class="px-5 py-3">Status</th>
                <th class="px-5 py-3">Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($sales as $sale)
                <tr class="border-t border-line">
                    <td class="px-5 py-3 font-semibold"><a href="{{ route('admin.sales.show', $sale) }}" class="hover:text-primary">{{ $sale->reference() }}</a></td>
                    <td class="px-5 py-3">{{ $sale->customer_name }}</td>
                    <td class="px-5 py-3">{{ $sale->serviceLabel() }}</td>
                    <td class="px-5 py-3"><span class="text-xs font-semibold px-2 py-1 rounded-full {{ $sale->statusBadgeClasses() }}">{{ $sale->statusLabel() }}</span></td>
                    <td class="px-5 py-3">{{ $sale->created_at->format('d M Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-5 py-6 text-center text-inkFaint">No sales captured yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<a href="{{ route('admin.marketing.index') }}" class="inline-block mt-6 text-sm font-semibold text-primary">&larr; Back to Sales Team</a>
@endsection
