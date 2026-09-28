@extends('admin.layouts.app')

@section('title', 'Invoices')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <h1 class="font-display uppercase text-2xl">Invoices</h1>
    <a href="{{ route('admin.invoices.create') }}"
       class="border-2 border-primary bg-primary text-white uppercase text-sm font-semibold px-6 py-2 rounded-lg">
        Upload Invoice
    </a>
</div>

<form method="GET" class="mb-5">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by customer or invoice number..."
           class="border-2 border-line rounded-lg px-3 py-2 text-sm w-full max-w-xs">
</form>

<div class="border-2 border-line rounded-xl bg-white overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-bgAlt text-left uppercase text-xs text-inkFaint">
            <tr>
                <th class="px-5 py-3">Customer</th>
                <th class="px-5 py-3">Invoice</th>
                <th class="px-5 py-3">Date</th>
                <th class="px-5 py-3">Amount</th>
                <th class="px-5 py-3">Status</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($invoices as $invoice)
                <tr class="border-t border-line">
                    <td class="px-5 py-3 font-semibold">
                        <a href="{{ route('admin.customers.show', $invoice->user_id) }}" class="hover:text-primary">
                            {{ trim(($invoice->user->name ?? 'Removed customer').' '.($invoice->user->surname ?? '')) }}
                        </a>
                    </td>
                    <td class="px-5 py-3">
                        {{ $invoice->displayNumber() }}
                        @if ($invoice->description)
                            <span class="block text-xs text-inkFaint">{{ $invoice->description }}</span>
                        @endif
                    </td>
                    <td class="px-5 py-3">{{ $invoice->issued_at?->format('d M Y') }}</td>
                    <td class="px-5 py-3">R{{ number_format($invoice->amount, 2) }}</td>
                    <td class="px-5 py-3 {{ $invoice->status === 'paid' ? 'text-accentDark' : 'text-primary' }} font-semibold">
                        {{ ucfirst($invoice->status) }}
                    </td>
                    <td class="px-5 py-3 text-right whitespace-nowrap">
                        <a href="{{ route('admin.invoices.download', $invoice) }}" class="text-primary font-semibold">Download</a>
                        <form method="POST" action="{{ route('admin.invoices.destroy', $invoice) }}" class="inline ml-3"
                              onsubmit="return confirm('Remove this invoice? The customer will no longer see it.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 font-semibold">Remove</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-5 py-6 text-center text-inkFaint">No invoices uploaded yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $invoices->links() }}</div>
@endsection
