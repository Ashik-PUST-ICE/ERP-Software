<?php $__env->startPush('title'); ?>
<?php echo e($title ?? __('Timezone Settings')); ?>

<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
<div class="section-title">
    <h2 class="title"><?php echo e(__($title ?? 'Timezone Settings')); ?></h2>
</div>
<div class="section-wrap">
    <div class="form-wrapper">
        <div class="bg-white bd-half bd-c-ebedf0 bd-ra-25 p-30">
            <p class="text-muted mb-4"><?php echo e(__('Set the default timezone for scheduling posts and campaigns. All scheduled times will be stored and processed using this timezone.')); ?></p>
            <form id="timezone-settings-form" class="ajax primary-form" method="POST" action="<?php echo e(route('admin.settings.timezone.update')); ?>" data-handler="commonResponseWithPageLoad">
                <?php echo csrf_field(); ?>
                <div class="row gy-4">
                    <div class="col-xl-8 col-lg-8 col-md-12">
                        <div class="form-group">
                            <label for="app_timezone" class="form-label"><?php echo e(__('Application Timezone')); ?> <span class="required">*</span></label>
                            <select name="app_timezone" id="app_timezone" class="form-control select wide" required>
                                <?php $__currentLoopData = $timezones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($tz); ?>" <?php echo e(($app_timezone ?? 'UTC') === $tz ? 'selected' : ''); ?>><?php echo e($tz); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <small class="form-text text-muted"><?php echo e(__('This timezone is used when creating and displaying scheduled posts and campaign times.')); ?></small>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="primary-btn"><?php echo e(__('Save Timezone')); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('auto_posts.admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\admin\settings\timezone-settings.blade.php ENDPATH**/ ?>