@extends('layouts.marketing')
@section('title', 'API docs — '.config('returns.brand.name'))
@section('content')
    <div class="mx-auto max-w-3xl px-4 py-16">
        <h1 class="font-display text-4xl font-semibold">REST API v1</h1>
        <p class="mt-4">Spec: <code>openapi/openapi.yaml</code></p>
        <ul class="mt-6 list-disc space-y-2 pl-5">
            <li><code>GET /api/v1/health</code></li>
            <li><code>GET /api/v1/rmas</code> (Bearer + <code>X-Tenant-ID</code>)</li>
            <li><code>POST /api/v1/rmas</code></li>
        </ul>
        <p class="mt-6 text-sm text-slate-500">Import <code>postman/collection.json</code> for a starter collection.</p>
    </div>
@endsection
