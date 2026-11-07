@extends('layouts.marketing')
@section('title', 'Help — '.config('returns.brand.name'))
@section('content')
    <div class="mx-auto max-w-3xl px-4 py-16">
        <h1 class="font-display text-4xl font-semibold">Knowledge base</h1>
        <div class="mt-8 space-y-4">
            @foreach ($articles as $article)
                <article class="rounded-lg border border-slate-200 p-4 dark:border-slate-800">
                    <h2 class="font-semibold">{{ $article['title'] }}</h2>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ $article['body'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
@endsection
