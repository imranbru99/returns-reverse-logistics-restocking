<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('returns.brand.name'))</title>
    <meta name="description" content="@yield('meta_description', 'Returns Platform — inspection, disposition, and restocking for reverse logistics.')">
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="alternate" hreflang="en" href="{{ route('locale.switch', ['locale' => 'en']) }}">
    <link rel="alternate" hreflang="bn" href="{{ route('locale.switch', ['locale' => 'bn']) }}">
    <meta property="og:title" content="@yield('og_title', config('returns.brand.name'))">
    <meta property="og:description" content="@yield('meta_description', 'Returns Platform — inspection, disposition, and restocking for reverse logistics.')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('head')
    @php
        $organizationSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => config('returns.brand.name'),
            'url' => config('app.url'),
            'email' => config('returns.brand.author_email'),
        ];
    @endphp
    <script type="application/ld+json">@json($organizationSchema)</script>
</head>
<body class="min-h-screen antialiased transition-colors duration-300" x-data="{ mobileOpen: false }">
    @include('marketing.partials.cookie-consent')
    <header class="sticky top-0 z-40 border-b border-slate-800/60 bg-slate-950/80 backdrop-blur dark:border-slate-800 dark:bg-slate-950/80 [.light_&]:border-slate-200 [.light_&]:bg-white/90">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-4">
            <a href="{{ route('marketing.home') }}" class="font-display text-xl font-semibold tracking-tight text-teal-400">
                {{ config('returns.brand.name') }}
            </a>
            <nav class="hidden items-center gap-6 text-sm text-slate-300 md:flex">
                <a href="{{ route('marketing.features') }}" class="hover:text-teal-300">Features</a>
                <a href="{{ route('marketing.modules') }}" class="hover:text-teal-300">Modules</a>
                <a href="{{ route('marketing.pricing') }}" class="hover:text-teal-300">Pricing</a>
                <a href="{{ route('portal.start') }}" class="hover:text-teal-300">Portal</a>
                <a href="{{ route('marketing.docs.api') }}" class="hover:text-teal-300">API</a>
            </nav>
            <div class="flex items-center gap-2">
                <div class="flex rounded-lg border border-slate-700 text-xs">
                    <a href="{{ route('locale.switch', 'en') }}" class="px-2 py-1 {{ app()->getLocale() === 'en' ? 'bg-teal-600 text-white' : 'text-slate-300' }}">EN</a>
                    <a href="{{ route('locale.switch', 'bn') }}" class="px-2 py-1 {{ app()->getLocale() === 'bn' ? 'bg-teal-600 text-white' : 'text-slate-300' }}">BN</a>
                </div>
                <button type="button"
                    class="rounded-lg border border-slate-600 px-2 py-1 text-xs text-slate-200"
                    x-on:click="const d=document.documentElement.classList.toggle('dark'); localStorage.setItem('theme', d?'dark':'light')">
                    Theme
                </button>
                <a href="{{ route('login') }}" class="hidden text-sm text-slate-300 hover:text-white sm:inline">Login</a>
                <a href="{{ route('marketing.register') }}" class="rounded-lg bg-teal-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-teal-500">Register</a>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="mt-16 border-t border-slate-800 bg-slate-950 px-4 py-10 text-sm text-slate-400">
        <div class="mx-auto grid max-w-6xl gap-8 md:grid-cols-3">
            <div>
                <p class="font-display text-lg text-teal-400">{{ config('returns.brand.name') }}</p>
                <p class="mt-2">Modular returns, inspection, and restocking.</p>
            </div>
            <div class="flex flex-col gap-2">
                <a href="{{ route('marketing.privacy') }}">Privacy</a>
                <a href="{{ route('marketing.terms') }}">Terms</a>
                <a href="{{ route('marketing.security') }}">Security</a>
            </div>
            <div class="flex flex-col gap-2">
                <a href="{{ route('marketing.contact') }}">Contact</a>
                <a href="{{ route('marketing.help') }}">Help center</a>
                @if (config('returns.demo_mode'))
                    <a href="{{ route('demo.index') }}">Demo login</a>
                @endif
            </div>
        </div>
    </footer>

    @livewireScripts
    @stack('scripts')
</body>
</html>
