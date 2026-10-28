@extends('admin.layouts.app')

@section('title', 'Sales & Agreements')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <h1 class="font-display uppercase text-2xl">Sales &amp; Agreements</h1>
    <a href="{{ route('admin.sales.create') }}"
       class="border-2 border-primary bg-primary text-white uppercase text-sm font-semibold px-6 py-2 rounded-lg">
        Capture a Sale
    </a>
</div>

<div class="flex flex-wrap gap-2 mb-5 border-b-2 border-line">
    <a href="{{ route('admin.sales.index') }}"
       class="px-4 py-2 text-sm font-semibold border-b-2 -mb-0.5 {{ ! request('status') ? 'border-primary text-primary' : 'border-transparent text-inkFaint hover:text-ink' }}">
        All <span class="text-xs">({{ $counts->sum() }})</span>
    </a>
    @foreach (\App\Models\Sale::STATUSES as $key => $label)
        <a href="{{ route('admin.sales.index', ['status' => $key]) }}"
           class="px-4 py-2 text-sm font-semibold border-b-2 -mb-0.5 {{ request('status') === $key ? 'border-primary text-primary' : 'border-transparent text-inkFaint hover:text-ink' }}">
            {{ $label }} <span class="text-xs">({{ $counts[$key] ?? 0 }})</span>
        </a>
    @endforeach
</div>

<form method="GET" class="mb-5 flex flex-wrap gap-3">
    <input type="hidden" name="status" value="{{ request('status') }}">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search customer, email or phone…"
           class="border-2 border-line rounded-lg px-3 py-2 text-sm w-full max-w-xs">
    <select name="rep" class="border-2 border-line rounded-lg px-3 py-2 text-sm bg-white" onchange="this.form.submit()">
        <option value="">All sales reps</option>
        @foreach ($reps as $rep)
            <option value="{{ $rep->id }}" @selected((string) request('rep') === (string) $rep->id)>{{ $rep->fullName() }}</option>
        @endforeach
    </select>
    <button type="submit" class="border-2 border-line rounded-lg px-4 py-2 text-sm font-semibold">Filter</button>
</form>

<div class="border-2 border-line rounded-xl bg-white overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-bgAlt text-left uppercase text-xs text-inkFaint">
            <tr>
                <th class="px-5 py-3">Agreement</th>
                <th class="px-5 py-3">Customer</th>
                <th class="px-5 py-3">Service</th>
                <th class="px-5 py-3">Monthly</th>
                <th class="px-5 py-3">Sales Rep</th>
                <th class="px-5 py-3">Status</th>
                <th class="px-5 py-3">Date</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($sales as $sale)
                <tr class="border-t border-line">
                    <td class="px-5 py-3 font-semibold whitespace-nowrap">{{ $sale->reference() }}</td>
                    <td class="px-5 py-3">
                        <div class="font-semibold">{{ $sale->customer_name }}</div>
                        <div class="text-xs text-inkFaint">{{ $sale->email }}</div>
                    </td>
                    <td class="px-5 py-3">{{ $sale->serviceLabel() }}</td>
                    <td class="px-5 py-3 whitespace-nowrap">R{{ number_format((float) $sale->monthly_subscription, 2) }}</td>
                    <td class="px-5 py-3">{{ $sale->salesRepName() ?: 'Office' }}</td>
                    <td class="px-5 py-3"><span class="text-xs font-semibold px-2 py-1 rounded-full whitespace-nowrap {{ $sale->statusBadgeClasses() }}">{{ $sale->statusLabel() }}</span></td>
                    <td class="px-5 py-3 whitespace-nowrap">{{ $sale->created_at->format('d M Y') }}</td>
                    <td class="px-5 py-3 text-right"><a href="{{ route('admin.sales.show', $sale) }}" class="text-primary font-semibold">View</a></td>
                </tr>
            @empty
                <tr><td colspan="8" class="px-5 py-6 text-center text-inkFaint">No sales here yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $sales->links() }}</div>
@endsection
