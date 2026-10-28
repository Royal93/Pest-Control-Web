<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sales') | SP Sales</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-bgAlt text-ink font-sans min-h-screen flex">

    <aside class="w-60 flex-shrink-0 bg-secondary text-white min-h-screen p-6">
        <div class="font-display uppercase text-lg mb-8 tracking-wide">SP Sales</div>
        <nav class="flex flex-col gap-1 text-sm">
            <a href="{{ route('marketing.dashboard') }}" class="px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('marketing.dashboard') ? 'bg-white/10 font-semibold' : '' }}">Dashboard</a>
            <a href="{{ route('marketing.leads.index') }}" class="px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('marketing.leads.*') ? 'bg-white/10 font-semibold' : '' }}">My Leads</a>
            <a href="{{ route('marketing.sales.index') }}" class="px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('marketing.sales.*') ? 'bg-white/10 font-semibold' : '' }}">My Sales</a>
        </nav>
        <div class="mt-10 text-xs text-white/60">{{ auth()->user()->name }} {{ auth()->user()->surname }}</div>
        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf
            <button type="submit" class="text-white/70 text-sm hover:text-white">Log out</button>
        </form>
    </aside>

    <main class="flex-1 p-8 min-w-0">
        @if (session('status'))
            <div class="mb-6 border-2 border-accent bg-accent/10 text-ink px-4 py-3 rounded-lg text-sm">
                {{ session('status') }}
            </div>
        @endif

        @yield('content')
    </main>

</body>
</html>
