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

    <footer class="border-t-2 border-primary mt-20 bg-secondary text-white">
        <div class="max-w-6xl mx-auto px-7 py-16">

            {{-- ============ TOP: COMPANY / SERVICES / QUICK LINKS ============ --}}
            <div class="grid md:grid-cols-3 gap-10 mb-16">

                {{-- Company + social --}}
                <div>
                    <h3 class="font-display uppercase text-lg">SP <span class="text-primary">Pest Control</span></h3>
                    <div class="w-10 h-1 bg-gradient-to-r from-primary to-accent mt-2 mb-4"></div>
                    <p class="text-white/70 text-sm max-w-xs">
                        Residential and commercial pest control built around prevention -
                        treat the source, seal the perimeter, keep it out.
                    </p>
                    <div class="flex flex-wrap gap-3 mt-6">
                        <a href="#" aria-label="Facebook" class="w-10 h-10 rounded-full bg-white/10 hover:bg-primary flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12a10 10 0 1 0-11.6 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.4h-1.3c-1.2 0-1.6.8-1.6 1.6V12h2.8l-.4 2.9h-2.4v7A10 10 0 0 0 22 12Z"/></svg>
                        </a>
                        <a href="#" aria-label="Instagram" class="w-10 h-10 rounded-full bg-white/10 hover:bg-primary flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2c2.7 0 3.1 0 4.1.1 1.1 0 1.8.2 2.5.5.7.3 1.2.6 1.8 1.2.6.6.9 1.1 1.2 1.8.3.7.4 1.4.5 2.5 0 1 .1 1.4.1 4.1s0 3.1-.1 4.1c0 1.1-.2 1.8-.5 2.5-.3.7-.6 1.2-1.2 1.8-.6.6-1.1.9-1.8 1.2-.7.3-1.4.4-2.5.5-1 0-1.4.1-4.1.1s-3.1 0-4.1-.1c-1.1 0-1.8-.2-2.5-.5-.7-.3-1.2-.6-1.8-1.2-.6-.6-.9-1.1-1.2-1.8-.3-.7-.4-1.4-.5-2.5 0-1-.1-1.4-.1-4.1s0-3.1.1-4.1c0-1.1.2-1.8.5-2.5.3-.7.6-1.2 1.2-1.8.6-.6 1.1-.9 1.8-1.2.7-.3 1.4-.4 2.5-.5C8.9 2 9.3 2 12 2Zm0 5a5 5 0 1 0 0 10 5 5 0 0 0 0-10Zm0 8.2a3.2 3.2 0 1 1 0-6.4 3.2 3.2 0 0 1 0 6.4Zm5.2-8.4a1.2 1.2 0 1 0 0-2.4 1.2 1.2 0 0 0 0 2.4Z"/></svg>
                        </a>
                        <a href="#" aria-label="LinkedIn" class="w-10 h-10 rounded-full bg-white/10 hover:bg-primary flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M6.94 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0ZM3.2 8.75h3.5V21H3.2V8.75Zm6.4 0h3.35v1.68h.05c.47-.88 1.6-1.8 3.3-1.8 3.53 0 4.18 2.32 4.18 5.34V21h-3.5v-5.5c0-1.3-.02-2.98-1.82-2.98-1.82 0-2.1 1.42-2.1 2.88V21H9.6V8.75Z"/></svg>
                        </a>
                        <a href="#" aria-label="X (Twitter)" class="w-10 h-10 rounded-full bg-white/10 hover:bg-primary flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M18.9 3H22l-7.4 8.5L23.3 21H16.6l-5.2-6.8L5.4 21H2.2l7.9-9-8-9h6.8l4.7 6.2L18.9 3Zm-1.2 16h1.7L7.3 5H5.5l12.2 14Z"/></svg>
                        </a>
                        <a href="#" aria-label="YouTube" class="w-10 h-10 rounded-full bg-white/10 hover:bg-primary flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M23 12s0-3.4-.4-5a3 3 0 0 0-2.1-2.1C18.9 4.5 12 4.5 12 4.5s-6.9 0-8.5.4A3 3 0 0 0 1.4 7C1 8.6 1 12 1 12s0 3.4.4 5a3 3 0 0 0 2.1 2.1c1.6.4 8.5.4 8.5.4s6.9 0 8.5-.4a3 3 0 0 0 2.1-2.1c.4-1.6.4-5 .4-5ZM9.8 15.5v-7l6 3.5-6 3.5Z"/></svg>
                        </a>
                        <a href="#" aria-label="WhatsApp" class="w-10 h-10 rounded-full bg-white/10 hover:bg-primary flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2Zm0 18.2a8.1 8.1 0 0 1-4.2-1.1l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1 1 12 20.2Zm4.5-6.1c-.2-.1-1.4-.7-1.7-.8-.2-.1-.4-.1-.6.1-.2.2-.6.8-.8 1-.1.2-.3.2-.5.1-.2-.1-1-.4-1.9-1.2-.7-.6-1.2-1.4-1.3-1.6-.1-.2 0-.4.1-.5.1-.1.2-.3.4-.4.1-.2.2-.3.2-.5.1-.2 0-.4 0-.5 0-.1-.6-1.5-.8-2-.2-.5-.4-.4-.6-.4h-.5c-.2 0-.5.1-.7.3-.2.2-1 1-1 2.3 0 1.4 1 2.7 1.1 2.9.1.2 2 3 4.8 4.3.7.3 1.2.5 1.6.6.7.2 1.3.2 1.8.1.5-.1 1.4-.6 1.6-1.1.2-.5.2-1 .1-1.1-.1-.1-.2-.2-.5-.3Z"/></svg>
                        </a>
                    </div>
                    <p class="text-white/40 text-xs mt-3">Social links coming soon</p>
                </div>

                {{-- Services --}}
                <div>
                    <h3 class="font-display uppercase text-lg">Our Services</h3>
                    <div class="w-10 h-1 bg-gradient-to-r from-primary to-accent mt-2 mb-4"></div>
                    <ul class="space-y-3 text-sm">
                        <li><a href="{{ route('services.residential') }}" class="text-white/70 hover:text-primary flex items-center gap-2"><span class="text-primary">&#9656;</span> Residential Pest Control</a></li>
                        <li><a href="{{ route('services.commercial') }}" class="text-white/70 hover:text-primary flex items-center gap-2"><span class="text-primary">&#9656;</span> Commercial Pest Control</a></li>
                        <li><a href="{{ route('plans.index') }}" class="text-white/70 hover:text-primary flex items-center gap-2"><span class="text-primary">&#9656;</span> Protection Plans</a></li>
                        <li><a href="{{ route('services.pest', 'cockroaches') }}" class="text-white/70 hover:text-primary flex items-center gap-2"><span class="text-primary">&#9656;</span> Cockroach Control</a></li>
                        <li><a href="{{ route('services.pest', 'termites') }}" class="text-white/70 hover:text-primary flex items-center gap-2"><span class="text-primary">&#9656;</span> Termite Control</a></li>
                        <li><a href="{{ route('services.pest', 'rodents') }}" class="text-white/70 hover:text-primary flex items-center gap-2"><span class="text-primary">&#9656;</span> Rodent Control</a></li>
                    </ul>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h3 class="font-display uppercase text-lg">Quick Links</h3>
                    <div class="w-10 h-1 bg-gradient-to-r from-primary to-accent mt-2 mb-4"></div>
                    <ul class="space-y-3 text-sm">
                        <li><a href="{{ route('home') }}" class="text-white/70 hover:text-primary flex items-center gap-2"><span class="text-primary">&#9656;</span> Home</a></li>
                        <li><a href="{{ route('about') }}" class="text-white/70 hover:text-primary flex items-center gap-2"><span class="text-primary">&#9656;</span> About Us</a></li>
                        <li><a href="{{ route('services.residential') }}" class="text-white/70 hover:text-primary flex items-center gap-2"><span class="text-primary">&#9656;</span> Residential</a></li>
                        <li><a href="{{ route('services.commercial') }}" class="text-white/70 hover:text-primary flex items-center gap-2"><span class="text-primary">&#9656;</span> Commercial</a></li>
                        <li><a href="{{ route('contact') }}" class="text-white/70 hover:text-primary flex items-center gap-2"><span class="text-primary">&#9656;</span> Contact Us</a></li>
                        <li><a href="{{ route('contact') }}" class="text-white/70 hover:text-primary flex items-center gap-2"><span class="text-primary">&#9656;</span> Request Inspection</a></li>
                    </ul>
                </div>
            </div>

            {{-- ============ GET IN TOUCH ============ --}}
            <div class="mb-16">
                <h3 class="font-display uppercase text-lg">Get In Touch</h3>
                <div class="w-10 h-1 bg-gradient-to-r from-primary to-accent mt-2 mb-6"></div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div class="border-2 border-primary/40 bg-white/5 rounded-xl p-5">
                        <svg class="w-5 h-5 text-primary mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 22s7-7.5 7-12.5A7 7 0 0 0 5 9.5C5 14.5 12 22 12 22Z"/><circle cx="12" cy="9.5" r="2.5"/></svg>
                        <p class="font-mono text-xs uppercase tracking-widest text-primary mb-2">Address</p>
                        <p class="text-white/70 text-sm leading-snug">The White House<br>c/o Kerk St &amp; Monument Rd<br>Kempton Park</p>
                    </div>
                    <div class="border-2 border-primary/40 bg-white/5 rounded-xl p-5">
                        <svg class="w-5 h-5 text-primary mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3 19.5 19.5 0 0 1-6-6 19.8 19.8 0 0 1-3-8.7A2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .3 2 .7 2.9a2 2 0 0 1-.4 2.1L8.1 10a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.4 1.9.6 2.9.7a2 2 0 0 1 1.6 2Z"/></svg>
                        <p class="font-mono text-xs uppercase tracking-widest text-primary mb-2">Phone</p>
                        <a href="tel:+27113941191" class="text-white/70 text-sm hover:text-primary">011 394 1191</a>
                    </div>
                    <div class="border-2 border-primary/40 bg-white/5 rounded-xl p-5">
                        <svg class="w-5 h-5 text-primary mb-2" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2Zm0 18.2a8.1 8.1 0 0 1-4.2-1.1l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1 1 12 20.2Zm4.5-6.1c-.2-.1-1.4-.7-1.7-.8-.2-.1-.4-.1-.6.1-.2.2-.6.8-.8 1-.1.2-.3.2-.5.1-.2-.1-1-.4-1.9-1.2-.7-.6-1.2-1.4-1.3-1.6-.1-.2 0-.4.1-.5.1-.1.2-.3.4-.4.1-.2.2-.3.2-.5.1-.2 0-.4 0-.5 0-.1-.6-1.5-.8-2-.2-.5-.4-.4-.6-.4h-.5c-.2 0-.5.1-.7.3-.2.2-1 1-1 2.3 0 1.4 1 2.7 1.1 2.9.1.2 2 3 4.8 4.3.7.3 1.2.5 1.6.6.7.2 1.3.2 1.8.1.5-.1 1.4-.6 1.6-1.1.2-.5.2-1 .1-1.1-.1-.1-.2-.2-.5-.3Z"/></svg>
                        <p class="font-mono text-xs uppercase tracking-widest text-primary mb-2">WhatsApp</p>
                        <a href="https://wa.me/27676456311" target="_blank" rel="noopener" class="text-white/70 text-sm hover:text-primary">067 645 6311</a>
                    </div>
                    <div class="border-2 border-primary/40 bg-white/5 rounded-xl p-5">
                        <svg class="w-5 h-5 text-primary mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                        <p class="font-mono text-xs uppercase tracking-widest text-primary mb-2">Email</p>
                        <a href="mailto:info@nomorepest.co.za" class="text-white/70 text-sm hover:text-primary break-all">info@nomorepest.co.za</a>
                    </div>
                    <div class="border-2 border-primary/40 bg-white/5 rounded-xl p-5">
                        <svg class="w-5 h-5 text-primary mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
                        <p class="font-mono text-xs uppercase tracking-widest text-primary mb-2">Hours</p>
                        <p class="text-white/70 text-sm leading-snug">Mon-Sat<br>07:00 - 17:00</p>
                    </div>
                </div>
            </div>

            {{-- ============ BOTTOM BAR ============ --}}
            <div class="border-t border-white/20 pt-6 flex flex-wrap items-center justify-between gap-4">
                <span class="font-mono text-xs text-white/50">&copy; {{ date('Y') }} SP Pest Control. All rights reserved.</span>
                <div class="flex gap-4 font-mono text-xs uppercase tracking-wide text-white/50">
                    <a href="{{ route('privacy') }}" class="hover:text-primary">Privacy Policy</a>
                    <span>|</span>
                    <a href="{{ route('terms') }}" class="hover:text-primary">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
