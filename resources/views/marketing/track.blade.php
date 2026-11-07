@extends('layouts.marketing')
@section('title', 'Track return — '.config('returns.brand.name'))
@section('content')
    <div class="mx-auto max-w-xl px-4 py-16">
        <h1 class="font-display text-3xl font-semibold">Track your return</h1>
        <form method="post" action="{{ route('marketing.track') }}" class="mt-8 space-y-4">
            @csrf
            <input type="text" name="number" value="{{ old('number') }}" placeholder="RMA number" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 dark:border-slate-700 dark:bg-slate-900" required>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 dark:border-slate-700 dark:bg-slate-900" required>
            <button class="rounded-lg bg-teal-600 px-4 py-2 text-white">Track</button>
        </form>
        @if ($error)
            <p class="mt-4 text-red-500">{{ $error }}</p>
        @endif
        @if ($rma)
            <div class="mt-8 rounded-xl border border-slate-200 p-4 dark:border-slate-800">
                <p class="font-semibold">{{ $rma->number }}</p>
                <p class="text-sm">Status: {{ $rma->status->label() }}</p>
            </div>
        @endif
    </div>
@endsection
