@extends('layouts.app')

@section('title', 'Commercial Pest Control — SP Pest Control')

@section('content')
<section class="max-w-6xl mx-auto px-7 py-16">
    <p class="font-mono text-xs tracking-widest uppercase text-primary mb-3">Commercial</p>
    <h1 class="font-display uppercase text-3xl mb-4">Built around your industry</h1>
    <p class="text-inkMuted max-w-xl mb-12">
        In shared and public-facing spaces, one pest issue rarely stays contained. We build
        service plans around the layout, traffic and compliance needs of your business.
    </p>

    <div class="divide-y-2 divide-line border-y-2 border-line">
        @foreach ($industries as $i => $industry)
            <div class="grid md:grid-cols-[0.9fr_1.6fr] gap-6 py-8">
                <div>
                    <p class="font-mono text-inkFaint text-sm">0{{ $i + 1 }}</p>
                    <h2 class="font-display uppercase text-xl mt-2">{{ $industry->name }}</h2>
                </div>
                <div>
                    <p class="text-inkMuted mb-4">{{ $industry->description }}</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($industry->common_pests ?? [] as $pest)
                            <span class="font-mono text-xs uppercase text-primary border-2 border-line px-3 py-1">{{ $pest }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endsection
