<?php $__env->startPush('title'); ?>
<?php echo e(__($title)); ?>

<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>

<div class="text-center biling-submit-response-page">
    <?php if($success == true): ?>
    <div class="d-flex justify-content-center pb-30">
        <img src="<?php echo e(asset('assets/images/successful-message.png')); ?>" alt="" />
    </div>
    <h4 class="title"><?php echo e(__('Successful')); ?></h4>
    <p class="subtitle"><?php echo e($message); ?></p>
    <?php else: ?>
    <div class="d-flex justify-content-center pb-30 mt-20 mb-20">
        <img src="<?php echo e(asset('assets/images/failed-message.png')); ?>" alt="" />
    </div>
    <h4 class="title"><?php echo e(__('Failed')); ?></h4>
    <p class="subtitle"><?php echo e($message); ?></p>
    <?php endif; ?>
    <a href="<?php echo e(route('admin.billings.index')); ?>" class="primary-btn">
        <?php echo e($success ? __('Back to Billing') : __('Back to Billing')); ?>

    </a>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('auto_posts.admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\admin\checkout-success.blade.php ENDPATH**/ ?>