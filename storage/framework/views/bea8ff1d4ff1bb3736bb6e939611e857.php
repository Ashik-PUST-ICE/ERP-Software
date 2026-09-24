<?php $__env->startPush('admin-style'); ?>
<link rel="stylesheet" href="<?php echo e(asset('admin/styles/main.css')); ?>">
<?php $__env->stopPush(); ?>
<?php $__env->startPush('title'); ?>
<?php echo e($title); ?>

<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
<div class="section-title">
    <h2 class="title"><?php echo e(__($title)); ?></h2>
</div>
<div class="settings-page-area">
    <div class="settings-page-left">
        <nav class="settings-menu">
            <ul>
                <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module => $modulePermissions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li>
                    <a href="javascript:void(0)"
                        class="fs-15 border-0 w-100 text-left fw-500 lh-25 text-black py-10 px-26 bg-cdef84 bd-ra-12 hover-bg-one module-trigger <?php echo e($loop->first ? 'active' : ''); ?>"
                        data-module="<?php echo e($module); ?>">
                        <?php echo e(moduleName($module)); ?> <i class="fa-solid fa-angle-right"></i>
                    </a>
                </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </nav>
    </div>
    <div class="settings-page-right">
        <div class="section-wrap">
            <div class="section-inner-title">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="title"><?php echo e($role->display_name); ?> - <?php echo e(__('Permissions')); ?></h3>
                    <a href="<?php echo e(route('super_admin.roles.index')); ?>" class="primary-btn btn-secondary">
                        <i class="fa fa-arrow-left me-2"></i><?php echo e(__('Back')); ?>

                    </a>
                </div>
            </div>

            <form data-handler="commonResponse" action="<?php echo e(route('super_admin.roles.update.permissions', [$role->id])); ?>"
                method="POST" class="ajax">
                <?php echo csrf_field(); ?>
                <div class="row gy-4">
                    <!-- Permissions Content -->
                    <div class="col-lg-12">
                        <div class="bg-white bd-half bd-c-ebedf0 bd-ra-25 p-30">
                            <!-- Search Box -->
                            <div class="search-input-wrap mb-3">
                                <label class="icon" for="permission-search">
                                    <svg width="17" height="17" viewBox="0 0 17 17" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10.625 10.625L11.6875 11.6875" stroke="#6E5858" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path
                                            d="M11.9944 13.4762C11.5852 13.067 11.5852 12.4035 11.9944 11.9944C12.4035 11.5852 13.067 11.5852 13.4762 11.9944L14.9222 13.4405C15.3314 13.8497 15.3314 14.5131 14.9222 14.9222C14.5131 15.3314 13.8497 15.3314 13.4405 14.9222L11.9944 13.4762Z"
                                            stroke="#6E5858" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M11.6872 6.72982C11.6872 3.99141 9.46726 1.77148 6.72884 1.77148C3.99043 1.77148 1.77051 3.99141 1.77051 6.72982C1.77051 9.46824 3.99043 11.6882 6.72884 11.6882C9.46726 11.6882 11.6872 9.46824 11.6872 6.72982Z"
                                            stroke="#6E5858" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </label>
                                <input type="text" class="search-input" id="permission-search"
                                    placeholder="<?php echo e(__('Search permissions...')); ?>">
                            </div>

                            <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module => $modulePermissions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="module-content <?php echo e($loop->first ? '' : 'd-none'); ?>" data-module="<?php echo e($module); ?>">
                                <h5 class="fs-18 fw-600 text-black mb-20"><?php echo e(moduleName($module)); ?>

                                    <?php echo e(__('Permissions')); ?></h5>
                                <div class="row">
                                    <?php $__currentLoopData = $modulePermissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-6 mb-3 permission-item">
                                        <div class="form-check">
                                            <input <?php echo e(in_array($permission->id, $oldPermissions) ? 'checked' : ''); ?>

                                                class="form-check-input" type="checkbox" name="permissions[]"
                                                value="<?php echo e($permission->name); ?>" id="permission-<?php echo e($permission->id); ?>">
                                            <label class="form-check-label" for="permission-<?php echo e($permission->id); ?>">
                                                <?php echo e($permission->display_name); ?>

                                            </label>
                                        </div>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>

                <div class="btn-list mt-4 pt-3 border-top">
                    <button type="submit" class="primary-btn">
                        <?php echo e(__('Save Permissions')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('script'); ?>
<script src="<?php echo e(asset('super_admin/js/roles-permissions.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('auto_posts.super_admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\super_admin\roles\permissions.blade.php ENDPATH**/ ?>