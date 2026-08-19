@extends('layouts.app')

@section('title', 'My Account — SP Pest Control')

@section('content')
<x-pest-hero scheme="g" eyebrow="My Account" heading-plain="Welcome back," :heading-accent="auth()->user()->name">
</x-pest-hero>

<section class="max-w-5xl mx-auto px-7 py-16 space-y-10">
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
