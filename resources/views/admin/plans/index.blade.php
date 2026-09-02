@extends('admin.layouts.app')

@section('title', 'Plans & Pricing')

@section('content')
<h1 class="font-display uppercase text-2xl mb-6">Plans &amp; Pricing</h1>

<div class="border-2 border-line rounded-xl bg-white overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-bgAlt text-left uppercase text-xs text-inkFaint">
            <tr>
                <th class="px-5 py-3">Plan</th>
                <th class="px-5 py-3">Price</th>
                <th class="px-5 py-3">Billing Cycle</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($plans as $plan)
                <tr class="border-t border-line">
                    <td class="px-5 py-3 font-semibold">{{ $plan->name }}</td>
                    <td class="px-5 py-3">R{{ number_format($plan->price, 2) }}</td>
                    <td class="px-5 py-3 capitalize">{{ $plan->billing_cycle }}</td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('admin.plans.edit', $plan) }}" class="text-primary font-semibold">Edit</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
