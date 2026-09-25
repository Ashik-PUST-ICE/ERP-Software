<!DOCTYPE html>
<html class="no-js" lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" dir="<?php echo e(selectedLanguage()->rtl == 1 ? 'rtl' : 'ltr'); ?>">

<?php echo $__env->make('auto_posts.super_admin.layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<body class="<?php echo e(selectedLanguage()->rtl == 1 ? 'direction-rtl' : 'direction-ltr'); ?>">
    <input type="hidden" id="lang_code" value="<?php echo e(session('local')); ?>">
    <!-- Preloader Start -->
    <?php if(getOption('app_preloader_status', 0) == STATUS_ACTIVE): ?>
        <div class="preloader">
            <div id="preloader_status">
                <img src="<?php echo e(getSettingImage('app_preloader')); ?>" alt="<?php echo e(getOption('app_name')); ?>"/>
            </div>
        </div>
    <?php endif; ?>
    <!-- Preloader End -->


    <!-- Main Content -->
    <main class="main-wrap">
        <!-- Sidebar -->
        <?php echo $__env->make('auto_posts.super_admin.layouts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <!-- Sticky Header Area Start -->
            
            <?php echo $__env->make('auto_posts.super_admin.layouts.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <!-- Sticky Header Area End -->
            <!-- Content -->
            <div class="content-wrapper">
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </div>
    </main>

    <!-- <div class="overflow-x-hidden">
    </div> -->
    <?php if(!empty(getOption('cookie_status')) && getOption('cookie_status') == STATUS_ACTIVE): ?>
        <div class="cookie-consent-wrap shadow-lg">
            <?php echo $__env->make('cookie-consent::index', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    <?php endif; ?>
    <?php echo $__env->make('auto_posts.super_admin.layouts.script', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>

</html>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views/auto_posts/super_admin/layouts/app.blade.php ENDPATH**/ ?>