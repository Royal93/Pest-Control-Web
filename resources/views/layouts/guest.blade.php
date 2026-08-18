<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SP Pest Control') }}</title>

        <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Source+Sans+3:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-body text-ink antialiased bg-bgAlt">
        <div class="min-h-screen flex flex-col items-center justify-center px-6 py-12">
            <a href="/" class="font-display text-2xl mb-8">
                <span class="text-signal">SP</span> Pest Control
            </a>

            <div class="w-full sm:max-w-md bg-white border-2 border-primary p-8">
                {{ $slot }}
            </div>

            <a href="/" class="font-mono text-xs uppercase tracking-widest text-inkFaint mt-8 hover:text-primary">
                &larr; Back to site
            </a>
        </div>
    </body>
</html>
