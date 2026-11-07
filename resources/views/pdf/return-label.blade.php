<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('returns.shipment.label', [], 'en') ?? 'Return label' }} — {{ $rma->number }}</title>
    <style>
        body { font-family: 'Noto Sans', 'DejaVu Sans', sans-serif; margin: 24px; color: #111827; }
        .brand { color: {{ $brandColor ?? '#047857' }}; font-size: 22px; font-weight: 700; }
        .box { border: 2px dashed #9ca3af; padding: 16px; margin-top: 20px; }
        .tracking { font-size: 20px; letter-spacing: 0.05em; font-weight: 600; }
        .muted { color: #6b7280; font-size: 12px; }
        .row { margin-top: 8px; }
    </style>
</head>
<body>
    <div class="brand">{{ $tenantName }}</div>
    <p class="muted">{{ __('Returns · inbound parcel', [], 'en') }}</p>

    <div class="box">
        <div class="row"><strong>RMA</strong> {{ $rma->number }}</div>
        <div class="row tracking">{{ $shipment->tracking_number }}</div>
        <div class="row muted">Carrier: {{ $shipment->carrier }}</div>
        @if($shipment->rate_cents)
            <div class="row muted">Rate: {{ number_format($shipment->rate_cents / 100, 2) }} USD</div>
        @endif
    </div>

    <p class="muted" style="margin-top: 24px;">
        {{ __('Affix this label to the outside of the return package. Scan the tracking barcode at receiving.', [], 'en') }}
    </p>
</body>
</html>
