@extends('admin.layouts.app')

@section('title', $slide->exists ? 'Edit Slide' : 'Add Slide')

@section('content')
<h1 class="font-display uppercase text-2xl mb-6">{{ $slide->exists ? 'Edit Slide' : 'Add Slide' }}</h1>

@if ($errors->any())
    <div class="mb-6 max-w-3xl border-2 border-red-500 bg-red-50 text-ink px-4 py-3 rounded-lg text-sm">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Live preview of the wording, in the homepage style --}}
<div class="max-w-3xl rounded-xl p-6 mb-6 border-2 border-line" style="background:linear-gradient(120deg,#fff8f1 0%,#fcebdc 50%,#f6d9c3 100%)">
    <div class="text-xs uppercase text-inkFaint mb-3">Preview</div>
    <span id="pvTag" class="inline-block text-[11px] uppercase tracking-widest px-3 py-1 rounded-full border border-primary text-primary mb-3"></span>
    <div class="font-display uppercase text-3xl md:text-4xl font-bold leading-tight text-ink">
        <span id="pvBefore"></span> <span id="pvAccent" class="text-primary"></span> <span id="pvAfter"></span>
    </div>
    <p id="pvText" class="text-base mt-3 max-w-md text-[#3e454c]"></p>
</div>

<form method="POST" enctype="multipart/form-data"
      action="{{ $slide->exists ? route('admin.slides.update', $slide) : route('admin.slides.store') }}"
      class="max-w-3xl border-2 border-line rounded-xl bg-white p-6 flex flex-col gap-5">
    @csrf
    @if ($slide->exists) @method('PUT') @endif

    <div>
        <label class="block text-xs uppercase font-semibold mb-1">Small label above the heading</label>
        <input type="text" name="tag" maxlength="40" value="{{ old('tag', $slide->tag) }}" data-preview="pvTag"
               placeholder="e.g. Qualified technicians" class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm">
    </div>

    <div>
        <label class="block text-xs uppercase font-semibold mb-1">Heading</label>
        <p class="text-xs text-inkFaint mb-2">The heading is in three parts so you can choose which word(s) show in orange. Leave any part empty if you don't need it.</p>
        <div class="grid md:grid-cols-3 gap-3">
            <div>
                <span class="block text-xs text-inkFaint mb-1">Start (dark)</span>
                <input type="text" name="heading_before" maxlength="60" value="{{ old('heading_before', $slide->heading_before) }}" data-preview="pvBefore"
                       placeholder="Real experts." class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <span class="block text-xs text-primary font-semibold mb-1">Orange word(s)</span>
                <input type="text" name="heading_accent" maxlength="40" value="{{ old('heading_accent', $slide->heading_accent) }}" data-preview="pvAccent"
                       placeholder="Real" class="w-full border-2 border-primary rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <span class="block text-xs text-inkFaint mb-1">End (dark)</span>
                <input type="text" name="heading_after" maxlength="60" value="{{ old('heading_after', $slide->heading_after) }}" data-preview="pvAfter"
                       placeholder="results." class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm">
            </div>
        </div>
    </div>

    <div>
        <label class="block text-xs uppercase font-semibold mb-1">Sentence under the heading</label>
        <textarea name="text" rows="2" maxlength="180" data-preview="pvText"
                  class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm">{{ old('text', $slide->text) }}</textarea>
        <p class="text-xs text-inkFaint mt-1">Keep it to one short sentence (180 characters at most) so it fits beside the photo.</p>
    </div>

    <div>
        <label class="block text-xs uppercase font-semibold mb-1">Photo</label>
        @if ($slide->exists && $slide->hasImage())
            <img src="{{ asset($slide->image_path) }}" alt="" class="w-64 h-36 object-cover rounded-lg mb-2">
            <p class="text-xs text-inkFaint mb-2">Leave this empty to keep the current photo.</p>
        @elseif ($slide->exists)
            <p class="text-xs text-red-700 mb-2">This slide has no photo, so it is skipped on the homepage. Upload one to show it.</p>
        @endif
        <input type="file" name="photo" accept=".jpg,.jpeg,.png,.webp" @required(! $slide->exists)
               class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm bg-white">
        <p class="text-xs text-inkFaint mt-1">JPG, PNG or WebP, up to 5 MB. A wide landscape photo with the person on the right works best.</p>
    </div>

    <div>
        <label class="block text-xs uppercase font-semibold mb-1">Photo description (for screen readers and Google)</label>
        <input type="text" name="image_alt" maxlength="150" value="{{ old('image_alt', $slide->image_alt) }}"
               placeholder="e.g. SP Pest Control technician speaking with a customer" class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm">
    </div>

    <div class="flex flex-wrap gap-3 mt-2">
        <button type="submit" class="border-2 border-primary bg-primary text-white uppercase text-sm font-semibold px-6 py-2 rounded-lg">
            {{ $slide->exists ? 'Save Slide' : 'Add Slide' }}
        </button>
        <a href="{{ route('admin.slides.index') }}" class="border-2 border-line uppercase text-sm font-semibold px-6 py-2 rounded-lg">Cancel</a>
    </div>
</form>

@if ($slide->exists)
    <form method="POST" action="{{ route('admin.slides.destroy', $slide) }}" class="max-w-3xl mt-4 text-right"
          onsubmit="return confirm('Delete this slide for good? You can also just hide it instead.');">
        @csrf @method('DELETE')
        <button type="submit" class="text-sm text-red-600 font-semibold">Delete this slide</button>
    </form>
@endif

<script>
    document.querySelectorAll('[data-preview]').forEach(function (input) {
        var target = document.getElementById(input.dataset.preview);
        var update = function () {
            target.textContent = input.value;
            if (target.id === 'pvTag') target.style.display = input.value.trim() ? '' : 'none';
        };
        input.addEventListener('input', update);
        update();
    });
</script>
@endsection
