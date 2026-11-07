@extends('layouts.marketing')
@section('title', 'Returns portal — '.config('returns.brand.name'))
@section('content')
    <div class="mx-auto max-w-xl px-4 py-16 text-center">
        <h1 class="font-display text-4xl font-semibold">Customer returns</h1>
        <p class="mt-4 text-slate-600 dark:text-slate-300">Start a new return or track an existing RMA.</p>
        <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-center">
            <a href="{{ route('portal.start') }}" class="rounded-lg bg-teal-600 px-5 py-2 text-white">Start return</a>
            <a href="{{ route('portal.track') }}" class="rounded-lg border border-teal-600 px-5 py-2 text-teal-700 dark:text-teal-300">Track return</a>
        </div>
    </div>
@endsection
