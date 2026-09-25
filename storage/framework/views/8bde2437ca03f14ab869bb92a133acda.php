<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<body style="font-family:Arial,sans-serif;line-height:1.6;color:#252525;background:#f8fafc;padding:24px;">
    <div style="max-width:680px;margin:0 auto;background:#fff;padding:28px;border-radius:10px;">
        <h2 style="margin-top:0;color:#1e3a8a;"><?php echo e(getOption('app_name')); ?></h2>
        <div style="white-space:pre-line;margin-bottom:24px;"><?php echo e($mailMessage); ?></div>
        <div style="border:1px solid #e5e7eb;border-radius:8px;padding:16px;">
            <strong><?php echo e(__('Invoice')); ?>:</strong> <?php echo e($invoice->invoice_number); ?><br>
            <strong><?php echo e(__('Order')); ?>:</strong> <?php echo e($invoice->order?->order_number ?: '-'); ?><br>
            <strong><?php echo e(__('Total')); ?>:</strong> <?php echo e($invoice->currency); ?> <?php echo e(number_format((float) $invoice->total_amount, 2)); ?><br>
            <strong><?php echo e(__('Due date')); ?>:</strong> <?php echo e($invoice->due_date?->format('d M Y') ?: __('Not specified')); ?>

        </div>
        <p style="margin-top:22px;"><a href="<?php echo e(route('admin.garments.invoices.print', $invoice->id)); ?>" style="background:#4778c7;color:#fff;text-decoration:none;padding:10px 16px;border-radius:6px;"><?php echo e(__('View Invoice')); ?></a></p>
    </div>
</body>
</html>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\mail\invoice-email.blade.php ENDPATH**/ ?>