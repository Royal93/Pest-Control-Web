@extends('layouts.app')

@section('title', 'Set Your Password | SP Pest Control')

@section('content')
<section class="bg-bgAlt py-16">
    <div class="max-w-md mx-auto px-6">
        <h1 class="font-display uppercase text-3xl mb-1">Welcome, {{ $user->name }}</h1>
        <p class="text-inkFaint text-sm mb-6">Set a password for your SP Pest Control customer portal. You log in with {{ $user->email }}.</p>

        @if ($errors->any())
            <div class="mb-4 border-2 border-red-500 bg-red-50 px-4 py-3 rounded-lg text-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ $action }}" class="border-2 border-line rounded-xl bg-white p-6 flex flex-col gap-4">
            @csrf
            <div>
                <label for="password" class="block text-xs uppercase font-semibold mb-1">New password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                       class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label for="password_confirmation" class="block text-xs uppercase font-semibold mb-1">Confirm password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                       class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm">
            </div>
            <button type="submit" class="border-2 border-primary bg-primary text-white uppercase text-sm font-semibold px-6 py-3 rounded-lg">
                Set Password &amp; Log In
            </button>
        </form>
    </div>
</section>
@endsection
