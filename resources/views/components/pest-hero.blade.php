@props(['scheme' => 'a', 'eyebrow' => null, 'headingPlain' => '', 'headingAccent' => null, 'compact' => true])

<header {{ $attributes->merge(['class' => 'hero-animated hero-scheme-'.$scheme.' border-b-2 border-primary']) }}>
    {{-- 14 floating pest silhouettes, 10 distinct shapes --}}
    <svg class="floating-pest f-1"  viewBox="0 0 100 100"><g><ellipse cx="30" cy="50" rx="10" ry="7"/><ellipse cx="48" cy="50" rx="12" ry="8"/><ellipse cx="68" cy="50" rx="9" ry="7"/><rect x="60" y="28" width="3" height="20" transform="rotate(20 61 38)"/><rect x="74" y="28" width="3" height="20" transform="rotate(-20 75 38)"/></g></svg>
    <svg class="floating-pest f-2"  viewBox="0 0 100 100"><g><ellipse cx="50" cy="55" rx="26" ry="16"/><ellipse cx="50" cy="34" rx="10" ry="8"/><rect x="16" y="10" width="3" height="18" transform="rotate(25 17 19)"/><rect x="78" y="10" width="3" height="18" transform="rotate(-25 79 19)"/></g></svg>
    <svg class="floating-pest f-3"  viewBox="0 0 100 100"><g><circle cx="50" cy="50" r="14"/><rect x="10" y="49" width="26" height="3" transform="rotate(15 23 50)"/><rect x="10" y="59" width="26" height="3" transform="rotate(-15 23 60)"/><rect x="64" y="49" width="26" height="3" transform="rotate(-15 77 50)"/><rect x="64" y="59" width="26" height="3" transform="rotate(15 77 60)"/><rect x="14" y="34" width="26" height="3" transform="rotate(35 27 35)"/><rect x="60" y="34" width="26" height="3" transform="rotate(-35 73 35)"/><rect x="14" y="70" width="26" height="3" transform="rotate(-35 27 71)"/><rect x="60" y="70" width="26" height="3" transform="rotate(35 73 71)"/></g></svg>
    <svg class="floating-pest f-4"  viewBox="0 0 100 100"><g><ellipse cx="45" cy="55" rx="24" ry="15"/><circle cx="74" cy="48" r="9"/><circle cx="83" cy="42" r="3"/><path d="M22 55 Q4 40 10 20" fill="none" stroke="#fff" stroke-width="3"/></g></svg>
    <svg class="floating-pest f-5"  viewBox="0 0 100 100"><g><ellipse cx="50" cy="55" rx="16" ry="11"/><ellipse cx="30" cy="42" rx="16" ry="9" transform="rotate(-25 30 42)"/><ellipse cx="70" cy="42" rx="16" ry="9" transform="rotate(25 70 42)"/><rect x="46" y="20" width="2.5" height="14" transform="rotate(15 47 27)"/><rect x="52" y="20" width="2.5" height="14" transform="rotate(-15 53 27)"/></g></svg>
    <svg class="floating-pest f-6"  viewBox="0 0 100 100"><g><ellipse cx="30" cy="50" rx="9" ry="6"/><ellipse cx="46" cy="50" rx="11" ry="7"/><ellipse cx="64" cy="50" rx="8" ry="6"/><rect x="56" y="30" width="3" height="18" transform="rotate(20 57 39)"/><rect x="68" y="30" width="3" height="18" transform="rotate(-20 69 39)"/></g></svg>
    <svg class="floating-pest f-7"  viewBox="0 0 100 100"><g><circle cx="50" cy="50" r="11"/><rect x="16" y="49" width="24" height="2.5" transform="rotate(18 27 50)"/><rect x="16" y="59" width="24" height="2.5" transform="rotate(-18 27 60)"/><rect x="60" y="49" width="24" height="2.5" transform="rotate(-18 71 50)"/><rect x="60" y="59" width="24" height="2.5" transform="rotate(18 71 60)"/></g></svg>
    <svg class="floating-pest f-8"  viewBox="0 0 100 100"><g><ellipse cx="45" cy="55" rx="22" ry="14"/><circle cx="70" cy="46" r="8"/><path d="M25 55 Q10 42 14 24" fill="none" stroke="#fff" stroke-width="3"/></g></svg>
    <svg class="floating-pest f-9"  viewBox="0 0 100 100"><g><circle cx="50" cy="52" r="9"/><rect x="20" y="51" width="20" height="2.5" transform="rotate(18 30 52)"/><rect x="20" y="59" width="20" height="2.5" transform="rotate(-18 30 60)"/><rect x="60" y="51" width="20" height="2.5" transform="rotate(-18 70 52)"/><rect x="60" y="59" width="20" height="2.5" transform="rotate(18 70 60)"/><rect x="30" y="36" width="20" height="2.5" transform="rotate(40 40 37)"/><rect x="50" y="36" width="20" height="2.5" transform="rotate(-40 60 37)"/></g></svg>
    <svg class="floating-pest f-10" viewBox="0 0 100 100"><g><ellipse cx="50" cy="50" rx="10" ry="24"/><ellipse cx="50" cy="30" rx="9" ry="8"/><rect x="42" y="12" width="2.5" height="14" transform="rotate(20 43 19)"/><rect x="52" y="12" width="2.5" height="14" transform="rotate(-20 53 19)"/></g></svg>
    <svg class="floating-pest f-11" viewBox="0 0 100 100"><g><ellipse cx="45" cy="55" rx="24" ry="15"/><circle cx="74" cy="48" r="9"/><circle cx="83" cy="42" r="3"/><path d="M22 55 Q4 40 10 20" fill="none" stroke="#fff" stroke-width="3"/></g></svg>
    <svg class="floating-pest f-12" viewBox="0 0 100 100"><g><ellipse cx="30" cy="50" rx="10" ry="7"/><ellipse cx="48" cy="50" rx="12" ry="8"/><ellipse cx="68" cy="50" rx="9" ry="7"/><rect x="60" y="28" width="3" height="20" transform="rotate(20 61 38)"/><rect x="74" y="28" width="3" height="20" transform="rotate(-20 75 38)"/></g></svg>
    <svg class="floating-pest f-13" viewBox="0 0 100 100"><g><circle cx="50" cy="50" r="14"/><rect x="10" y="49" width="26" height="3" transform="rotate(15 23 50)"/><rect x="10" y="59" width="26" height="3" transform="rotate(-15 23 60)"/><rect x="64" y="49" width="26" height="3" transform="rotate(-15 77 50)"/><rect x="64" y="59" width="26" height="3" transform="rotate(15 77 60)"/><rect x="14" y="34" width="26" height="3" transform="rotate(35 27 35)"/><rect x="60" y="34" width="26" height="3" transform="rotate(-35 73 35)"/></g></svg>
    <svg class="floating-pest f-14" viewBox="0 0 100 100"><g><ellipse cx="50" cy="55" rx="16" ry="11"/><ellipse cx="30" cy="42" rx="16" ry="9" transform="rotate(-25 30 42)"/><ellipse cx="70" cy="42" rx="16" ry="9" transform="rotate(25 70 42)"/><rect x="46" y="20" width="2.5" height="14" transform="rotate(15 47 27)"/><rect x="52" y="20" width="2.5" height="14" transform="rotate(-15 53 27)"/></g></svg>

    <div class="max-w-3xl mx-auto px-7 {{ $compact ? 'pt-14 pb-16' : 'pt-20 pb-24' }} text-center md:text-left relative">
        @if ($eyebrow)
            <p class="font-mono text-xs tracking-widest uppercase text-white/80 mb-4">{{ $eyebrow }}</p>
        @endif
        <h1 class="font-display uppercase {{ $compact ? 'text-3xl md:text-4xl' : 'text-4xl md:text-5xl' }} leading-tight mb-6 text-white">
            {{ $headingPlain }}
            @if ($headingAccent)
                <span class="text-accent">{{ $headingAccent }}</span>
            @endif
        </h1>

        {{ $slot }}
    </div>

    <div class="hero-curve">
        <svg viewBox="0 0 500 40" preserveAspectRatio="none"><path d="M0,40 C150,0 350,0 500,40 L500,40 L0,40 Z" fill="#ffffff"/></svg>
    </div>
</header>
