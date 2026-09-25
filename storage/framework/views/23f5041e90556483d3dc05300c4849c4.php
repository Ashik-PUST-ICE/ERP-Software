<header class="lp-header">
    <div class="container">
        <div class="nav-wrapper">

            <div class="logo">
                <a href="<?php echo e(url('/')); ?>">
                    <img src="<?php echo e(getSettingImage('app_black_logo')); ?>" alt="<?php echo e(getOption('app_name', 'PostBot')); ?>">
                </a>
            </div>

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
                $landingNavLinks = $landingNavLinks ?? [
                ['label' => __('Home'), 'url' => url('/#home')],
                ['label' => __('Features'), 'url' => url('/#feature-section')],
                ['label' => __('Blog'), 'url' => url('/#blog-section')],
                ['label' => __('FAQ'), 'url' => url('/#faq-section')],
                ['label' => __('Pricing'), 'url' => url('/#pricing-section')],
                ];
                $staticMenus = $staticMenus ?? collect([]);
                $navPages = $navPages ?? collect([]);
                ?>

                <ul>
                    <?php $__currentLoopData = $landingNavLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><a href="<?php echo e($item['url']); ?>"><?php echo e($item['label']); ?></a></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <?php $__currentLoopData = $staticMenus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li>
                        <a href="<?php echo e($menu->url ? url($menu->url) : url($menu->slug ?? '')); ?>">
                            <?php echo e(__($menu->name)); ?>

                        </a>
                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <?php if($navPages->count()): ?>
                    <li class="has-dropdown">
                        <a href="javascript:void(0)"><?php echo e(__('Pages')); ?></a>
                        <ul class="dropdown">
                            <?php $__currentLoopData = $navPages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><a href="<?php echo e(url($p->slug)); ?>"><?php echo e(__($p->en_title)); ?></a></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>

            <div class="nav-actions">
                <?php if(auth()->guard()->guest()): ?>
                <a href="<?php echo e(route('login')); ?>" class="primary-btn bg-white"><?php echo e(__('Sign In')); ?></a>
                <a href="<?php echo e(route('register')); ?>" class="primary-btn"><?php echo e(__('Sign Up')); ?></a>
                <?php else: ?>
                <?php if(auth()->user()->role == USER_ROLE_SUPER_ADMIN): ?>
                <a href="<?php echo e(route('super_admin.dashboard')); ?>" class="primary-btn bg-white"><?php echo e(__('Dashboard')); ?></a>
                <?php else: ?>
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="primary-btn bg-white"><?php echo e(__('Dashboard')); ?></a>
                <?php endif; ?>
                <?php endif; ?>
            </div>

            <div class="menu-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>

        </div>
    </div>
</header>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\frontend\layouts\landing-nav.blade.php ENDPATH**/ ?>