@extends('admin.layouts.app')

@section('title', 'Homepage Slides')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-3 mb-2">
    <h1 class="font-display uppercase text-2xl">Homepage Slides</h1>
    <div class="flex gap-2">
        <a href="{{ url('/') }}" target="_blank" class="border-2 border-line uppercase text-sm font-semibold px-5 py-2 rounded-lg">View Homepage</a>
        <a href="{{ route('admin.slides.create') }}" class="border-2 border-primary bg-primary text-white uppercase text-sm font-semibold px-5 py-2 rounded-lg">Add Slide</a>
    </div>
</div>
<p class="text-sm text-inkFaint mb-6 max-w-3xl">
    These are the rotating slides at the top of the homepage. Change the wording or photo of any slide, hide the ones you don't want for now,
    and use the arrows to change the order. Prices are changed under <a href="{{ route('admin.plans.index') }}" class="text-primary font-semibold">Plans &amp; Pricing</a>.
</p>

<div class="flex flex-col gap-4">
    @foreach ($slides as $i => $slide)
        <div class="border-2 {{ $slide->is_active ? 'border-line' : 'border-dashed border-line opacity-70' }} rounded-xl bg-white p-4 flex flex-col md:flex-row gap-4">
            <div class="md:w-56 flex-shrink-0">
                @if ($slide->hasImage())
                    <img src="{{ asset($slide->image_path) }}" alt="" class="w-full h-32 object-cover rounded-lg">
                @else
                    <div class="w-full h-32 rounded-lg bg-red-50 border-2 border-dashed border-red-300 flex items-center justify-center text-xs text-red-700 text-center px-3">
                        Photo missing. This slide is skipped on the homepage until you upload one.
                    </div>
                @endif
            </div>

            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <span class="text-xs text-inkFaint">Slide {{ $i + 1 }}</span>
                    @if ($slide->is_active && $slide->hasImage())
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-green-100 text-green-700">Showing</span>
                    @elseif ($slide->is_active)
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-red-100 text-red-700">Not showing: no photo</span>
                    @else
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">Hidden</span>
                    @endif
                </div>
                @if ($slide->tag)
                    <span class="inline-block text-[11px] uppercase tracking-widest px-3 py-1 rounded-full border border-primary text-primary mb-2">{{ $slide->tag }}</span>
                @endif
                <div class="font-display uppercase text-xl leading-tight">
                    {{ $slide->heading_before }} <span class="text-primary">{{ $slide->heading_accent }}</span> {{ $slide->heading_after }}
                </div>
                <p class="text-sm text-inkFaint mt-1">{{ $slide->text }}</p>
            </div>

            <div class="flex md:flex-col flex-wrap gap-2 md:items-end md:justify-between">
                <div class="flex gap-1">
                    <form method="POST" action="{{ route('admin.slides.move', $slide) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="direction" value="up">
                        <button type="submit" class="w-9 h-9 border-2 border-line rounded-lg disabled:opacity-30" @disabled($i === 0) aria-label="Move up">&uarr;</button>
                    </form>
                    <form method="POST" action="{{ route('admin.slides.move', $slide) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="direction" value="down">
                        <button type="submit" class="w-9 h-9 border-2 border-line rounded-lg disabled:opacity-30" @disabled($i === $slides->count() - 1) aria-label="Move down">&darr;</button>
                    </form>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.slides.edit', $slide) }}" class="border-2 border-primary bg-primary text-white uppercase text-xs font-semibold px-4 py-2 rounded-lg">Edit</a>
                    <form method="POST" action="{{ route('admin.slides.toggle', $slide) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="border-2 border-line uppercase text-xs font-semibold px-4 py-2 rounded-lg">{{ $slide->is_active ? 'Hide' : 'Show' }}</button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
