@extends('layouts.marketing')

@section('title', $title.' solutions — '.config('returns.brand.name'))

@section('content')
    <div class="mx-auto max-w-4xl px-4 py-16">
        <h1 class="font-display text-4xl font-semibold">{{ $title }}</h1>
        @if ($preset)
            <p class="mt-3 text-slate-600 dark:text-slate-300">Preset: {{ $preset->label() }} — modules enabled by default for this vertical.</p>
        @endif
        <ul class="mt-8 space-y-4">
            @foreach ($modules as $module)
                <li class="rounded-lg border border-slate-200 p-4 dark:border-slate-800">
                    <p class="font-medium text-teal-700 dark:text-teal-300">{{ $module['name'] }}</p>
                    <p class="text-sm text-slate-600 dark:text-slate-300">{{ $module['description'] }}</p>
                </li>
            @endforeach
        </ul>
    </div>
@endsection
