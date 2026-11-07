<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $listing->product?->name ?? 'Listing' }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-slate-50 text-slate-900 antialiased">
    <div class="mx-auto max-w-3xl px-4 py-10">
        <a href="{{ route('resale.index') }}" class="text-sm text-emerald-700">&larr; All listings</a>
        <h1 class="mt-4 text-2xl font-semibold">{{ $listing->product?->name ?? 'Product' }}</h1>
        <p class="text-slate-600">Condition grade: <strong>{{ $listing->grade ?? 'B' }}</strong></p>
        <p class="mt-4 text-3xl font-bold">{{ money($listing->price_cents, $listing->currency, true) }}</p>
        @if(is_array($listing->photos) && count($listing->photos))
            <ul class="mt-6 list-disc pl-5 text-sm text-slate-600">
                @foreach($listing->photos as $photo)
                    <li>{{ is_string($photo) ? $photo : ($photo['url'] ?? 'Photo') }}</li>
                @endforeach
            </ul>
        @endif
    </div>
</body>
</html>
