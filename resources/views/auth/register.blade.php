<x-guest-layout>
    <p class="font-mono text-xs tracking-widest uppercase text-primary mb-2">My Account</p>
    <h1 class="font-display uppercase text-2xl mb-6">Create Your Account</h1>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div class="grid sm:grid-cols-2 gap-5">
            <label class="flex flex-col gap-2 text-sm text-inkMuted">
                Name
                <input type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="given-name" class="border-2 border-line p-3">
                @error('name')
                    <span class="text-signal text-xs">{{ $message }}</span>
                @enderror
            </label>

            <label class="flex flex-col gap-2 text-sm text-inkMuted">
                Surname
                <input type="text" name="surname" value="{{ old('surname') }}" required autocomplete="family-name" class="border-2 border-line p-3">
                @error('surname')
                    <span class="text-signal text-xs">{{ $message }}</span>
                @enderror
            </label>
        </div>

        <label class="flex flex-col gap-2 text-sm text-inkMuted">
            Email
            <input type="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="border-2 border-line p-3">
            @error('email')
                <span class="text-signal text-xs">{{ $message }}</span>
            @enderror
        </label>

        <label class="flex flex-col gap-2 text-sm text-inkMuted">
            Phone Number
            <input type="tel" name="phone" value="{{ old('phone') }}" required autocomplete="tel" class="border-2 border-line p-3">
            @error('phone')
                <span class="text-signal text-xs">{{ $message }}</span>
            @enderror
        </label>

        <label class="flex flex-col gap-2 text-sm text-inkMuted">
            Password
            <input type="password" name="password" required autocomplete="new-password" class="border-2 border-line p-3">
            @error('password')
                <span class="text-signal text-xs">{{ $message }}</span>
            @enderror
        </label>

        <label class="flex flex-col gap-2 text-sm text-inkMuted">
            Confirm Password
            <input type="password" name="password_confirmation" required autocomplete="new-password" class="border-2 border-line p-3">
        </label>

        <button type="submit" class="w-full bg-primary text-white uppercase text-sm font-semibold py-3">
            Create Account
        </button>

        <div class="text-sm text-center pt-2">
            <a href="{{ route('login') }}" class="text-inkMuted hover:text-primary">Already have an account? Log in</a>
        </div>
    </form>
</x-guest-layout>
