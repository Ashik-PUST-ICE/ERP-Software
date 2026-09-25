<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<body style="font-family:Arial,sans-serif;line-height:1.6;color:#252525;">
    <div style="max-width:680px;margin:0 auto;padding:24px;">
        <h2 style="margin-top:0;"><?php echo e(getOption('app_name')); ?></h2>
        <div><?php echo nl2br(e($mailMessage)); ?></div>
        <hr style="border:0;border-top:1px solid #e5e7eb;margin:24px 0;">
        <small style="color:#6b7280;"><?php echo e(getOption('app_name')); ?></small>
    </div>
</body>
</html>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\mail\admin-manual-email.blade.php ENDPATH**/ ?>