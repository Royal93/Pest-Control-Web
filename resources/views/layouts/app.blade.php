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
        <div class="max-w-6xl mx-auto px-7 py-4 flex items-center justify-between">
            <a href="{{ route('home') }}" class="font-display text-xl">
                <span class="text-signal">SP</span> Pest Control
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

    <footer class="border-t-2 border-primary mt-20 py-10">
        <div class="max-w-6xl mx-auto px-7 text-sm text-inkMuted flex justify-between font-mono uppercase tracking-wide">
            <span>SP Pest Control</span>
            <span>&copy; {{ date('Y') }}</span>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
