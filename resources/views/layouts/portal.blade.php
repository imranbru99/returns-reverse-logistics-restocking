<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer returns — {{ config('returns.brand.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
    <header class="border-b border-slate-800 px-4 py-4">
        <div class="mx-auto flex max-w-3xl items-center justify-between">
            <a href="{{ route('marketing.home') }}" class="font-display text-lg text-teal-400">{{ config('returns.brand.name') }}</a>
            <a href="{{ route('portal.track') }}" class="text-sm text-slate-300 hover:text-white">Track return</a>
        </div>
    </header>
    <main class="mx-auto max-w-3xl px-4 py-10">
        {{ $slot }}
    </main>
    @livewireScripts
</body>
</html>
