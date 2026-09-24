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
                <h3 class="title"><?php echo e(__('Application Settings')); ?></h3>
            </div>
            <div class="primary-form">
                <form id="application-settings-form" class="ajax" action="<?php echo e(route('super_admin.setting.application-settings.update')); ?>"
                    method="POST" enctype="multipart/form-data" data-handler="settingCommonHandler">
                    <?php echo csrf_field(); ?>
                    <div class="row gy-4">
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="app_name" class="form-label"><?php echo e(__('App Name')); ?><span class="required">*</span></label>
                                <input type="text" id="app_name" name="app_name" value="<?php echo e(getOption('app_name')); ?>" class="form-control" placeholder="<?php echo e(__('App Name')); ?>" required>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="APP_URL" class="form-label"><?php echo e(__('App URL')); ?><span class="required">*</span></label>
                                <input type="text" id="APP_URL" name="APP_URL" value="<?php echo e(getOption('APP_URL')); ?>" class="form-control" placeholder="<?php echo e(__('App URL')); ?>">
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="app_email" class="form-label"><?php echo e(__('App Email')); ?><span class="required">*</span></label>
                                <input type="email" id="app_email" name="app_email" value="<?php echo e(getOption('app_email')); ?>" class="form-control" placeholder="<?php echo e(__('App Email')); ?>" required>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="app_contact_number" class="form-label"><?php echo e(__('App Contact Number')); ?><span class="required">*</span></label>
                                <input type="text" id="app_contact_number" name="app_contact_number" value="<?php echo e(getOption('app_contact_number')); ?>" class="form-control" placeholder="<?php echo e(__('App Contact Number')); ?>" required>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="app_location" class="form-label"><?php echo e(__('App Location')); ?><span class="required">*</span></label>
                                <input type="text" id="app_location" name="app_location" value="<?php echo e(getOption('app_location')); ?>" class="form-control" placeholder="<?php echo e(__('App Location')); ?>">
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="app_timezone" class="form-label"><?php echo e(__('Timezone')); ?><span class="required">*</span></label>
                                <select name="app_timezone" class="select form-control wide" id="app_timezone" required>
                                    <option value=""><?php echo e(__('Select Timezone')); ?></option>
                                    <?php $__currentLoopData = $timezones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $timezone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($timezone); ?>" <?php echo e($timezone == getOption('app_timezone') ? 'selected' : ''); ?>>
                                            <?php echo e($timezone); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="app_copyright" class="form-label"><?php echo e(__('App Copyright')); ?><span class="required">*</span></label>
                                <input type="text" id="app_copyright" name="app_copyright" value="<?php echo e(getOption('app_copyright')); ?>" class="form-control" placeholder="<?php echo e(__('App Copyright')); ?>">
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="app_developed" class="form-label"><?php echo e(__('Developed By')); ?><span class="required">*</span></label>
                                <input type="text" id="app_developed" name="app_developed" value="<?php echo e(getOption('app_developed')); ?>" class="form-control" placeholder="<?php echo e(__('Developed By')); ?>">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="btn-list">
                <button type="submit" form="application-settings-form" class="primary-btn"><?php echo e(__('Save')); ?></button>
            </div>

            <!-- Currency Settings Section -->
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('auto_posts.super_admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\super_admin\setting\general_settings\application-settings.blade.php ENDPATH**/ ?>