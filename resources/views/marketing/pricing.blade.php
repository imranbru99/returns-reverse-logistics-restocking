@extends('layouts.marketing')

@section('title', 'Pricing — '.config('returns.brand.name'))

@section('content')
    <div class="mx-auto max-w-6xl px-4 py-16">
        <h1 class="font-display text-4xl font-semibold">Pricing</h1>
        <div class="mt-10 grid gap-6 md:grid-cols-3">
            @foreach ($plans as $plan)
                <div class="rounded-2xl border border-slate-200 p-6 dark:border-slate-800">
                    <h2 class="text-xl font-semibold">{{ $plan['name'] }}</h2>
                    <p class="mt-2 text-3xl font-bold text-teal-600">
                        @if ($plan['price'] === null)
                            Custom
                        @else
                            ${{ number_format($plan['price']) }}<span class="text-sm font-normal text-slate-500">/mo</span>
                        @endif
                    </p>
                    <p class="mt-3 text-sm text-slate-600 dark:text-slate-300">{{ $plan['description'] }}</p>
                    <ul class="mt-4 list-disc pl-5 text-sm">
                        @foreach ($plan['included_modules'] as $moduleKey)
                            <li>{{ $moduleKey }}</li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
        <p class="mt-8 text-sm text-slate-500">Add-on module tiers: free $0 · starter ${{ $tierPrices['starter'] ?? 0 }} · growth ${{ $tierPrices['growth'] ?? 0 }} · enterprise ${{ $tierPrices['enterprise'] ?? 0 }} / module / month.</p>
    </div>
@endsection
