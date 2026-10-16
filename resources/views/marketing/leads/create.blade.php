@extends('marketing.layouts.app')

@section('title', 'Capture Lead')

@section('content')
<h1 class="font-display uppercase text-2xl mb-6">Capture a Lead</h1>

@if ($errors->any())
    <div class="mb-6 max-w-2xl border-2 border-red-500 bg-red-50 text-ink px-4 py-3 rounded-lg text-sm">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('marketing.leads.store') }}"
      class="max-w-2xl border-2 border-line rounded-xl bg-white p-6 flex flex-col gap-5">
    @csrf

    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block text-xs uppercase font-semibold mb-1">Full name</label>
            <input type="text" name="name" value="{{ old('name') }}" required maxlength="255"
                   class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs uppercase font-semibold mb-1">Phone</label>
            <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="e.g. 082 123 4567"
                   class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm">
        </div>
    </div>

    <div>
        <label class="block text-xs uppercase font-semibold mb-1">Email (optional)</label>
        <input type="email" name="email" value="{{ old('email') }}" maxlength="255"
               class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm">
    </div>

    <div>
        <label class="block text-xs uppercase font-semibold mb-1">Address</label>
        <input type="text" name="address" value="{{ old('address') }}" maxlength="255"
               class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm">
    </div>

    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block text-xs uppercase font-semibold mb-1">Suburb</label>
            <input type="text" name="suburb" value="{{ old('suburb') }}" maxlength="120"
                   class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs uppercase font-semibold mb-1">Province</label>
            <select name="province" class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm bg-white">
                <option value="">Select…</option>
                @foreach (['Gauteng', 'KwaZulu-Natal', 'Western Cape', 'Eastern Cape', 'Free State', 'Limpopo', 'Mpumalanga', 'North West', 'Northern Cape'] as $province)
                    <option value="{{ $province }}" @selected(old('province') === $province)>{{ $province }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div>
        <label class="block text-xs uppercase font-semibold mb-1">Property type</label>
        <select name="property_type" class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm bg-white">
            <option value="">Select…</option>
            <option value="residential" @selected(old('property_type') === 'residential')>Residential</option>
            <option value="commercial" @selected(old('property_type') === 'commercial')>Commercial</option>
            <option value="industrial" @selected(old('property_type') === 'industrial')>Industrial</option>
            <option value="estate" @selected(old('property_type') === 'estate')>Estate / Complex</option>
        </select>
    </div>

    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block text-xs uppercase font-semibold mb-1">Pest problem</label>
            <input type="text" name="pest_type" value="{{ old('pest_type') }}" list="pestList" placeholder="e.g. Cockroaches"
                   class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm">
            <datalist id="pestList">
                @foreach ($pests as $pest)
                    <option value="{{ $pest }}"></option>
                @endforeach
            </datalist>
        </div>
        <div>
            <label class="block text-xs uppercase font-semibold mb-1">Interested in which plan (optional)</label>
            <input type="text" name="service_interest" value="{{ old('service_interest') }}" list="planList"
                   class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm">
            <datalist id="planList">
                @foreach ($plans as $plan)
                    <option value="{{ $plan }}"></option>
                @endforeach
            </datalist>
        </div>
    </div>

    <div>
        <label class="block text-xs uppercase font-semibold mb-1">How did they hear about us?</label>
        <select name="referral_channel" class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm bg-white">
            <option value="">Select…</option>
            <option value="referral" @selected(old('referral_channel') === 'referral')>Referral</option>
            <option value="saw_the_van" @selected(old('referral_channel') === 'saw_the_van')>Saw our van</option>
            <option value="flyer" @selected(old('referral_channel') === 'flyer')>Flyer / pamphlet</option>
            <option value="online_search" @selected(old('referral_channel') === 'online_search')>Online search</option>
            <option value="walk_in" @selected(old('referral_channel') === 'walk_in')>Walked up to them directly</option>
            <option value="other" @selected(old('referral_channel') === 'other')>Other</option>
        </select>
    </div>

    <div>
        <label class="block text-xs uppercase font-semibold mb-1">Notes from the conversation</label>
        <textarea name="notes" rows="3" maxlength="2000"
                  class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm">{{ old('notes') }}</textarea>
    </div>

    <div>
        <label class="block text-xs uppercase font-semibold mb-1">Next follow-up date (optional)</label>
        <input type="date" name="next_follow_up_date" value="{{ old('next_follow_up_date') }}"
               class="border-2 border-line rounded-lg px-3 py-2 text-sm">
    </div>

    <label class="flex items-start gap-2 text-sm">
        <input type="checkbox" name="consent" value="1" required class="mt-1" @checked(old('consent'))>
        <span>The customer agreed to be contacted by SP Pest Control about this enquiry.</span>
    </label>

    <div class="flex gap-3 mt-2">
        <button type="submit" class="border-2 border-primary bg-primary text-white uppercase text-sm font-semibold px-6 py-2 rounded-lg">
            Save Lead
        </button>
        <a href="{{ route('marketing.dashboard') }}" class="border-2 border-line uppercase text-sm font-semibold px-6 py-2 rounded-lg">
            Cancel
        </a>
    </div>
</form>
@endsection
