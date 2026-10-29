<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') | SP Pest Control</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="app-shell bg-bgAlt text-ink font-sans min-h-screen md:flex">

    {{-- Phones: top bar with a menu button; the sidebar slides in as a drawer --}}
    <header class="md:hidden sticky top-0 z-30 bg-secondary text-white flex items-center justify-between px-4 py-3">
        <span class="font-display uppercase text-lg tracking-wide">SP Admin</span>
        <button type="button" class="menu-btn w-11 h-11 flex items-center justify-center rounded-lg border border-white/30"
                data-menu-toggle="adminNav" aria-controls="adminNav" aria-expanded="false" aria-label="Open menu">
            <svg class="icon-open w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
            <svg class="icon-close w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/></svg>
        </button>
    </header>
    <div class="drawer-overlay fixed inset-0 z-30 bg-black/40 md:hidden" data-menu-close="adminNav"></div>


    <aside id="adminNav" class="drawer fixed inset-y-0 left-0 z-40 w-64 overflow-y-auto md:static md:z-auto md:w-60 md:flex-shrink-0 bg-secondary text-white min-h-screen p-6">
        <div class="font-display uppercase text-lg mb-8 tracking-wide">SP Admin</div>
        <nav class="flex flex-col gap-1 text-sm">
            <a href="{{ route('admin.admins.index') }}" class="px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('admin.admins.*') ? 'bg-white/10 font-semibold' : '' }}">Admins</a>
            <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 font-semibold' : '' }}">Dashboard</a>
            <a href="{{ route('admin.plans.index') }}" class="px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('admin.plans.*') ? 'bg-white/10 font-semibold' : '' }}">Plans &amp; Pricing</a>
            <a href="{{ route('admin.customers.index') }}" class="px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('admin.customers.*') ? 'bg-white/10 font-semibold' : '' }}">Customers</a>
            <a href="{{ route('admin.invoices.index') }}" class="px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('admin.invoices.*') ? 'bg-white/10 font-semibold' : '' }}">Invoices</a>
            <a href="{{ route('admin.marketing.index') }}" class="px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('admin.marketing.*') ? 'bg-white/10 font-semibold' : '' }}">Sales Team</a>
            <a href="{{ route('admin.leads.index') }}" class="px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('admin.leads.index') || request()->routeIs('admin.leads.show') ? 'bg-white/10 font-semibold' : '' }}">Leads</a>
            <a href="{{ route('admin.leads.create') }}" class="px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('admin.leads.create') ? 'bg-white/10 font-semibold' : '' }}">Capture Lead</a>
            <a href="{{ route('admin.sales.index') }}" class="px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('admin.sales.*') ? 'bg-white/10 font-semibold' : '' }}">Sales &amp; Agreements</a>
        </nav>
        <form method="POST" action="{{ route('admin.logout') }}" class="mt-10">
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
        @if (session('error'))
            <div class="mb-6 border-2 border-red-500 bg-red-50 text-ink px-4 py-3 rounded-lg text-sm">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

</body>
</html>
