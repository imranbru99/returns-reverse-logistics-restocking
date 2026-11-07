<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Destruction Certificate {{ $certificate->certificate_number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1 { font-size: 18px; margin-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
    </style>
</head>
<body>
    <h1>Certificate of Destruction</h1>
    <p><strong>Number:</strong> {{ $certificate->certificate_number }}</p>
    <p><strong>Issued:</strong> {{ $certificate->issued_at?->format('Y-m-d H:i') }}</p>
    @if($partner)
        <p><strong>Recycling partner:</strong> {{ $partner->name }}</p>
    @endif
    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $row)
                <tr>
                    <td>{{ $row['description'] ?? $row['sku'] ?? 'Item' }}</td>
                    <td>{{ $row['quantity'] ?? 1 }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
