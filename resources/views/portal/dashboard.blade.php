@extends('layouts.app')

@section('title', 'My Account — SP Pest Control')

@section('content')
<section class="max-w-5xl mx-auto px-7 py-16 space-y-10">
    <div>
        <p class="font-mono text-xs tracking-widest uppercase text-primary mb-3">My Account</p>
        <h1 class="font-display uppercase text-3xl">Welcome back, {{ auth()->user()->name }}</h1>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        <div class="border-2 border-line p-6">
            @livewire('plan-card')
        </div>
        <div class="border-2 border-line p-6">
            @livewire('payment-method-form')
        </div>
    </div>

    <div class="border-2 border-line p-6">
        @livewire('service-history')
    </div>

    <div class="border-2 border-line p-6">
        @livewire('billing-history')
    </div>
</section>
@endsection
