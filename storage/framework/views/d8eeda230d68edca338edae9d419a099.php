<!DOCTYPE html>
<html class="no-js" lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e(getOption('app_name')); ?> - <?php echo $__env->yieldPushContent('title' ?? ''); ?></title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />

    <?php if (! empty(trim($__env->yieldContent('meta')))): ?>
    <?php echo $__env->yieldPushContent('meta'); ?>
    <?php else: ?>
    <?php
    $metaData = getMeta('home');
    ?>
    <meta name="description" content="<?php echo e(__($metaData['meta_description']) ?? getOption('app_name')); ?>">
    <meta name="keywords" content="<?php echo e(__($metaData['meta_keyword'])); ?>">
    <?php endif; ?>

    <!-- Place favicon.ico in the root directory -->
    <link rel="icon" href="<?php echo e(getSettingImage('app_fav_icon')); ?>" type="image/png" sizes="16x16">
    <link rel="shortcut icon" href="<?php echo e(getSettingImage('app_fav_icon')); ?>" type="image/x-icon">
    <!-- fonts file -->
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <!-- css file  -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/plugins.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/scss/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/responsive.css')); ?>">
    <script src="<?php echo e(asset('assets/js/modernizr-3.11.2.min.js')); ?>"></script>
    <?php if(!empty(getOption('google_recaptcha_status')) && getOption('google_recaptcha_status') == 1 && getOption('google_recaptcha_site_key')): ?>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <?php endif; ?>
    <?php echo $__env->yieldPushContent('style'); ?>

    <style>
        <?php echo e(getOption('custom_css')); ?>

    </style>

    <?php if(getOption('google_analytics_status', 0)): ?>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo e(getOption('google_analytics_tracking_id')); ?>">
    </script>
    <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());
    gtag('config', "<?php echo e(getOption('google_analytics_tracking_id')); ?>");
    </script>
    <?php endif; ?>
</head>

<body class="<?php echo e(selectedLanguage()->rtl == 1 ? 'direction-rtl' : 'direction-ltr'); ?>">
    <input type="hidden" id="lang_code" value="<?php echo e(session('local')); ?>">
    <?php if(getOption('app_preloader_status', 0) == STATUS_ACTIVE): ?>
    <div class="preloader">
        <div id="preloader_status">
            <img src="<?php echo e(getSettingImage('app_preloader')); ?>" alt="<?php echo e(getOption('app_name')); ?>" />
        </div>
    </div>
    <?php endif; ?>

    <?php echo $__env->yieldContent('content'); ?>

    <!-- js file  -->
    <script src="<?php echo e(asset('assets/js/jquery-3.7.1.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/plugins.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/main.js')); ?>"></script>
    <script src="<?php echo e(asset('common/js/common.js')); ?>?ver=<?php echo e(env('VERSION' ,0)); ?>"></script>

    <?php echo $__env->yieldPushContent('script'); ?>

    <script>
    var currencySymbol = "<?php echo e(getCurrencySymbol()); ?>";
    var currencyPlacement = "<?php echo e(getCurrencyPlacement()); ?>";

    <?php if(Session::has('success')): ?>
    toastr.success("<?php echo e(session('success')); ?>");
    <?php endif; ?>
    <?php if(Session::has('error')): ?>
    toastr.error("<?php echo e(session('error')); ?>");
    <?php endif; ?>
    <?php if(Session::has('info')): ?>
    toastr.info("<?php echo e(session('info')); ?>");
    <?php endif; ?>
    <?php if(Session::has('warning')): ?>
    toastr.warning("<?php echo e(session('warning')); ?>");
    <?php endif; ?>

    <?php if(@$errors->any()): ?>
    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    toastr.error("<?php echo e($error); ?>");
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
    </script>
</body>

</html><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auth\layouts\app.blade.php ENDPATH**/ ?>