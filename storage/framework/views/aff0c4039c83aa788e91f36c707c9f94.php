<?php $__env->startPush('title'); ?><?php echo e($page->en_title); ?> - <?php echo e(getOption('app_name', 'Autopost')); ?><?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<!-- Header Area Start -->
<header class="lp-header">
    <div class="container">
        <div class="nav-wrapper">

            <!-- Logo -->
            <div class="logo">
                <a href="<?php echo e(url('/')); ?>">
                    <img src="<?php echo e(getSettingImage('app_black_logo')); ?>" alt="<?php echo e(getOption('app_name', 'PostBot')); ?>">
                </a>
            </div>

            <!-- Navigation -->
            <nav class="nav-menu">
                <div class="mobile-menu-heading">
                    <div class="logo">
                        <a href="<?php echo e(url('/')); ?>">
                            <img src="<?php echo e(getSettingImage('app_black_logo')); ?>" alt="<?php echo e(getOption('app_name', 'PostBot')); ?>">
                        </a>
                    </div>
                    <div class="close-button">
                        <img src="<?php echo e(asset('assets/images/close-menu.svg')); ?>" alt="close menu">
                    </div>
                </div>
                <?php
                $navPages = $navPages ?? collect([]);
                ?>
                <ul>
                    <li>
                        <a href="<?php echo e(url('/')); ?>"><?php echo e(__('Home')); ?></a>
                    </li>
                    <li>
                        <a href="<?php echo e(url('/#feature-section')); ?>"><?php echo e(__('Features')); ?></a>
                    </li>
                    <li>
                        <a href="<?php echo e(url('/#blog-section')); ?>"><?php echo e(__('Blog')); ?></a>
                    </li>
                    <li>
                        <a href="<?php echo e(url('/#faq-section')); ?>"><?php echo e(__('FAQ')); ?></a>
                    </li>
                    <li>
                        <a href="<?php echo e(url('/#pricing-section')); ?>"><?php echo e(__('Pricing')); ?></a>
                    </li>

                    <?php if($navPages->count()): ?>
                    <li class="has-dropdown">
                        <a href="javascript:void(0)"><?php echo e(__('Pages')); ?></a>
                        <ul class="dropdown">
                            <?php $__currentLoopData = $navPages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                                <a href="<?php echo e(url($p->slug)); ?>"><?php echo e(__($p->en_title)); ?></a>
                            </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>

            <!-- Actions -->
            <div class="nav-actions">
                <?php if(auth()->guard()->guest()): ?>
                <a href="<?php echo e(route('login')); ?>" class="primary-btn bg-white"><?php echo e(__('Sign In')); ?></a>
                <a href="<?php echo e(route('register')); ?>" class="primary-btn"><?php echo e(__('Sign Up')); ?></a>
                <?php else: ?>
                <a href="<?php echo e(route('login')); ?>" class="primary-btn bg-white">
                    <?php echo e(__('Dashboard')); ?>

                </a>
                <?php endif; ?>
            </div>

            <!-- Mobile Toggle -->
            <div class="menu-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
</header>
<!-- Header Area End -->

<main>
    <!-- Page Content Area Start -->
    <section class="lp-dynamic-page lp-section-padding" style="padding-top: 200px;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h1 class="lp-title" data-aos="fade-up" data-aos-delay="200" data-aos-duration="1000">
                        <?php echo e(__($page->en_title)); ?>

                    </h1>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="dynamic-page-content" data-aos="fade-up" data-aos-delay="300" data-aos-duration="1000">
                        <?php echo $page->en_description; ?>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Page Content Area End -->
</main>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('auto_posts.frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\frontend\page.blade.php ENDPATH**/ ?>