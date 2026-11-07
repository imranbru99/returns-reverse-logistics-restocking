@extends('layouts.marketing')

@section('title', $title.' — '.config('returns.brand.name'))

@section('content')
    <div class="mx-auto max-w-4xl px-4 py-16">
        <h1 class="font-display text-4xl font-semibold">For {{ $title }}</h1>
        <ul class="mt-8 space-y-4">
            @foreach ($modules as $module)
                <li class="rounded-lg border border-slate-200 p-4 dark:border-slate-800">
                    <p class="font-medium">{{ $module['name'] }}</p>
                    <p class="text-sm text-slate-600 dark:text-slate-300">{{ $module['description'] }}</p>
                </li>
            @endforeach
        </ul>
    </div>
@endsection
