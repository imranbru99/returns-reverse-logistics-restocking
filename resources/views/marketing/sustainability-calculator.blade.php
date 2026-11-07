@extends('layouts.marketing')
@section('title', 'Sustainability calculator — '.config('returns.brand.name'))
@section('content')
    <div class="mx-auto max-w-3xl px-4 py-16">
        <h1 class="font-display text-4xl font-semibold">CO₂ impact estimator</h1>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Uses the Recycling, Donation &amp; Sustainability module assumptions for diversion.</p>
        <div class="mt-8"><livewire:marketing.co2-calculator /></div>
    </div>
@endsection
