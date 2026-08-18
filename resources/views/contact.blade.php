@extends('layouts.app')

@section('title', 'Contact — SP Pest Control')

@section('content')
<section class="max-w-3xl mx-auto px-7 py-16">
    <p class="font-mono text-xs tracking-widest uppercase text-primary mb-3">Contact</p>
    <h1 class="font-display uppercase text-3xl mb-8">Request an inspection</h1>

    @if (session('success'))
        <div class="border-2 border-primary p-4 mb-6 text-sm">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('contact.store') }}" class="space-y-5">
        @csrf
        <div class="grid sm:grid-cols-2 gap-5">
            <label class="flex flex-col gap-2 text-sm text-inkMuted">
                Full Name
                <input type="text" name="name" required class="border-2 border-line p-3">
            </label>
            <label class="flex flex-col gap-2 text-sm text-inkMuted">
                Phone Number
                <input type="tel" name="phone" required class="border-2 border-line p-3">
            </label>
        </div>
        <label class="flex flex-col gap-2 text-sm text-inkMuted">
            Email
            <input type="email" name="email" required class="border-2 border-line p-3">
        </label>
        <label class="flex flex-col gap-2 text-sm text-inkMuted">
            Details
            <textarea name="message" rows="4" class="border-2 border-line p-3"></textarea>
        </label>
        <button type="submit" class="bg-primary text-white uppercase text-sm font-semibold px-6 py-3">
            Send Request
        </button>
    </form>
</section>
@endsection
