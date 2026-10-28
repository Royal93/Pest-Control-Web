@extends('admin.layouts.app')

@section('title', 'Capture a Sale')

@section('content')
<h1 class="font-display uppercase text-2xl mb-1">Capture a Sale</h1>
<p class="text-sm text-inkFaint mb-6">
    @if ($lead)
        For lead <a href="{{ route('admin.leads.show', $lead) }}" class="text-primary font-semibold">{{ $lead->name }}</a>.
        Details from the lead are filled in already.
    @else
        A new sale not linked to a lead.
    @endif
    On submit, the personalised service agreement is generated and emailed to the customer to accept online.
</p>

@include('sales._form', [
    'action' => route('admin.sales.store'),
    'cancelUrl' => $lead ? route('admin.leads.show', $lead) : route('admin.sales.index'),
])
@endsection
