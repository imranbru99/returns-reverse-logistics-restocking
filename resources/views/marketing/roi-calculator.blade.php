@extends('layouts.marketing')
@section('title', 'ROI calculator — '.config('returns.brand.name'))
@section('content')
    <div class="mx-auto max-w-3xl px-4 py-16">
        <h1 class="font-display text-4xl font-semibold">ROI calculator</h1>
        <div class="mt-8"><livewire:marketing.roi-calculator /></div>
    </div>
@endsection
