@extends('admin.layouts.app')

@section('title', 'Admins')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="font-display uppercase text-2xl">Admins</h1>
    <a href="{{ route('admin.admins.create') }}" class="border-2 border-primary bg-primary text-white uppercase text-sm font-semibold px-5 py-2 rounded-lg">
        + Add Admin
    </a>
</div>

@if (session('error'))
    <div class="mb-5 border-2 border-red-300 bg-red-50 text-red-700 px-4 py-3 rounded-lg text-sm">
        {{ session('error') }}
    </div>
@endif

<div class="border-2 border-line rounded-xl bg-white overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-bgAlt text-left uppercase text-xs text-inkFaint">
            <tr>
                <th class="px-5 py-3">Name</th>
                <th class="px-5 py-3">Email</th>
                <th class="px-5 py-3">Added</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($admins as $admin)
                <tr class="border-t border-line">
                    <td class="px-5 py-3 font-semibold">
                        {{ $admin->name }}
                        @if ($admin->id === auth('admin')->id())
                            <span class="text-xs text-inkFaint">(you)</span>
                        @endif
                    </td>
                    <td class="px-5 py-3">{{ $admin->email }}</td>
                    <td class="px-5 py-3">{{ $admin->created_at->format('d M Y') }}</td>
                    <td class="px-5 py-3 text-right">
                        <form method="POST" action="{{ route('admin.admins.destroy', $admin) }}"
                              onsubmit="return confirm('Remove {{ $admin->name }} as an admin?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 font-semibold">Remove</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
