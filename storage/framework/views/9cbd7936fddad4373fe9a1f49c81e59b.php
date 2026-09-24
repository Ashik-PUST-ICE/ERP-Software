<?php $__env->startPush('title'); ?>
    <?php echo e($title); ?>

<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
<div class="section-title">
    <h2 class="title"><?php echo e($title); ?></h2>
</div>
<div class="settings-page-area">
    <?php echo $__env->make('auto_posts.super_admin.setting.partials.general-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div class="settings-page-right">
        <div class="section-wrap">
            <div class="section-inner-title">
                <h3 class="title"><?php echo e(__($title)); ?></h3>
            </div>
            <div class="primary-form">
                <div class="row gy-4">
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                        <div class="cache-action-card">
                            <div class="cache-icon">
                                <i class="fa-solid fa-eye"></i>
                            </div>
                            <h4 class="cache-title"><?php echo e(__('Clear View Cache')); ?></h4>
                            <p class="cache-description"><?php echo e(__('Clear all compiled view files')); ?></p>
                            <a href="<?php echo e(route('super_admin.setting.cache-update', 1)); ?>" 
                               class="primary-btn cache-action-btn">
                                <i class="fa-solid fa-trash-can me-2"></i><?php echo e(__('Clear Cache')); ?>

                            </a>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                        <div class="cache-action-card">
                            <div class="cache-icon">
                                <i class="fa-solid fa-route"></i>
                            </div>
                            <h4 class="cache-title"><?php echo e(__('Clear Route Cache')); ?></h4>
                            <p class="cache-description"><?php echo e(__('Clear all route cache files')); ?></p>
                            <a href="<?php echo e(route('super_admin.setting.cache-update', 2)); ?>" 
                               class="primary-btn cache-action-btn">
                                <i class="fa-solid fa-trash-can me-2"></i><?php echo e(__('Clear Cache')); ?>

                            </a>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                        <div class="cache-action-card">
                            <div class="cache-icon">
                                <i class="fa-solid fa-gear"></i>
                            </div>
                            <h4 class="cache-title"><?php echo e(__('Clear Config Cache')); ?></h4>
                            <p class="cache-description"><?php echo e(__('Clear all configuration cache files')); ?></p>
                            <a href="<?php echo e(route('super_admin.setting.cache-update', 3)); ?>" 
                               class="primary-btn cache-action-btn">
                                <i class="fa-solid fa-trash-can me-2"></i><?php echo e(__('Clear Cache')); ?>

                            </a>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                        <div class="cache-action-card">
                            <div class="cache-icon">
                                <i class="fa-solid fa-broom"></i>
                            </div>
                            <h4 class="cache-title"><?php echo e(__('Application Clear Cache')); ?></h4>
                            <p class="cache-description"><?php echo e(__('Clear all application cache files')); ?></p>
                            <a href="<?php echo e(route('super_admin.setting.cache-update', 4)); ?>" 
                               class="primary-btn cache-action-btn">
                                <i class="fa-solid fa-trash-can me-2"></i><?php echo e(__('Clear Cache')); ?>

                            </a>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                        <div class="cache-action-card">
                            <div class="cache-icon">
                                <i class="fa-solid fa-link"></i>
                            </div>
                            <h4 class="cache-title"><?php echo e(__('Storage Link')); ?></h4>
                            <p class="cache-description"><?php echo e(__('Create symbolic link for storage')); ?></p>
                            <a href="<?php echo e(route('super_admin.setting.cache-update', 5)); ?>" 
                               class="primary-btn cache-action-btn">
                                <i class="fa-solid fa-link me-2"></i><?php echo e(__('Create Link')); ?>

                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('style'); ?>
<link rel="stylesheet" href="<?php echo e(asset('super_admin/css/cache-settings.css')); ?>">
<?php $__env->stopPush(); ?>




<?php echo $__env->make('auto_posts.super_admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\super_admin\setting\cache-settings.blade.php ENDPATH**/ ?>