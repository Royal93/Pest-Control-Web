@extends('admin.layouts.app')

@section('title', 'Add Admin')

@section('content')
<h1 class="font-display uppercase text-2xl mb-6">Add Admin</h1>

<form method="POST" action="{{ route('admin.admins.store') }}" class="max-w-md border-2 border-line rounded-xl bg-white p-6 flex flex-col gap-5">
    @csrf

    <div>
        <label class="block text-xs uppercase font-semibold mb-1">Name</label>
        <input type="text" name="name" value="{{ old('name') }}" required
               class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm">
        @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-xs uppercase font-semibold mb-1">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required
               class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm">
        @error('email') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-xs uppercase font-semibold mb-1">Password</label>
        <input type="password" name="password" required
               class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm">
        @error('password') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-xs uppercase font-semibold mb-1">Confirm Password</label>
        <input type="password" name="password_confirmation" required
               class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm">
    </div>

    <div class="flex gap-3 mt-2">
        <button type="submit" class="border-2 border-primary bg-primary text-white uppercase text-sm font-semibold px-6 py-2 rounded-lg">
            Add Admin
        </button>
        <a href="{{ route('admin.admins.index') }}" class="border-2 border-line uppercase text-sm font-semibold px-6 py-2 rounded-lg">
            Cancel
        </a>
    </div>
</form>
@endsection
