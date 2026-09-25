<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    <title><?php echo e(getOption('app_name')); ?> - <?php echo $__env->yieldPushContent('title' ?? ''); ?></title>

    <?php if (! empty(trim($__env->yieldContent('meta')))): ?>
        <?php echo $__env->yieldPushContent('meta'); ?>
    <?php else: ?>
        <?php
            $metaData = getMeta('home');
        ?>

        <meta name="description" content="<?php echo e(__($metaData['meta_description']) ?? getOption('app_name')); ?>">
        <meta name="keywords" content="<?php echo e(__($metaData['meta_keyword'])); ?>">

        <!-- Open Graph meta tags for social sharing -->
        <meta property="og:type" content="<?php echo e(__('Autopost')); ?>">
        <meta property="og:title" content="<?php echo e(__($metaData['meta_title']) ?? getOption('app_name')); ?>">
        <meta property="og:description" content="<?php echo e(__($metaData['meta_description']) ?? getOption('app_name')); ?>">
        <meta property="og:image" content="<?php echo e(__($metaData['og_image']) ?? getSettingImage('app_logo')); ?>">
        <meta property="og:url" content="<?php echo e(url()->current()); ?>">

        <meta property="og:site_name" content="<?php echo e(__(getOption('app_name'))); ?>">

        <!-- Twitter Card meta tags for Twitter sharing -->
        <meta name="twitter:card" content="<?php echo e(__('Autopost')); ?>">
        <meta name="twitter:title" content="<?php echo e(__($metaData['meta_title']) ?? getOption('app_name')); ?>">
        <meta name="twitter:description" content="<?php echo e(__($metaData['meta_description']) ?? getOption('app_name')); ?>">
        <meta name="twitter:image" content="<?php echo e(__($metaData['og_image']) ?? getSettingImage('app_logo')); ?>">

        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>"/>
    <?php endif; ?>

    <!-- Place favicon.ico in the root directory -->
    <link rel="icon" href="<?php echo e(getSettingImage('app_fav_icon')); ?>" type="image/png" sizes="16x16">
    <link rel="shortcut icon" href="<?php echo e(getSettingImage('app_fav_icon')); ?>" type="image/x-icon">
    <link rel="shortcut icon" href="<?php echo e(getSettingImage('app_fav_icon')); ?>">
    <!-- fonts file -->
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter+Tight:wght@100;200;300;400;500;600;700;800;900&family=Nunito:wght@200;300;400;500;600;700;800;900;1000&display=swap"
        rel="stylesheet"/>
    <!-- css file  -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap.min.css')); ?>"/>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/swiper-bundle.min.css')); ?>"/>
    <link rel="stylesheet" href="<?php echo e(asset('assets/scss/style.css')); ?>"/>
    <?php echo $__env->make('auto_posts.frontend.layouts.dynamic-color', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php echo $__env->yieldPushContent('style'); ?>

    <style>
        <?php echo e(getOption('custom_css')); ?>

    </style>

    <?php if(getOption('google_analytics_status', 0)): ?>
        <!-- Google tag (gtag.js) -->
        <script async
                src="https://www.googletagmanager.com/gtag/js?id=<?php echo e(getOption('google_analytics_tracking_id')); ?>"></script>
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
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\frontend\layouts\header.blade.php ENDPATH**/ ?>