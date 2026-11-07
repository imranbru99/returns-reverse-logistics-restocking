<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Return label {{ $rma->number }}</title>
    <style>body{font-family:system-ui;padding:2rem;} .box{border:2px dashed #0d9488;padding:1.5rem;max-width:28rem;}</style>
</head>
<body onload="window.print()">
    <div class="box">
        <h1>{{ config('returns.brand.name') }}</h1>
        <p><strong>RMA:</strong> {{ $rma->number }}</p>
        <p><strong>Order:</strong> {{ $rma->order?->number }}</p>
        <p><strong>Customer:</strong> {{ $rma->customer?->name }}</p>
        <p class="text-sm">Sandbox return label — attach to package.</p>
    </div>
</body>
</html>
