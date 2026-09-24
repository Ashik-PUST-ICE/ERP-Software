<?php $__env->startPush('title'); ?>
    <?php echo e(getOption('app_name', 'Autopost')); ?> - <?php echo e(getOption('landing_meta_title', 'AI-Powered Social Media Scheduling')); ?>

<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    <?php echo $__env->make('auto_posts.frontend.layouts.landing-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div id="smooth-wrapper">
        <div id="smooth-content">
            <main id="home">

                <?php echo $__env->make('auto_posts.frontend.partials.landing.hero', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('auto_posts.frontend.partials.landing.features', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('auto_posts.frontend.partials.landing.post-management', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('auto_posts.frontend.partials.landing.campaign', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('auto_posts.frontend.partials.landing.tools', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('auto_posts.frontend.partials.landing.pricing', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('auto_posts.frontend.partials.landing.testimonials', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('auto_posts.frontend.partials.landing.blog', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('auto_posts.frontend.partials.landing.faq', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('auto_posts.frontend.partials.landing.cta', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            </main>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('auto_posts.frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\frontend\index.blade.php ENDPATH**/ ?>