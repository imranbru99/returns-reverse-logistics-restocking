@extends('layouts.marketing')

@section('title', config('returns.brand.name').' — Reverse logistics platform')
@section('meta_description', 'Returns Platform coordinates return, receive, inspect, disposition, and restock workflows from one modular stack.')

@section('content')
    <section class="mesh-bg relative overflow-hidden px-4 pb-20 pt-16 text-white">
        <div class="mx-auto max-w-6xl">
            <p class="font-display text-5xl font-bold tracking-tight text-teal-300 md:text-7xl">{{ config('returns.brand.name') }}</p>
            <h1 class="mt-6 max-w-2xl text-2xl font-medium text-slate-100 md:text-3xl">Run every return through inspection, disposition, and restock with tenant-scoped modules.</h1>
            <p class="mt-4 max-w-xl text-slate-300">Toggle capabilities from <code class="text-teal-200">config/modules.php</code> without redeploying code.</p>
            <div class="mt-8 flex flex-wrap gap-3">
                @if (config('returns.demo_mode'))
                    <a href="{{ route('demo.index') }}" class="rounded-lg bg-teal-500 px-5 py-2.5 font-medium text-slate-950 hover:bg-teal-400">Try demo</a>
                @else
                    <a href="{{ route('marketing.how-it-works') }}" class="rounded-lg bg-teal-500 px-5 py-2.5 font-medium text-slate-950 hover:bg-teal-400">How it works</a>
                @endif
                <a href="{{ route('marketing.register') }}" class="rounded-lg border border-teal-400/60 px-5 py-2.5 text-teal-100 hover:bg-teal-500/10">Start trial</a>
            </div>
        </div>
    </section>

    <section class="px-4 py-16">
        <div class="mx-auto max-w-6xl">
            <h2 class="font-display text-2xl font-semibold text-teal-700 dark:text-teal-300">Workflow</h2>
            <ol class="mt-6 grid gap-4 md:grid-cols-5">
                @foreach (['Return', 'Receive', 'Inspect', 'Disposition', 'Restock'] as $step)
                    <li class="rounded-xl border border-slate-200 bg-white p-4 text-center shadow-sm transition hover:-translate-y-0.5 dark:border-slate-800 dark:bg-slate-900">
                        <span class="text-sm font-semibold uppercase tracking-wide text-teal-600">{{ $step }}</span>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    <section class="mesh-bg-light px-4 py-16 dark:mesh-bg">
        <div class="mx-auto max-w-6xl">
            <h2 class="font-display text-2xl font-semibold">Platform features</h2>
            <div class="mt-8 grid gap-6 md:grid-cols-3">
                @foreach ($features as $feature)
                    <a href="{{ route('marketing.features.show', $feature['key']) }}" class="group rounded-xl border border-slate-200 bg-white/80 p-5 dark:border-slate-800 dark:bg-slate-900/80">
                        <h3 class="font-semibold text-teal-700 group-hover:text-teal-500 dark:text-teal-300">{{ $feature['name'] }}</h3>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ $feature['description'] }}</p>
                    </a>
                @endforeach
            </div>
            <div class="mt-10 text-center">
                <a href="{{ route('marketing.features') }}" class="text-teal-600 hover:underline">View all features</a>
            </div>
        </div>
    </section>

    <section class="px-4 py-16 text-center">
        <h2 class="font-display text-3xl font-semibold">Ready to model your returns stack?</h2>
        <p class="mt-3 text-slate-600 dark:text-slate-300">Build a module plan estimate or open the customer portal.</p>
        <div class="mt-6 flex justify-center gap-3">
            <a href="{{ route('marketing.modules') }}" class="rounded-lg bg-teal-600 px-5 py-2 text-white">Module marketplace</a>
            <a href="{{ route('portal.start') }}" class="rounded-lg border border-teal-600 px-5 py-2 text-teal-700 dark:text-teal-300">Start a return</a>
        </div>
    </section>
@endsection
