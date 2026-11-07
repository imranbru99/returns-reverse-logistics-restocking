@extends('layouts.marketing')

@section('title', $feature['name'].' — '.config('returns.brand.name'))

@section('content')
    <div class="mx-auto max-w-3xl px-4 py-16">
        <p class="text-sm uppercase tracking-wide text-teal-600">{{ $feature['category'] }}</p>
        <h1 class="font-display mt-2 text-4xl font-semibold">{{ $feature['name'] }}</h1>
        <p class="mt-4 text-lg text-slate-600 dark:text-slate-300">{{ $feature['description'] }}</p>
        <ul class="mt-8 list-disc space-y-2 pl-5 text-slate-700 dark:text-slate-200">
            @foreach ($feature['highlights'] as $highlight)
                <li>{{ $highlight }}</li>
            @endforeach
        </ul>
        <p class="mt-8 text-sm text-slate-500">Module key: <code>{{ $feature['module'] }}</code></p>
    </div>
@endsection
