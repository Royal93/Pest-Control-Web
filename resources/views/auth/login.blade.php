<x-guest-layout>
    <p class="font-mono text-xs tracking-widest uppercase text-primary mb-2">My Account</p>
    <h1 class="font-display uppercase text-2xl mb-6">Log In</h1>

    @if (session('status'))
        <div class="border-2 border-primary p-3 mb-5 text-sm">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <label class="flex flex-col gap-2 text-sm text-inkMuted">
            Email
            <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="border-2 border-line p-3">
            @error('email')
                <span class="text-signal text-xs">{{ $message }}</span>
            @enderror
        </label>

        <label class="flex flex-col gap-2 text-sm text-inkMuted">
            Password
            <input type="password" name="password" required autocomplete="current-password" class="border-2 border-line p-3">
            @error('password')
                <span class="text-signal text-xs">{{ $message }}</span>
            @enderror
        </label>

        <label class="flex items-center gap-2 text-sm text-inkMuted">
            <input type="checkbox" name="remember">
            Remember me
        </label>

        <button type="submit" class="w-full bg-primary text-white uppercase text-sm font-semibold py-3">
            Log In
        </button>

        <div class="flex justify-between text-sm pt-2">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-inkMuted hover:text-primary">Forgot your password?</a>
            @endif
            <a href="{{ route('register') }}" class="text-primary font-semibold">Create an account</a>
        </div>
    </form>
</x-guest-layout>
