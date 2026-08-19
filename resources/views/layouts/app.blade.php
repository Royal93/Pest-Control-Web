<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SP Pest Control')</title>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Source+Sans+3:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-body text-ink bg-white">

    <div id="siteTopbar" class="border-b-2 border-primary sticky top-0 bg-white/95 z-50 transition-shadow duration-300">
        <div class="max-w-6xl mx-auto px-7 py-5 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="SP Pest Control" class="w-16 h-16 rounded-full object-cover">
                <span class="font-display text-xl leading-none hidden sm:inline">SP <span class="text-primary">Pest Control</span></span>
            </a>
            <nav class="hidden md:flex gap-7 text-sm items-center">
                <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <button type="button" class="nav-link flex items-center gap-1 {{ request()->routeIs('services.residential') || request()->routeIs('services.pest') ? 'is-active' : '' }}">
                        Residential
                        <svg class="w-3 h-3 transition-transform duration-200" :class="open ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/></svg>
                    </button>
                    <div x-show="open" x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         class="absolute left-1/2 -translate-x-1/2 top-full pt-3 w-[420px] z-50">
                        <div class="bg-white border-2 border-primary rounded-2xl shadow-xl p-6 grid grid-cols-2 gap-6">
                            <div>
                                <p class="font-mono text-xs uppercase tracking-widest text-inkFaint mb-3">Popular Pests</p>
                                <div class="flex flex-col gap-1">
                                    <a href="{{ route('services.pest', 'cockroaches') }}" class="dropdown-item">Cockroaches</a>
                                    <a href="{{ route('services.pest', 'ants') }}" class="dropdown-item">Ants</a>
                                    <a href="{{ route('services.pest', 'rodents') }}" class="dropdown-item">Rodents</a>
                                    <a href="{{ route('services.pest', 'termites') }}" class="dropdown-item">Termites</a>
                                    <a href="{{ route('services.pest', 'bed-bugs') }}" class="dropdown-item">Bed Bugs</a>
                                </div>
                            </div>
                            <div>
                                <p class="font-mono text-xs uppercase tracking-widest text-inkFaint mb-3">Browse</p>
                                <div class="flex flex-col gap-1">
                                    <a href="{{ route('services.residential') }}" class="dropdown-item font-semibold">All Residential Pests</a>
                                    <a href="{{ route('plans.index') }}" class="dropdown-item">Protection Plans</a>
                                    <a href="{{ route('contact') }}" class="dropdown-item">Request Inspection</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <button type="button" class="nav-link flex items-center gap-1 {{ request()->routeIs('services.commercial') ? 'is-active' : '' }}">
                        Commercial
                        <svg class="w-3 h-3 transition-transform duration-200" :class="open ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/></svg>
                    </button>
                    <div x-show="open" x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         class="absolute left-1/2 -translate-x-1/2 top-full pt-3 w-[320px] z-50">
                        <div class="bg-white border-2 border-primary rounded-2xl shadow-xl p-6">
                            <p class="font-mono text-xs uppercase tracking-widest text-inkFaint mb-3">Industries</p>
                            <div class="flex flex-col gap-1">
                                <a href="{{ route('services.commercial') }}#multi-family-housing" class="dropdown-item">Multi-Family Housing</a>
                                <a href="{{ route('services.commercial') }}#retail-businesses" class="dropdown-item">Retail Businesses</a>
                                <a href="{{ route('services.commercial') }}#restaurants-food-services" class="dropdown-item">Restaurants &amp; Food Services</a>
                                <a href="{{ route('services.commercial') }}#schools-educational-facilities" class="dropdown-item">Schools &amp; Educational Facilities</a>
                                <a href="{{ route('services.commercial') }}" class="dropdown-item font-semibold mt-1">All Commercial Services</a>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="{{ route('plans.index') }}" class="nav-link {{ request()->routeIs('plans.index') ? 'is-active' : '' }}">Protection Plans</a>
                <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'is-active' : '' }}">About</a>
                <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'is-active' : '' }}">Contact</a>
                @auth
                    <a href="{{ route('portal.index') }}" class="nav-link {{ request()->routeIs('portal.index') ? 'is-active' : '' }}">My Account</a>
                @else
                    <a href="{{ route('login') }}" class="nav-link {{ request()->routeIs('login') ? 'is-active' : '' }}">Log In</a>
                @endauth
            </nav>
            <a href="{{ route('contact') }}" class="bg-primary text-white text-sm uppercase tracking-wide font-semibold px-5 py-3">
                Request Inspection
            </a>
        </div>
    </div>

    <main>
        @yield('content')
    </main>

    <footer class="border-t-2 border-primary mt-20 py-10 bg-secondary text-white">
        <div class="max-w-6xl mx-auto px-7 flex items-center justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="SP Pest Control" class="w-11 h-11 rounded-full object-cover">
                <span class="font-mono text-xs uppercase tracking-widest text-white/70">SP Pest Control</span>
            </div>
            <span class="font-mono text-xs text-white/50">&copy; {{ date('Y') }}</span>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
