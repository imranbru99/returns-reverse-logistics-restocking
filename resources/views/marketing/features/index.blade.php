@extends('layouts.marketing')

@section('title', 'Features — '.config('returns.brand.name'))

@section('content')
    <div class="mx-auto max-w-6xl px-4 py-16">
        <h1 class="font-display text-4xl font-semibold">Features</h1>
        <p class="mt-3 max-w-2xl text-slate-600 dark:text-slate-300">Every card maps to an entry in <code>config/features.php</code>.</p>
        <div class="mt-10 grid gap-6 md:grid-cols-2">
            @foreach ($features as $feature)
                <article class="rounded-xl border border-slate-200 p-6 dark:border-slate-800">
                    <h2 class="text-xl font-semibold text-teal-700 dark:text-teal-300">
                        <a href="{{ route('marketing.features.show', $feature['key']) }}">{{ $feature['name'] }}</a>
                    </h2>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ $feature['description'] }}</p>
                    <p class="mt-3 text-xs uppercase tracking-wide text-slate-500">{{ $feature['category'] }} · {{ $feature['module'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
@endsection
