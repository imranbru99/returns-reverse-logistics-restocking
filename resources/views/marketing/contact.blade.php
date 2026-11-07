@extends('layouts.marketing')
@section('title', 'Contact — '.config('returns.brand.name'))
@section('content')
    <div class="mx-auto max-w-xl px-4 py-16">
        <h1 class="font-display text-4xl font-semibold">Contact</h1>
        @if (session('status'))
            <p class="mt-4 text-teal-600">{{ session('status') }}</p>
        @endif
        <form method="post" action="{{ route('marketing.contact.store') }}" class="mt-8 space-y-4">
            @csrf
            <input name="name" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:border-slate-700 dark:bg-slate-900" required value="{{ old('name') }}">
            <input name="email" type="email" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:border-slate-700 dark:bg-slate-900" required value="{{ old('email') }}">
            <input name="company" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:border-slate-700 dark:bg-slate-900" value="{{ old('company') }}">
            <input name="subject" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:border-slate-700 dark:bg-slate-900" required value="{{ old('subject') }}">
            <textarea name="message" rows="5" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:border-slate-700 dark:bg-slate-900" required>{{ old('message') }}</textarea>
            <button class="rounded-lg bg-teal-600 px-4 py-2 text-white">Send</button>
        </form>
    </div>
@endsection
