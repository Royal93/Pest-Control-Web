@extends('admin.layouts.app')

@section('title', 'Sales Team')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <h1 class="font-display uppercase text-2xl">Sales Team</h1>
    <a href="{{ route('admin.marketing.create') }}"
       class="border-2 border-primary bg-primary text-white uppercase text-sm font-semibold px-6 py-2 rounded-lg">
        Add Team Member
    </a>
</div>

<div class="border-2 border-line rounded-xl bg-white overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-bgAlt text-left uppercase text-xs text-inkFaint">
            <tr>
                <th class="px-5 py-3">Name</th>
                <th class="px-5 py-3">Email</th>
                <th class="px-5 py-3">Leads Assigned</th>
                <th class="px-5 py-3">Open</th>
                <th class="px-5 py-3">Converted</th>
                <th class="px-5 py-3">Paid Sales</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($reps as $rep)
                <tr class="border-t border-line">
                    <td class="px-5 py-3 font-semibold">{{ $rep->fullName() }}</td>
                    <td class="px-5 py-3">{{ $rep->email }}</td>
                    <td class="px-5 py-3">{{ $rep->leads_count }}</td>
                    <td class="px-5 py-3">{{ $rep->open_count }}</td>
                    <td class="px-5 py-3">{{ $rep->converted_count }}</td>
                    <td class="px-5 py-3">{{ $rep->sales_count }}</td>
                    <td class="px-5 py-3 text-right whitespace-nowrap">
                        <a href="{{ route('admin.leads.create', ['rep' => $rep->id]) }}" class="text-primary font-semibold">Give a Lead</a>
                        <a href="{{ route('admin.marketing.show', $rep) }}" class="text-primary font-semibold ml-3">View</a>
                        <form method="POST" action="{{ route('admin.marketing.destroy', $rep) }}" class="inline ml-3"
                              onsubmit="return confirm('Remove this team member? Their login will stop working. Their open leads become unassigned so you can give them to someone else.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 font-semibold">Remove</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-5 py-6 text-center text-inkFaint">No sales team members yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
