<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — SP Pest Control</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-bgAlt text-ink font-sans min-h-screen flex">

    <aside class="w-60 flex-shrink-0 bg-secondary text-white min-h-screen p-6">
        <div class="font-display uppercase text-lg mb-8 tracking-wide">SP Admin</div>
        <nav class="flex flex-col gap-1 text-sm">
                       <a href="{{ route('admin.admins.index') }}" class="px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('admin.admins.*') ? 'bg-white/10 font-semibold' : '' }}">Admins</a>
            <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 font-semibold' : '' }}">Dashboard</a>
            <a href="{{ route('admin.plans.index') }}" class="px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('admin.plans.*') ? 'bg-white/10 font-semibold' : '' }}">Plans &amp; Pricing</a>
            <a href="{{ route('admin.customers.index') }}" class="px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('admin.customers.*') ? 'bg-white/10 font-semibold' : '' }}">Customers</a>
        </nav>
        <form method="POST" action="{{ route('admin.logout') }}" class="mt-10">
            @csrf
            <button type="submit" class="text-white/70 text-sm hover:text-white">Log out</button>
        </form>
    </aside>

    <main class="flex-1 p-8">
        @if (session('status'))
            <div class="mb-6 border-2 border-accent bg-accent/10 text-ink px-4 py-3 rounded-lg text-sm">
                {{ session('status') }}
            </div>
        @endif

        @yield('content')
    </main>

</body>
</html>
