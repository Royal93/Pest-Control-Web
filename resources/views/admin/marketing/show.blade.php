@extends('admin.layouts.app')

@section('title', $rep->name)

@section('content')
<h1 class="font-display uppercase text-2xl mb-1">{{ $rep->name }} {{ $rep->surname }}</h1>
<p class="text-sm text-inkFaint mb-6">{{ $rep->email }}</p>

<div class="border-2 border-line rounded-xl bg-white overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-bgAlt text-left uppercase text-xs text-inkFaint">
            <tr>
                <th class="px-5 py-3">Name</th>
                <th class="px-5 py-3">Phone</th>
                <th class="px-5 py-3">Suburb</th>
                <th class="px-5 py-3">Pest</th>
                <th class="px-5 py-3">Status</th>
                <th class="px-5 py-3">Captured</th>
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
                    <td class="px-5 py-3">{{ $lead->created_at->format('d M Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-5 py-6 text-center text-inkFaint">No leads captured yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $leads->links() }}</div>

<a href="{{ route('admin.marketing.index') }}" class="inline-block mt-6 text-sm font-semibold text-primary">&larr; Back to Marketing Team</a>
@endsection
