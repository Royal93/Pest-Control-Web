@extends('marketing.layouts.app')

@section('title', 'Capture a Sale')

@section('content')
<h1 class="font-display uppercase text-2xl mb-1">Capture a Sale</h1>
<p class="text-sm text-inkFaint mb-6">
    For <a href="{{ route('marketing.leads.edit', $lead) }}" class="text-primary font-semibold">{{ $lead->name }}</a>.
    Details from the lead are filled in already. On submit, the personalised service agreement is emailed to the customer to accept online.
</p>

@include('sales._form', [
    'action' => route('marketing.sales.store'),
    'cancelUrl' => route('marketing.leads.edit', $lead),
])
@endsection
