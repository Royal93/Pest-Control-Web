<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sales') | SP Sales</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="app-shell bg-bgAlt text-ink font-sans min-h-screen md:flex">

    {{-- Phones: top bar with a menu button; the sidebar slides in as a drawer --}}
    <header class="md:hidden sticky top-0 z-30 bg-secondary text-white flex items-center justify-between px-4 py-3">
        <span class="font-display uppercase text-lg tracking-wide">SP Sales</span>
        <button type="button" class="menu-btn w-11 h-11 flex items-center justify-center rounded-lg border border-white/30"
                data-menu-toggle="salesNav" aria-controls="salesNav" aria-expanded="false" aria-label="Open menu">
            <svg class="icon-open w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
            <svg class="icon-close w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/></svg>
        </button>
    </header>
    <div class="drawer-overlay fixed inset-0 z-30 bg-black/40 md:hidden" data-menu-close="salesNav"></div>


    <aside id="salesNav" class="drawer fixed inset-y-0 left-0 z-40 w-64 overflow-y-auto md:static md:z-auto md:w-60 md:flex-shrink-0 bg-secondary text-white min-h-screen p-6">
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

    <main class="flex-1 min-w-0 p-4 sm:p-6 md:p-8">
        @if (session('status'))
            <div class="mb-6 border-2 border-accent bg-accent/10 text-ink px-4 py-3 rounded-lg text-sm">
                {{ session('status') }}
            </div>
        @endif

        @yield('content')
    </main>

</body>
</html>
