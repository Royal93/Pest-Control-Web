<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — SP Pest Control</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-bgAlt text-ink font-sans min-h-screen flex items-center justify-center">

    <div class="w-full max-w-sm border-2 border-primary rounded-2xl p-8 bg-white">
        <h1 class="font-display uppercase text-xl mb-6 text-center">Admin Login</h1>

        @if ($errors->any())
            <div class="mb-4 text-sm text-red-600 border-2 border-red-300 rounded-lg px-3 py-2">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login') }}" class="flex flex-col gap-4">
            @csrf
            <div>
                <label class="block text-xs uppercase font-semibold mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs uppercase font-semibold mb-1">Password</label>
                <input type="password" name="password" required
                       class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm">
            </div>
            <label class="flex items-center gap-2 text-sm text-inkMuted">
                <input type="checkbox" name="remember"> Remember me
            </label>
            <button type="submit" class="border-2 border-primary bg-primary text-white uppercase text-sm font-semibold py-2 rounded-lg">
                Log In
            </button>
        </form>
    </div>

</body>
</html>
