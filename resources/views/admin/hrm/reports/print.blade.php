<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        * { box-sizing: border-box; }
        body { color: #26364a; font-family: Arial, sans-serif; margin: 0; padding: 32px; }
        .report-head { align-items: flex-start; border-bottom: 2px solid #4778c7; display: flex; justify-content: space-between; margin-bottom: 24px; padding-bottom: 14px; }
        h1 { font-size: 24px; margin: 0 0 6px; }
        .muted { color: #718096; font-size: 12px; }
        table { border-collapse: collapse; font-size: 12px; width: 100%; }
        th { background: #4778c7; color: #fff; font-weight: 600; text-align: left; }
        th, td { border: 1px solid #dfe6ef; padding: 9px 10px; }
        tr:nth-child(even) { background: #f7f9fc; }
        .empty { color: #718096; padding: 24px; text-align: center; }
        @media print { body { padding: 0; } .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="report-head">
        <div><h1>{{ $title }}</h1><div class="muted">{{ config('app.name') }}</div></div>
        <div class="muted">{{ __('Generated') }}: {{ now()->format('d M Y, h:i A') }}</div>
    </div>
    @if(count($rows))
        <table>
            <thead><tr>@foreach($columns as $column)<th>{{ __($column) }}</th>@endforeach</tr></thead>
            <tbody>@foreach($rows as $row)<tr>@foreach($row as $value)<td>{{ $value }}</td>@endforeach</tr>@endforeach</tbody>
        </table>
    @else
        <div class="empty">{{ __('No records found') }}</div>
    @endif
    <script>window.onload = function () { window.print(); };</script>
</body>
</html>
