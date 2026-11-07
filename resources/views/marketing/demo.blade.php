@extends('layouts.marketing')
@section('title', 'Demo login — '.config('returns.brand.name'))
@section('content')
    <div class="mx-auto max-w-2xl px-4 py-16">
        <h1 class="font-display text-4xl font-semibold">Demo login</h1>
        <p class="mt-3 text-sm text-slate-600 dark:text-slate-300">Password: <code>{{ config('returns.demo_password') }}</code></p>
        <form method="post" action="{{ route('demo.login') }}" class="mt-8 space-y-4">
            @csrf
            <select name="tenant" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:border-slate-700 dark:bg-slate-900" required>
                @foreach ($tenants as $tenant)
                    <option value="{{ $tenant->slug }}">{{ $tenant->name }}</option>
                @endforeach
            </select>
            <select name="role" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:border-slate-700 dark:bg-slate-900" required>
                @foreach ($roles as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </select>
            <button class="rounded-lg bg-teal-600 px-4 py-2 text-white">Sign in</button>
        </form>
    </div>
@endsection
