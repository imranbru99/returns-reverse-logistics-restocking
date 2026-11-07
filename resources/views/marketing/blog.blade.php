@extends('layouts.marketing')

@section('title', 'Blog — '.config('returns.brand.name'))

@section('content')
    <div class="mx-auto max-w-4xl px-4 py-16">
        <h1 class="font-display text-4xl font-semibold">Blog</h1>
        <p class="mt-4 text-lg text-slate-600 dark:text-slate-300">Product updates and reverse-logistics practice notes.</p>

        @if ($posts->isEmpty())
            <p class="mt-12 rounded-lg border border-dashed border-slate-300 p-8 text-center text-slate-500">
                No published posts yet. Check back soon.
            </p>
        @else
            <ul class="mt-12 space-y-8">
                @foreach ($posts as $post)
                    <li class="border-b border-slate-200 pb-8 dark:border-slate-700">
                        <a href="{{ route('marketing.blog.show', $post) }}" class="text-2xl font-semibold text-teal-700 hover:underline dark:text-teal-400">
                            {{ $post->title }}
                        </a>
                        @if ($post->excerpt)
                            <p class="mt-2 text-slate-600 dark:text-slate-300">{{ $post->excerpt }}</p>
                        @endif
                        <p class="mt-2 text-sm text-slate-500">{{ $post->published_at?->format('M j, Y') }}</p>
                    </li>
                @endforeach
            </ul>

            <div class="mt-8">{{ $posts->links() }}</div>
        @endif
    </div>
@endsection
