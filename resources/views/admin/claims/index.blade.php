@extends('admin.layouts.app')

@section('title', 'Lead Claims')

@section('content')
<h1 class="font-display uppercase text-2xl mb-2">Lead Claims</h1>
<p class="text-sm text-inkFaint mb-6 max-w-3xl">
    Sales reps can claim any unassigned lead on SP SALES. Approve one claim per lead: the lead goes to that rep and the other claims are declined.
    Reps are notified either way.
</p>

<h2 class="font-display uppercase text-sm mb-3">Waiting for a decision</h2>

<div class="flex flex-col gap-4 mb-10">
    @forelse ($pending as $leadId => $claims)
        @php($lead = $claims->first()->lead)
        <div class="border-2 border-amber-400 rounded-xl bg-white p-5">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                <div>
                    <a href="{{ route('admin.leads.show', $lead) }}" class="font-semibold text-base hover:text-primary">{{ $lead->name }}</a>
                    <span class="text-xs text-inkFaint ml-2">{{ collect([$lead->suburb, $lead->pest_type])->filter()->implode(' · ') }}</span>
                </div>
                @if ($lead->assigned_to)
                    <span class="text-xs font-semibold px-2 py-1 rounded-full bg-gray-100 text-gray-700">Already assigned</span>
                @endif
            </div>

            <div class="flex flex-col divide-y divide-line">
                @foreach ($claims as $claim)
                    <div class="py-3 flex flex-wrap items-start justify-between gap-3 text-sm">
                        <div class="min-w-0">
                            <div class="font-semibold">{{ $claim->rep?->fullName() ?? 'Removed rep' }}</div>
                            <div class="text-xs text-inkFaint">Claimed {{ $claim->created_at->format('d M Y, H:i') }} ({{ $claim->created_at->diffForHumans() }})</div>
                            @if ($claim->note)
                                <p class="mt-2 bg-bgAlt rounded-lg px-3 py-2">{{ $claim->note }}</p>
                            @endif
                        </div>
                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('admin.claims.approve', $claim) }}"
                                  onsubmit="return confirm('Give {{ addslashes($lead->name) }} to {{ addslashes($claim->rep?->fullName()) }}? Other claims on this lead will be declined.');">
                                @csrf
                                <button type="submit" class="border-2 border-primary bg-primary text-white uppercase text-xs font-semibold px-4 py-2 rounded-lg">Approve</button>
                            </form>
                            <form method="POST" action="{{ route('admin.claims.decline', $claim) }}">
                                @csrf
                                <button type="submit" class="border-2 border-line uppercase text-xs font-semibold px-4 py-2 rounded-lg">Decline</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="border-2 border-line rounded-xl bg-white px-5 py-6 text-sm text-inkFaint">No claims waiting.</div>
    @endforelse
</div>

<h2 class="font-display uppercase text-sm mb-3">Recently decided</h2>
<div class="border-2 border-line rounded-xl bg-white overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-bgAlt text-left uppercase text-xs text-inkFaint">
            <tr>
                <th class="px-5 py-3">Lead</th>
                <th class="px-5 py-3">Sales rep</th>
                <th class="px-5 py-3">Decision</th>
                <th class="px-5 py-3">By</th>
                <th class="px-5 py-3">Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($recent as $claim)
                <tr class="border-t border-line">
                    <td class="px-5 py-3 font-semibold">
                        @if ($claim->lead)
                            <a href="{{ route('admin.leads.show', $claim->lead) }}" class="hover:text-primary">{{ $claim->lead->name }}</a>
                        @else
                            Deleted lead
                        @endif
                    </td>
                    <td class="px-5 py-3">{{ $claim->rep?->fullName() ?? 'Removed rep' }}</td>
                    <td class="px-5 py-3"><span class="text-xs font-semibold px-2 py-1 rounded-full {{ $claim->statusBadgeClasses() }}">{{ $claim->statusLabel() }}</span></td>
                    <td class="px-5 py-3">{{ $claim->decidedBy?->name ?? 'Admin' }}</td>
                    <td class="px-5 py-3 whitespace-nowrap">{{ $claim->decided_at?->format('d M Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-5 py-6 text-center text-inkFaint">Nothing decided yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
