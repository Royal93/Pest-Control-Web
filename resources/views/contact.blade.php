@extends('layouts.app')

@section('title', 'Contact - SP Pest Control')

@section('content')
<x-pest-hero scheme="f" eyebrow="Contact" heading-plain="Request an" heading-accent="inspection">
</x-pest-hero>

<section class="max-w-3xl mx-auto px-7 py-16">
    @if (session('success'))
        <div class="border-2 border-primary p-4 mb-6 text-sm">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="border-2 border-red-500 bg-red-50 p-4 mb-6 text-sm text-ink">
            Please check the details below:
        </div>
    @endif

    <form method="POST" action="{{ route('contact.store') }}" class="space-y-5">
        @csrf
        <div class="grid sm:grid-cols-2 gap-5">
            <label class="flex flex-col gap-2 text-sm text-inkMuted">
                Full Name
                <input type="text" name="name" value="{{ old('name') }}" required class="border-2 border-line p-3 @error('name') border-red-500 @enderror">
                @error('name') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </label>
            <label class="flex flex-col gap-2 text-sm text-inkMuted">
                Cell Number
                <input type="tel" name="phone" value="{{ old('phone') }}" required placeholder="e.g. 082 123 4567" class="border-2 border-line p-3 @error('phone') border-red-500 @enderror">
                @error('phone') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </label>
        </div>
        <label class="flex flex-col gap-2 text-sm text-inkMuted">
            Email
            <input type="email" name="email" value="{{ old('email') }}" required class="border-2 border-line p-3 @error('email') border-red-500 @enderror">
            @error('email') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </label>
        <label class="flex flex-col gap-2 text-sm text-inkMuted">
            Details
            <textarea name="message" rows="4" required placeholder="Tell us what's going on and where, so we can help." class="border-2 border-line p-3 @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
            @error('message') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </label>
        <button type="submit" class="bg-primary text-white uppercase text-sm font-semibold px-6 py-3">
            Send Request
        </button>
    </form>
</section>
@endsection
