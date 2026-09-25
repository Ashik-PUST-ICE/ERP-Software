<div class="settings-page-left">
    <nav class="settings-menu">
        <ul>
            <li>
                <a href="<?php echo e(route('super_admin.setting.frontend.landing-page.hero')); ?>"
                    class="<?php echo e(isset($subHeroSectionActiveClass) && $subHeroSectionActiveClass == 'active' ? 'active' : (request()->routeIs('super_admin.setting.frontend.landing-page.hero') ? 'active' : '')); ?>">
                    <?php echo e(__('Hero Section')); ?> <i class="fa-solid fa-angle-right"></i>
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('super_admin.setting.frontend.landing-page')); ?>"
                    class="<?php echo e(isset($subLandingOtherActiveClass) && $subLandingOtherActiveClass == 'active' ? 'active' : (request()->routeIs('super_admin.setting.frontend.landing-page') && !request()->routeIs('super_admin.setting.frontend.landing-page.hero') ? 'active' : '')); ?>">
                    <?php echo e(__('Section Titles, CTA & Images')); ?> <i class="fa-solid fa-angle-right"></i>
                </a>
            </li>
        </ul>
    </nav>
</div>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\super_admin\setting\partials\landing-sidebar.blade.php ENDPATH**/ ?>