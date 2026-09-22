<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<body style="font-family:Arial,sans-serif;line-height:1.6;color:#252525;">
    <div style="max-width:680px;margin:0 auto;padding:24px;">
        <h2 style="margin-top:0;">{{ getOption('app_name') }}</h2>
        <div>{!! nl2br(e($mailMessage)) !!}</div>
        <hr style="border:0;border-top:1px solid #e5e7eb;margin:24px 0;">
        <small style="color:#6b7280;">{{ getOption('app_name') }}</small>
    </div>
</body>
</html>
