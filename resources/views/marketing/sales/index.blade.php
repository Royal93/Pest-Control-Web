@extends('marketing.layouts.app')

@section('title', 'My Sales')

@section('content')
<h1 class="font-display uppercase text-2xl mb-6">My Sales</h1>

<form method="GET" class="mb-5 flex flex-wrap gap-3">
    <select name="status" class="border-2 border-line rounded-lg px-3 py-2 text-sm bg-white" onchange="this.form.submit()">
        <option value="">All</option>
        @foreach (\App\Models\Sale::STATUSES as $key => $label)
            <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
        @endforeach
    </select>
</form>

<div class="border-2 border-line rounded-xl bg-white overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-bgAlt text-left uppercase text-xs text-inkFaint">
            <tr>
                <th class="px-5 py-3">Agreement</th>
                <th class="px-5 py-3">Customer</th>
                <th class="px-5 py-3">Service</th>
                <th class="px-5 py-3">Monthly</th>
                <th class="px-5 py-3">Status</th>
                <th class="px-5 py-3">Date</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($sales as $sale)
                <tr class="border-t border-line">
                    <td class="px-5 py-3 font-semibold whitespace-nowrap">{{ $sale->reference() }}</td>
                    <td class="px-5 py-3">{{ $sale->customer_name }}</td>
                    <td class="px-5 py-3">{{ $sale->serviceLabel() }}</td>
                    <td class="px-5 py-3 whitespace-nowrap">R{{ number_format((float) $sale->monthly_subscription, 2) }}</td>
                    <td class="px-5 py-3"><span class="text-xs font-semibold px-2 py-1 rounded-full whitespace-nowrap {{ $sale->statusBadgeClasses() }}">{{ $sale->statusLabel() }}</span></td>
                    <td class="px-5 py-3 whitespace-nowrap">{{ $sale->created_at->format('d M Y') }}</td>
                    <td class="px-5 py-3 text-right"><a href="{{ route('marketing.sales.show', $sale) }}" class="text-primary font-semibold">View</a></td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-5 py-6 text-center text-inkFaint">No sales yet. Open one of your leads and choose "Capture a Sale".</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $sales->links() }}</div>
@endsection
