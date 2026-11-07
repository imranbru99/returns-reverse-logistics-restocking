@extends('layouts.marketing')
@section('title', 'Developers — '.config('returns.brand.name'))
@section('content')
    <div class="mx-auto max-w-3xl px-4 py-16">
        <h1 class="font-display text-4xl font-semibold">Developer hub</h1>
        @if ($apiModule)
            <p class="mt-4">{{ $apiModule['description'] }}</p>
        @endif
        <a href="{{ route('marketing.docs.api') }}" class="mt-6 inline-block text-teal-600 hover:underline">OpenAPI reference</a>
    </div>
@endsection
