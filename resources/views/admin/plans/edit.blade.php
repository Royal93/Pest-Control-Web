@extends('admin.layouts.app')

@section('title', 'Edit ' . $plan->name)

@section('content')
<h1 class="font-display uppercase text-2xl mb-6">Edit {{ $plan->name }}</h1>

<form method="POST" action="{{ route('admin.plans.update', $plan) }}" class="max-w-2xl border-2 border-line rounded-xl bg-white p-6 flex flex-col gap-5">
    @csrf
    @method('PUT')

    <div>
        <label class="block text-xs uppercase font-semibold mb-1">Plan Name</label>
        <input type="text" name="name" value="{{ old('name', $plan->name) }}" required
               class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm">
    </div>

    <div>
        <label class="block text-xs uppercase font-semibold mb-1">Description</label>
        <textarea name="description" rows="2"
                  class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm">{{ old('description', $plan->description) }}</textarea>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-xs uppercase font-semibold mb-1">Price (R)</label>
            <input type="number" step="0.01" name="price" value="{{ old('price', $plan->price) }}" required
                   class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs uppercase font-semibold mb-1">Billing Cycle</label>
            <input type="text" name="billing_cycle" value="{{ old('billing_cycle', $plan->billing_cycle) }}"
                   placeholder="monthly"
                   class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm">
        </div>
    </div>

    <div>
        <label class="block text-xs uppercase font-semibold mb-1">
            Extra Content (JSON)
            <span class="normal-case text-inkFaint font-normal">— the "why", "includes", "terms" etc. shown on the public plan page. Edit carefully, must stay valid JSON.</span>
        </label>
        <textarea name="meta_json" rows="14" spellcheck="false"
                  class="w-full border-2 border-line rounded-lg px-3 py-2 text-xs font-mono">{{ old('meta_json', json_encode($plan->meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) }}</textarea>
        @error('meta_json') <p class="text-red-600 text-xs mt-1">{{ $message }} — check for a missing comma or quote.</p> @enderror
    </div>

    <div class="flex gap-3 mt-2">
        <button type="submit" class="border-2 border-primary bg-primary text-white uppercase text-sm font-semibold px-6 py-2 rounded-lg">
            Save Changes
        </button>
        <a href="{{ route('admin.plans.index') }}" class="border-2 border-line uppercase text-sm font-semibold px-6 py-2 rounded-lg">
            Cancel
        </a>
    </div>
</form>
@endsection
