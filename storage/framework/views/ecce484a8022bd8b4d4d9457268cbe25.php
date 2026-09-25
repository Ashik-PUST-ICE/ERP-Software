<div class="settings-page-left">
    <nav class="settings-menu">
        <ul>
            <li>
                <a href="<?php echo e(route('super_admin.setting.frontend.landing-page.hero')); ?>"
                    class="<?php echo e(isset($subLandingPageActiveClass) && $subLandingPageActiveClass == 'active' ? 'active' : (request()->routeIs('super_admin.setting.frontend.landing-page*') ? 'active' : '')); ?>">
                    <?php echo e(__('Landing Page')); ?> <i class="fa-solid fa-angle-right"></i>
                </a>
            </li>
        </ul>
    </nav>
</div>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\super_admin\setting\partials\frontend-sidebar.blade.php ENDPATH**/ ?>