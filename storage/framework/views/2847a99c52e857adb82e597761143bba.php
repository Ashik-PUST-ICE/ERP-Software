<div class="settings-page-left">
    <nav class="settings-menu">
        <ul>
            <li>
                <a href="<?php echo e(route('super_admin.setting.application-settings')); ?>"
                    class="<?php echo e(isset($subApplicationSettingActiveClass) && $subApplicationSettingActiveClass == 'active' ? 'active' : ''); ?>">
                    <?php echo e(__('App Settings')); ?> <i class="fa-solid fa-angle-right"></i>
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('super_admin.setting.logo-settings')); ?>"
                    class="<?php echo e(isset($subLogoSettingActiveClass) && $subLogoSettingActiveClass == 'active' ? 'active' : ''); ?>">
                    <?php echo e(__('Logo Settings')); ?> <i class="fa-solid fa-angle-right"></i>
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('super_admin.setting.color-settings')); ?>"
                    class="<?php echo e(isset($subColorSettingActiveClass) && $subColorSettingActiveClass == 'active' ? 'active' : ''); ?>">
                    <?php echo e(__('Color Settings')); ?> <i class="fa-solid fa-angle-right"></i>
                </a>
            </li>

            <li>
                <a href="<?php echo e(route('super_admin.setting.storage.index')); ?>"
                    class="<?php echo e(isset($subStorageSettingActiveClass) && $subStorageSettingActiveClass == 'active' ? 'active' : ''); ?>">
                    <?php echo e(__('Storage Settings')); ?> <i class="fa-solid fa-angle-right"></i>
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('super_admin.setting.maintenance')); ?>"
                    class="<?php echo e(isset($subMaintenanceModeActiveClass) && $subMaintenanceModeActiveClass == 'active' ? 'active' : ''); ?>">
                    <?php echo e(__('Maintenance Mode')); ?> <i class="fa-solid fa-angle-right"></i>
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('super_admin.setting.languages.index')); ?>"
                    class="<?php echo e(isset($subLanguageSettingActiveClass) && $subLanguageSettingActiveClass == 'active' ? 'active' : (request()->routeIs('super_admin.setting.languages.*') ? 'active' : '')); ?>">
                    <?php echo e(__('Language Settings')); ?> <i class="fa-solid fa-angle-right"></i>
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('super_admin.setting.currencies.index')); ?>"
                    class="<?php echo e(isset($subCurrencySettingActiveClass) && $subCurrencySettingActiveClass == 'active' ? 'active' : (request()->routeIs('super_admin.setting.currencies.*') ? 'active' : '')); ?>">
                    <?php echo e(__('Currency Settings')); ?> <i class="fa-solid fa-angle-right"></i>
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('super_admin.setting.gateway.index')); ?>"
                    class="<?php echo e(isset($subGatewaySettingActiveClass) && $subGatewaySettingActiveClass == 'active' ? 'active' : (request()->routeIs('super_admin.setting.gateway.*') ? 'active' : '')); ?>">
                    <?php echo e(__('Gateway Settings')); ?> <i class="fa-solid fa-angle-right"></i>
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('super_admin.setting.cache-settings')); ?>"
                    class="<?php echo e(isset($subCacheActiveClass) && $subCacheActiveClass == 'active' ? 'active' : ''); ?>">
                    <?php echo e(__('Cache Settings')); ?> <i class="fa-solid fa-angle-right"></i>
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('super_admin.setting.ai-settings')); ?>"
                    class="<?php echo e(isset($subAISettingActiveClass) && $subAISettingActiveClass == 'active' ? 'active' : ''); ?>">
                    <?php echo e(__('AI Settings')); ?> <i class="fa-solid fa-angle-right"></i>
                </a>
            </li>

        </ul>
    </nav>
</div><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\super_admin\setting\partials\general-sidebar.blade.php ENDPATH**/ ?>