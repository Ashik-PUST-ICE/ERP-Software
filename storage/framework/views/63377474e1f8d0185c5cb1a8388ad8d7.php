<!DOCTYPE html>
<html class="no-js" lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<?php echo $__env->make('auto_posts.admin.layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<style>
    /* Loading Overlay Styles */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .loading-modal {
        background: #fff;
        padding: 30px 50px;
        border-radius: 10px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }
    
    .loading-content-wrap {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    .loading-spinner-wrap {
        margin-top: 20px;
    }
    
    .loading-spinner {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .loading-title {
        margin: 0;
        font-size: 18px;
        color: #333;
        font-weight: 600;
    }
    
    .loading-subtitle {
        margin: 10px 0 0;
        color: #666;
        font-size: 14px;
    }
</style>

<body class="<?php echo e(selectedLanguage()->rtl == 1 ? 'direction-rtl' : 'direction-ltr'); ?>">
    <input type="hidden" id="lang_code" value="<?php echo e(session('local')); ?>">
    <div class="overflow-x-hidden">
        <?php if(getOption('app_preloader_status', 0) == STATUS_ACTIVE): ?>
        <div id="preloader">
            <div id="preloader_status">
                <img src="<?php echo e(getSettingImage('app_preloader')); ?>" alt="<?php echo e(getOption('app_name')); ?>" />
            </div>
        </div>
        <?php endif; ?>

        <!-- Main Content -->
        <div class="zMain-wrap">
            <!-- Sidebar -->
            <?php echo $__env->make('auto_posts.admin.layouts.admin-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <!-- Main Content -->
            <div class="zMainContent">
                <!-- Header -->
                <?php echo $__env->make('auto_posts.admin.layouts.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <!-- Content -->
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </div>
    </div>
    <?php if(!empty(getOption('cookie_status')) && getOption('cookie_status') == STATUS_ACTIVE): ?>
    <div class="cookie-consent-wrap shadow-lg">
        <?php echo $__env->make('cookie-consent::index', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
    <?php endif; ?>

    <?php echo $__env->make('auto_posts.admin.layouts.ai-assistant', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('auto_posts.admin.layouts.queue-assistant', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!-- Loading Overlay -->
    <div id="create-post-loading-overlay" class="loading-overlay" style="display:none;">
        <div class="loading-modal">
            <div class="loading-content-wrap">
                <h3 id="loading-title" class="loading-title">Creating post</h3>
                <p id="loading-subtitle" class="loading-subtitle">Please wait while we create your post.</p>
                <div class="loading-spinner-wrap">
                    <svg class="loading-spinner" width="40" height="40" viewBox="0 0 50 50">
                        <circle cx="25" cy="25" r="20" fill="none" stroke="#ddd" stroke-width="5"></circle>
                        <circle cx="25" cy="25" r="20" fill="none" stroke="#FF4F02" stroke-width="5" stroke-dasharray="90" stroke-dashoffset="60" stroke-linecap="round"></circle>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <?php echo $__env->make('auto_posts.admin.layouts.script', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>

</html>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\admin\layouts\master.blade.php ENDPATH**/ ?>