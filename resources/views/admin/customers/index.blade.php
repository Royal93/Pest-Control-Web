@extends('admin.layouts.app')

@section('title', 'Customers')

@section('content')
<h1 class="font-display uppercase text-2xl mb-6">Customers</h1>

<form method="GET" class="mb-5">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email…"
           class="border-2 border-line rounded-lg px-3 py-2 text-sm w-full max-w-xs">
</form>

<div class="border-2 border-line rounded-xl bg-white overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-bgAlt text-left uppercase text-xs text-inkFaint">
            <tr>
                <th class="px-5 py-3">Name</th>
                <th class="px-5 py-3">Email</th>
                <th class="px-5 py-3">Joined</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($customers as $customer)
                <tr class="border-t border-line">
                    <td class="px-5 py-3 font-semibold">{{ $customer->name }}</td>
                    <td class="px-5 py-3">{{ $customer->email }}</td>
                    <td class="px-5 py-3">{{ $customer->created_at->format('d M Y') }}</td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('admin.customers.show', $customer) }}" class="text-primary font-semibold">View</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-5 py-6 text-center text-inkFaint">No customers yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $customers->links() }}</div>
@endsection
