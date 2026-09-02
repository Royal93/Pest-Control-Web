@extends('layouts.app')

@section('title', 'Redirecting to secure payment — SP Pest Control')

@section('content')
<section class="max-w-md mx-auto px-7 py-24 text-center">
    <h1 class="font-display uppercase text-2xl mb-3">Taking you to secure payment&hellip;</h1>
    <p class="text-inkMuted text-sm mb-8">
        You're being redirected to Netcash to complete your payment securely.
        Please don't close this window.
    </p>

    {{-- Netcash requires a real browser form POST, target=_top, to a parent
         window — not an AJAX/API call. This form submits itself immediately. --}}
    <form id="netcash-form" method="POST" action="{{ $netcash['action_url'] }}" target="_top">
        <input type="hidden" name="M1" value="{{ $netcash['m1'] }}">
        <input type="hidden" name="M2" value="{{ $netcash['m2'] }}">
        <input type="hidden" name="p2" value="{{ $netcash['p2'] }}">
        <input type="hidden" name="p3" value="{{ $netcash['p3'] }}">
        <input type="hidden" name="p4" value="{{ $netcash['p4'] }}">
        <input type="hidden" name="Budget" value="{{ $netcash['Budget'] }}">

        @if (isset($netcash['m14']))
            <input type="hidden" name="m14" value="{{ $netcash['m14'] }}">
        @endif
        @if (isset($netcash['m16']))
            <input type="hidden" name="m16" value="{{ $netcash['m16'] }}">
            <input type="hidden" name="m17" value="{{ $netcash['m17'] }}">
            <input type="hidden" name="m18" value="{{ $netcash['m18'] }}">
            <input type="hidden" name="m19" value="{{ $netcash['m19'] }}">
            <input type="hidden" name="m20" value="{{ $netcash['m20'] }}">
        @endif

        <noscript>
            <button type="submit" class="border-2 border-primary px-6 py-3 uppercase text-sm font-semibold">
                Continue to Payment
            </button>
        </noscript>
    </form>
</section>

<script>
    document.getElementById('netcash-form').submit();
</script>
@endsection
