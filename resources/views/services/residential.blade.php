@extends('layouts.app')

@section('title', 'Residential Pest Control — SP Pest Control')

@section('content')
<section class="max-w-6xl mx-auto px-7 py-16">
    <p class="font-mono text-xs tracking-widest uppercase text-primary mb-3">Residential</p>
    <h1 class="font-display uppercase text-3xl mb-4">Pests treated at the source</h1>
    <p class="text-inkMuted max-w-xl mb-12">
        Every treatment starts with an inspection, not a guess. Below are the pests we handle
        most often in and around South African homes.
    </p>

    @if ($featured)
        <div class="border-2 border-primary mb-10 grid md:grid-cols-2">
            <div class="bg-bgAlt p-10 flex items-center justify-center">
                @if ($featured->photo_path)
                    <img src="{{ asset($featured->photo_path) }}" alt="{{ $featured->name }}" class="max-h-56 object-contain">
                @endif
            </div>
            <div class="p-8">
                <p class="font-mono text-xs uppercase tracking-widest text-signal mb-2">Highest call-out volume</p>
                <h2 class="font-display uppercase text-2xl mb-3">{{ $featured->name }} Control</h2>
                <p class="text-inkMuted mb-5">{{ $featured->description }}</p>
                <a href="{{ route('contact') }}" class="border-2 border-line px-5 py-3 text-sm uppercase font-semibold">
                    Request a {{ $featured->name }} Inspection
                </a>
            </div>
        </div>
    @endif

    <div class="flex flex-wrap border-t-2 border-l-2 border-primary">
        @foreach ($pests as $pest)
            <div class="w-1/2 sm:w-1/2 md:w-1/4 border-r-2 border-b-2 border-primary p-6 bg-white flex flex-col gap-3">
                <div class="w-full h-28 flex items-center justify-center bg-bgAlt">
                    @if ($pest->photo_path)
                        <img src="{{ asset($pest->photo_path) }}" alt="{{ $pest->name }}" class="w-full h-28 object-cover">
                    @endif
                </div>
                <h3 class="font-display uppercase text-sm">{{ $pest->name }}</h3>
                <p class="text-inkMuted text-sm">{{ $pest->description }}</p>
            </div>
        @endforeach
    </div>
</section>
@endsection
