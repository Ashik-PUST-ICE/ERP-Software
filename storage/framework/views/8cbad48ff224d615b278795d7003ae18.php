<!DOCTYPE html>
<html class="no-js" lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<?php echo $__env->make('auto_posts.user.layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<body class="<?php echo e(selectedLanguage()->rtl == 1 ? 'direction-rtl' : 'direction-ltr'); ?>">
<input type="hidden" id="lang_code" value="<?php echo e(session('local')); ?>">
<div class="overflow-x-hidden">
    <?php if(getOption('app_preloader_status', 0) == STATUS_ACTIVE): ?>
        <div class="preloader">
            <div id="preloader_status">
                <img src="<?php echo e(getSettingImage('app_preloader')); ?>" alt="<?php echo e(getOption('app_name')); ?>"/>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="main-wrap">
        <!-- Sidebar -->
        <?php echo $__env->make('auto_posts.user.layouts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <?php echo $__env->make('auto_posts.user.layouts.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <!-- Content -->
            <div class="content-wrapper">
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </div>
    </main>
</div>
<?php if(!empty(getOption('cookie_status')) && getOption('cookie_status') == STATUS_ACTIVE): ?>
    <div class="cookie-consent-wrap shadow-lg">
        <?php echo $__env->make('cookie-consent::index', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
<?php endif; ?>
<?php echo $__env->make('auto_posts.user.layouts.script', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>

</html>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\user\layouts\app.blade.php ENDPATH**/ ?>