<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('Material Label') }} - {{ $material->item_code }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 32px; color: #222; }
        .label { width: 420px; border: 2px solid #222; padding: 24px; text-align: center; }
        .qr { width: 170px; height: 170px; margin: 18px auto; }
        .barcode { display: flex; height: 64px; justify-content: center; gap: 3px; margin: 18px 0 8px; }
        .barcode span { display: block; background: #111; }
        .barcode span:nth-child(3n) { width: 5px; }.barcode span:nth-child(3n+1) { width: 2px; }.barcode span:nth-child(3n+2) { width: 4px; }
        .code { font-size: 18px; font-weight: bold; letter-spacing: 2px; }
        @media print { .print-button { display: none; } }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">{{ __('Print Label') }}</button>
    <div class="label">
        <h2>{{ $material->item_name }}</h2>
        <p>{{ $material->category }} | {{ $material->unit }}</p>
        <img class="qr" src="https://quickchart.io/qr?text={{ urlencode($material->barcode ?: $material->item_code) }}&size=170" alt="{{ __('QR Code') }}">
        <div class="barcode">@for($i = 0; $i < 24; $i++)<span></span>@endfor</div>
        <div class="code">{{ $material->barcode ?: $material->item_code }}</div>
        <p>{{ $material->warehouse }}{{ $material->location ? ' / ' . $material->location : '' }}</p>
    </div>
</body>
</html>
