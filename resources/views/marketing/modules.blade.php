@extends('layouts.marketing')

@section('title', 'Module marketplace — '.config('returns.brand.name'))

@section('content')
    <div class="mx-auto max-w-5xl px-4 py-16">
        <h1 class="font-display text-4xl font-semibold">Module marketplace</h1>
        <p class="mt-3 text-slate-600 dark:text-slate-300">Toggle modules and see a monthly estimate from each module&rsquo;s <code>price_tier</code>.</p>
        <div class="mt-10">
            <livewire:marketing.module-plan-builder />
        </div>
    </div>
@endsection
