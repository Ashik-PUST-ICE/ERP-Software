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
                <h3 class="title"><?php echo e(__('Logo Settings')); ?></h3>
            </div>
            <form id="logo-settings-form" class="ajax" action="<?php echo e(route('super_admin.setting.application-settings.update')); ?>"
                    method="POST"
                    enctype="multipart/form-data" data-handler="commonResponseForModal">
                <?php echo csrf_field(); ?>
                <div class="row gy-4">
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                        <div class="single-logo">
                            <h4 class="logo-title"><?php echo e(__('App Preloader')); ?></h4>
                            <div class="logo-upload">
                                <div class="upload-img-box" onclick="document.getElementById('app_preloader').click()">
                                    <?php if(getOption('app_preloader')): ?>
                                        <img class="logo" src="<?php echo e(getSettingImage('app_preloader')); ?>" alt="preloader"/>
                                    <?php else: ?>
                                        <img class="logo" src="<?php echo e(asset('assets/images/logo-placehoder.png')); ?>" alt="logo placeholder"/>
                                    <?php endif; ?>
                                    <input type="file" name="app_preloader" id="app_preloader" accept="image/*" style="display:none" onchange="previewFile(this)"/>
                                </div>
                            </div>
                            <h5 class="size-recommended"><?php echo e(__('Recommended Size : 140 X 40')); ?></h5>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                        <div class="single-logo">
                            <h4 class="logo-title"><?php echo e(__('App Logo (White)')); ?></h4>
                            <div class="logo-upload">
                                <div class="upload-img-box" onclick="document.getElementById('app_logo').click()">
                                    <?php if(getOption('app_logo')): ?>
                                        <img class="logo" src="<?php echo e(getSettingImage('app_logo')); ?>" alt="logo white"/>
                                    <?php else: ?>
                                        <img class="logo" src="<?php echo e(asset('assets/images/logo-placehoder.png')); ?>" alt="logo placeholder"/>
                                    <?php endif; ?>
                                    <input type="file" name="app_logo" id="app_logo" accept="image/*" style="display:none" onchange="previewFile(this)"/>
                                </div>
                            </div>
                            <h5 class="size-recommended"><?php echo e(__('Recommended Size : 140 X 40')); ?></h5>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                        <div class="single-logo">
                            <h4 class="logo-title"><?php echo e(__('App Logo (Black)')); ?></h4>
                            <div class="logo-upload">
                                <div class="upload-img-box" onclick="document.getElementById('app_black_logo').click()">
                                    <?php if(getOption('app_black_logo')): ?>
                                        <img class="logo" src="<?php echo e(getSettingImage('app_black_logo')); ?>" alt="logo black"/>
                                    <?php else: ?>
                                        <img class="logo" src="<?php echo e(asset('assets/images/logo-placehoder.png')); ?>" alt="logo placeholder"/>
                                    <?php endif; ?>
                                    <input type="file" name="app_black_logo" id="app_black_logo" accept="image/*" style="display:none" onchange="previewFile(this)"/>
                                </div>
                            </div>
                            <h5 class="size-recommended"><?php echo e(__('Recommended Size : 140 X 40')); ?></h5>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                        <div class="single-logo">
                            <h4 class="logo-title"><?php echo e(__('App Fav Icon')); ?></h4>
                            <div class="logo-upload">
                                <div class="upload-img-box" onclick="document.getElementById('app_fav_icon').click()">
                                    <?php if(getOption('app_fav_icon')): ?>
                                        <img class="logo" src="<?php echo e(getSettingImage('app_fav_icon')); ?>" alt="favicon"/>
                                    <?php else: ?>
                                        <img class="logo" src="<?php echo e(asset('assets/images/logo-placehoder.png')); ?>" alt="logo placeholder"/>
                                    <?php endif; ?>
                                    <input type="file" name="app_fav_icon" id="app_fav_icon" accept="image/*" style="display:none" onchange="previewFile(this)"/>
                                </div>
                            </div>
                            <h5 class="size-recommended"><?php echo e(__('Recommended Size : 16 X 16')); ?></h5>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                        <div class="single-logo">
                            <h4 class="logo-title"><?php echo e(__('Login Left Image')); ?></h4>
                            <div class="logo-upload">
                                <div class="upload-img-box" onclick="document.getElementById('login_left_image').click()">
                                    <?php if(getOption('login_left_image')): ?>
                                        <img class="logo" src="<?php echo e(getSettingImage('login_left_image')); ?>" alt="login left image"/>
                                    <?php else: ?>
                                        <img class="logo" src="<?php echo e(asset('assets/images/logo-placehoder.png')); ?>" alt="logo placeholder"/>
                                    <?php endif; ?>
                                    <input type="file" name="login_left_image" id="login_left_image" accept="image/*" style="display:none" onchange="previewFile(this)"/>
                                </div>
                            </div>
                            <h5 class="size-recommended"><?php echo e(__('Recommended Size : 140 X 40')); ?></h5>
                        </div>
                    </div>
                </div>
            </form>
            <div class="btn-list">
                <button type="submit" form="logo-settings-form" class="primary-btn"><?php echo e(__('Save')); ?></button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('auto_posts.super_admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\super_admin\setting\general_settings\logo-settings.blade.php ENDPATH**/ ?>