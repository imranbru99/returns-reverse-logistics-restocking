@extends('layouts.marketing')

@section('title', $title.' — '.config('returns.brand.name'))

@section('content')
    <div class="mx-auto max-w-3xl px-4 py-16">
        <h1 class="font-display text-4xl font-semibold">{{ $heading }}</h1>
        <p class="mt-4 text-lg text-slate-600 dark:text-slate-300">{{ $lead }}</p>
    </div>
@endsection
