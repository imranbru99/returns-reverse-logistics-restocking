<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Resale — Graded returns</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-slate-50 text-slate-900 antialiased">
    <div class="mx-auto max-w-5xl px-4 py-10">
        <h1 class="text-2xl font-semibold">Certified resale</h1>
        <p class="mt-2 text-slate-600">Graded open-box and refurbished inventory.</p>
        <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($listings as $listing)
                <a href="{{ route('resale.show', $listing) }}" class="block rounded-lg border border-slate-200 bg-white p-4 shadow-sm hover:border-emerald-500">
                    <p class="font-medium">{{ $listing->product?->name ?? 'Product' }}</p>
                    <p class="text-sm text-slate-500">Grade {{ $listing->grade ?? 'B' }}</p>
                    <p class="mt-2 text-lg font-semibold">{{ money($listing->price_cents, $listing->currency, true) }}</p>
                </a>
            @endforeach
        </div>
        <div class="mt-8">{{ $listings->links() }}</div>
    </div>
</body>
</html>
