@extends('marketing.layouts.app')

@section('title', 'Open Leads')

@section('content')
<h1 class="font-display uppercase text-2xl mb-2">Open Leads</h1>
<p class="text-sm text-inkFaint mb-6 max-w-3xl">
    These leads are not assigned to anyone yet. Claim the ones you want to work. Admin approves one claim per lead,
    and the approved rep gets the lead, with the customer's contact details, in My Leads.
</p>

<div class="flex gap-2 mb-5 border-b-2 border-line">
    <a href="{{ route('marketing.open.index') }}"
       class="px-4 py-2 text-sm font-semibold border-b-2 -mb-0.5 {{ $tab === 'open' ? 'border-primary text-primary' : 'border-transparent text-inkFaint hover:text-ink' }}">
        Open Leads <span class="text-xs">({{ $openLeads->total() }})</span>
    </a>
    <a href="{{ route('marketing.open.index', ['tab' => 'claims']) }}"
       class="px-4 py-2 text-sm font-semibold border-b-2 -mb-0.5 {{ $tab === 'claims' ? 'border-primary text-primary' : 'border-transparent text-inkFaint hover:text-ink' }}">
        My Claims
        @if ($pendingCount > 0)
            <span class="ml-1 text-xs font-semibold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700">{{ $pendingCount }} waiting</span>
        @endif
    </a>
</div>

@if ($tab === 'open')
    <form method="GET" class="mb-5 flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, suburb or pest..."
               class="border-2 border-line rounded-lg px-3 py-2 text-sm w-full max-w-xs">
        <button type="submit" class="border-2 border-line rounded-lg px-4 py-2 text-sm font-semibold">Search</button>
    </form>

    <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-4">
        @forelse ($openLeads as $lead)
            @php($myClaim = $lead->claims->first())
            <div class="border-2 {{ $myClaim?->isPending() ? 'border-amber-400' : 'border-line' }} rounded-xl bg-white p-5 flex flex-col">
                <div class="flex items-start justify-between gap-2">
                    <h2 class="font-semibold text-base">{{ $lead->name }}</h2>
                    <span class="text-xs text-inkFaint whitespace-nowrap">{{ $lead->created_at->format('d M Y') }}</span>
                </div>

                <dl class="grid grid-cols-2 gap-3 text-sm mt-3">
                    <div><dt class="text-xs uppercase text-inkFaint">Area</dt><dd>{{ collect([$lead->suburb, $lead->province])->filter()->implode(', ') ?: 'Not given' }}</dd></div>
                    <div><dt class="text-xs uppercase text-inkFaint">Property</dt><dd>{{ $lead->property_type ? ucfirst($lead->property_type) : 'Not given' }}</dd></div>
                    <div><dt class="text-xs uppercase text-inkFaint">Pest problem</dt><dd>{{ $lead->pest_type ?: 'Not given' }}</dd></div>
                    <div><dt class="text-xs uppercase text-inkFaint">Service needed</dt><dd>{{ $lead->service_interest ?: 'Not given' }}</dd></div>
                </dl>

                <p class="text-xs text-inkFaint mt-3">
                    Contact details are shown once admin approves your claim.
                    @if ($lead->pending_claims_count > 0)
                        <br>{{ $lead->pending_claims_count }} {{ $lead->pending_claims_count === 1 ? 'claim' : 'claims' }} so far.
                    @endif
                </p>

                <div class="mt-auto pt-4">
                    @if ($myClaim?->isPending())
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $myClaim->statusBadgeClasses() }}">You claimed this. Waiting for admin.</span>
                            <form method="POST" action="{{ route('marketing.open.withdraw', $lead) }}" onsubmit="return confirm('Withdraw your claim on this lead?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-600 font-semibold">Withdraw</button>
                            </form>
                        </div>
                    @else
                        <details class="group">
                            <summary class="list-none cursor-pointer inline-block border-2 border-primary bg-primary text-white uppercase text-xs font-semibold px-4 py-2 rounded-lg">
                                Claim this lead
                            </summary>
                            <form method="POST" action="{{ route('marketing.open.claim', $lead) }}" class="mt-3 flex flex-col gap-2">
                                @csrf
                                <label class="text-xs uppercase font-semibold">Note for admin (optional)</label>
                                <textarea name="note" rows="2" maxlength="500" placeholder="e.g. I work in this area, or I know this client"
                                          class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm"></textarea>
                                <button type="submit" class="self-start border-2 border-primary bg-primary text-white uppercase text-xs font-semibold px-4 py-2 rounded-lg">
                                    Send claim
                                </button>
                            </form>
                        </details>
                    @endif
                </div>
            </div>
        @empty
            <div class="md:col-span-2 xl:col-span-3 border-2 border-line rounded-xl bg-white px-5 py-8 text-center text-sm text-inkFaint">
                There are no unassigned leads right now. You'll get a notification when admin gives you a lead.
            </div>
        @endforelse
    </div>

    <div class="mt-4">{{ $openLeads->links() }}</div>
@else
    <div class="border-2 border-line rounded-xl bg-white overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-bgAlt text-left uppercase text-xs text-inkFaint">
                <tr>
                    <th class="px-5 py-3">Lead</th>
                    <th class="px-5 py-3">Area</th>
                    <th class="px-5 py-3">Pest</th>
                    <th class="px-5 py-3">Claimed</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($myClaims as $claim)
                    <tr class="border-t border-line">
                        <td class="px-5 py-3 font-semibold">{{ $claim->lead?->name ?? 'Deleted lead' }}</td>
                        <td class="px-5 py-3">{{ $claim->lead?->suburb ?: 'Not given' }}</td>
                        <td class="px-5 py-3">{{ $claim->lead?->pest_type ?: 'Not given' }}</td>
                        <td class="px-5 py-3 whitespace-nowrap">{{ $claim->created_at->format('d M Y') }}</td>
                        <td class="px-5 py-3">
                            <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $claim->statusBadgeClasses() }}">{{ $claim->statusLabel() }}</span>
                        </td>
                        <td class="px-5 py-3 text-right whitespace-nowrap">
                            @if ($claim->status === 'approved' && $claim->lead && (int) $claim->lead->assigned_to === (int) auth()->id())
                                <a href="{{ route('marketing.leads.edit', $claim->lead) }}" class="text-primary font-semibold">Open lead</a>
                            @elseif ($claim->isPending() && $claim->lead)
                                <form method="POST" action="{{ route('marketing.open.withdraw', $claim->lead) }}" onsubmit="return confirm('Withdraw your claim on this lead?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 font-semibold">Withdraw</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-6 text-center text-inkFaint">You haven't claimed any leads yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $myClaims->links() }}</div>
@endif
@endsection
