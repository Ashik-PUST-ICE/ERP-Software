<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo e($title); ?></title>
    <style>
        body{font-family:Arial,sans-serif;color:#1f2937;margin:28px;font-size:12px}.toolbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:22px}.toolbar button{border:0;background:#4778c7;color:#fff;padding:9px 14px;border-radius:5px;cursor:pointer}.brand h1{margin:0 0 5px;font-size:22px}.brand p{margin:0;color:#64748b}.report-date{color:#64748b}.summary{display:flex;gap:10px;margin:18px 0}.summary div{border:1px solid #e5e7eb;border-radius:6px;padding:10px 14px;min-width:110px}.summary strong,.summary span{display:block}.summary strong{font-size:18px}.summary span{color:#64748b;margin-top:3px}table{width:100%;border-collapse:collapse}th{background:#f1f5f9;color:#475569;text-align:left;font-size:10px;text-transform:uppercase}th,td{border:1px solid #dbe2ea;padding:9px 8px}td.amount,th.amount{text-align:right}@media print{body{margin:12px}.toolbar button{display:none}.summary div{padding:7px 10px}}
    </style>
</head>
<body>
    <div class="toolbar"><div class="brand"><h1><?php echo e(__('Commercial Invoice Report')); ?></h1><p><?php echo e(getOption('app_name')); ?> · <?php echo e(now()->format('d M Y, h:i A')); ?></p></div><button onclick="window.print()"><?php echo e(__('Print')); ?></button></div>
    <div class="summary"><div><strong><?php echo e($invoices->count()); ?></strong><span><?php echo e(__('Total')); ?></span></div><div><strong><?php echo e($invoices->where('status','paid')->count()); ?></strong><span><?php echo e(__('Paid')); ?></span></div><div><strong><?php echo e($invoices->where('status','overdue')->count()); ?></strong><span><?php echo e(__('Overdue')); ?></span></div></div>
    <table><thead><tr><th><?php echo e(__('Invoice')); ?></th><th><?php echo e(__('Buyer')); ?></th><th><?php echo e(__('Issue Date')); ?></th><th><?php echo e(__('Due Date')); ?></th><th class="amount"><?php echo e(__('Total')); ?></th><th class="amount"><?php echo e(__('Paid')); ?></th><th><?php echo e(__('Status')); ?></th></tr></thead><tbody>
    <?php $__empty_1 = true; $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><tr><td><?php echo e($invoice->invoice_number); ?></td><td><?php echo e($invoice->order?->buyer?->company_name ?: '-'); ?></td><td><?php echo e($invoice->issue_date?->format('d M Y') ?: '-'); ?></td><td><?php echo e($invoice->due_date?->format('d M Y') ?: '-'); ?></td><td class="amount"><?php echo e($invoice->currency); ?> <?php echo e(number_format((float) $invoice->total_amount, 2)); ?></td><td class="amount"><?php echo e($invoice->currency); ?> <?php echo e(number_format((float) $invoice->paid_amount, 2)); ?></td><td><?php echo e(__(ucwords(str_replace('_',' ',$invoice->status)))); ?></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="7" style="text-align:center"><?php echo e(__('No invoices found.')); ?></td></tr><?php endif; ?>
    </tbody></table>
</body>
</html>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\garments\invoices\print-report.blade.php ENDPATH**/ ?>