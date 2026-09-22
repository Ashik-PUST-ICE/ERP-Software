<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<body style="font-family:Arial,sans-serif;line-height:1.6;color:#252525;background:#f8fafc;padding:24px;">
    <div style="max-width:680px;margin:0 auto;background:#fff;padding:28px;border-radius:10px;">
        <h2 style="margin-top:0;color:#1e3a8a;">{{ getOption('app_name') }}</h2>
        <div style="white-space:pre-line;margin-bottom:24px;">{{ $mailMessage }}</div>
        <div style="border:1px solid #e5e7eb;border-radius:8px;padding:16px;">
            <strong>{{ __('Invoice') }}:</strong> {{ $invoice->invoice_number }}<br>
            <strong>{{ __('Order') }}:</strong> {{ $invoice->order?->order_number ?: '-' }}<br>
            <strong>{{ __('Total') }}:</strong> {{ $invoice->currency }} {{ number_format((float) $invoice->total_amount, 2) }}<br>
            <strong>{{ __('Due date') }}:</strong> {{ $invoice->due_date?->format('d M Y') ?: __('Not specified') }}
        </div>
        <p style="margin-top:22px;"><a href="{{ route('admin.garments.invoices.print', $invoice->id) }}" style="background:#4778c7;color:#fff;text-decoration:none;padding:10px 16px;border-radius:6px;">{{ __('View Invoice') }}</a></p>
    </div>
</body>
</html>
