@extends('admin.layouts.app')

@section('title', 'Marketing Team')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <h1 class="font-display uppercase text-2xl">Marketing Team</h1>
    <a href="{{ route('admin.marketing.create') }}"
       class="border-2 border-primary bg-primary text-white uppercase text-sm font-semibold px-6 py-2 rounded-lg">
        Add Team Member
    </a>
</div>

<div class="border-2 border-line rounded-xl bg-white overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-bgAlt text-left uppercase text-xs text-inkFaint">
            <tr>
                <th class="px-5 py-3">Name</th>
                <th class="px-5 py-3">Email</th>
                <th class="px-5 py-3">Leads Captured</th>
                <th class="px-5 py-3">Converted</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($reps as $rep)
                <tr class="border-t border-line">
                    <td class="px-5 py-3 font-semibold">{{ $rep->name }} {{ $rep->surname }}</td>
                    <td class="px-5 py-3">{{ $rep->email }}</td>
                    <td class="px-5 py-3">{{ $rep->leads_count }}</td>
                    <td class="px-5 py-3">{{ $rep->converted_count }}</td>
                    <td class="px-5 py-3 text-right whitespace-nowrap">
                        <a href="{{ route('admin.marketing.show', $rep) }}" class="text-primary font-semibold">View Leads</a>
                        <form method="POST" action="{{ route('admin.marketing.destroy', $rep) }}" class="inline ml-3"
                              onsubmit="return confirm('Remove this team member? Their login will stop working, but their captured leads stay on record.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 font-semibold">Remove</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-5 py-6 text-center text-inkFaint">No marketing team members yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
