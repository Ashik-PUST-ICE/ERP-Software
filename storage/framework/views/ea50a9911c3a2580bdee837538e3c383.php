<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo e($title); ?></title>
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
        <div><h1><?php echo e($title); ?></h1><div class="muted"><?php echo e(config('app.name')); ?></div></div>
        <div class="muted"><?php echo e(__('Generated')); ?>: <?php echo e(now()->format('d M Y, h:i A')); ?></div>
    </div>
    <?php if(count($rows)): ?>
        <table>
            <thead><tr><?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><th><?php echo e(__($column)); ?></th><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></tr></thead>
            <tbody><?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><tr><?php $__currentLoopData = $row; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><td><?php echo e($value); ?></td><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></tbody>
        </table>
    <?php else: ?>
        <div class="empty"><?php echo e(__('No records found')); ?></div>
    <?php endif; ?>
    <script>window.onload = function () { window.print(); };</script>
</body>
</html>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\hrm\reports\print.blade.php ENDPATH**/ ?>