@extends('layouts.marketing')

@section('title', 'How it works — '.config('returns.brand.name'))

@section('content')
    <div class="mx-auto max-w-4xl px-4 py-16">
        <h1 class="font-display text-4xl font-semibold">How it works</h1>
        <ol class="mt-10 space-y-6">
            <li class="rounded-xl border border-slate-200 p-5 dark:border-slate-800"><strong>Authorize</strong> — Shoppers or agents open an RMA tied to an order via the customer portal or admin.</li>
            <li class="rounded-xl border border-slate-200 p-5 dark:border-slate-800"><strong>Receive</strong> — Dock sessions match inbound units when Receiving &amp; Dock Management is enabled.</li>
            <li class="rounded-xl border border-slate-200 p-5 dark:border-slate-800"><strong>Inspect</strong> — QC checklists and grades from the Inspection Engine capture evidence.</li>
            <li class="rounded-xl border border-slate-200 p-5 dark:border-slate-800"><strong>Disposition</strong> — Rules route goods to resale, refurbish, scrap, or vendor RTV.</li>
            <li class="rounded-xl border border-slate-200 p-5 dark:border-slate-800"><strong>Restock</strong> — Putaway tasks return sellable inventory to warehouse bins.</li>
        </ol>
    </div>
@endsection
