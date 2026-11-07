@extends('layouts.marketing')

@section('title', $post->title.' — '.config('returns.brand.name'))

@section('content')
    <article class="mx-auto max-w-3xl px-4 py-16">
        <a href="{{ route('marketing.blog') }}" class="text-sm text-teal-700 hover:underline dark:text-teal-400">&larr; All posts</a>
        <h1 class="mt-4 font-display text-4xl font-semibold">{{ $post->title }}</h1>
        <p class="mt-2 text-sm text-slate-500">{{ $post->published_at?->format('F j, Y') }}</p>
        <div class="prose prose-slate mt-8 max-w-none dark:prose-invert">
            {!! nl2br(e($post->body ?? '')) !!}
        </div>
    </article>
@endsection
