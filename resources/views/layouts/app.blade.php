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

    <div class="border-b-2 border-primary sticky top-0 bg-white/95 z-50">
        <div class="max-w-6xl mx-auto px-7 py-3 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="SP Pest Control" class="w-11 h-11 rounded-full object-cover">
                <span class="font-display text-lg leading-none hidden sm:inline">SP <span class="text-primary">Pest Control</span></span>
            </a>
            <nav class="hidden md:flex gap-7 text-sm">
                <a href="{{ route('services.residential') }}" class="hover:text-primary">Residential</a>
                <a href="{{ route('services.commercial') }}" class="hover:text-primary">Commercial</a>
                <a href="{{ route('plans.index') }}" class="hover:text-primary">Protection Plans</a>
                <a href="{{ route('about') }}" class="hover:text-primary">About</a>
                <a href="{{ route('contact') }}" class="hover:text-primary">Contact</a>
                @auth
                    <a href="{{ route('portal.index') }}" class="hover:text-primary">My Account</a>
                @else
                    <a href="{{ route('login') }}" class="hover:text-primary">Log In</a>
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
                <img src="{{ asset('images/logo.png') }}" alt="SP Pest Control" class="w-9 h-9 rounded-full object-cover">
                <span class="font-mono text-xs uppercase tracking-widest text-white/70">SP Pest Control</span>
            </div>
            <span class="font-mono text-xs text-white/50">&copy; {{ date('Y') }}</span>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
