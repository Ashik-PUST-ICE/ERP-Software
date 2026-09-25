<?php $__env->startPush('title'); ?>
<?php echo e($title); ?>

<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
<div class="section-title">
    <h2 class="title"><?php echo e(__($title)); ?></h2>
</div>
<div class="settings-page-area user-settings-page-area-for-responsive">
    <div class="settings-page-right">
        <div class="section-wrap">
            <div class="table-waraper">
                <div class="search-input-wrap">
                    <label class="icon" for="searchData">
                        <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10.625 10.625L11.6875 11.6875" stroke="#6E5858" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M11.9944 13.4762C11.5852 13.067 11.5852 12.4035 11.9944 11.9944C12.4035 11.5852 13.067 11.5852 13.4762 11.9944L14.9222 13.4405C15.3314 13.8497 15.3314 14.5131 14.9222 14.9222C14.5131 15.3314 13.8497 15.3314 13.4405 14.9222L11.9944 13.4762Z"
                                stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M11.6872 6.72982C11.6872 3.99141 9.46726 1.77148 6.72884 1.77148C3.99043 1.77148 1.77051 3.99141 1.77051 6.72982C1.77051 9.46824 3.99043 11.6882 6.72884 11.6882C9.46726 11.6882 11.6872 9.46824 11.6872 6.72982Z"
                                stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </label>
                    <input type="text" class="search-input" id="searchData"
                        placeholder="<?php echo e(__('Search By Name or Email...')); ?>" />
                </div>
                <input type="hidden" id="users-data-route" value="<?php echo e(route('super_admin.users.data')); ?>">
                <div class="table-responsive">

                    <table class="table display primary-table dataTable dtr-inline" id="usersDataTable">
                        <thead>
                            <tr>
                                <th class="keep-show"><?php echo e(__("SL")); ?></th>
                                <th><?php echo e(__("Name")); ?></th>
                                <th><?php echo e(__("Email")); ?></th>
                                <th><?php echo e(__("Role")); ?></th>
                                <th><?php echo e(__("Mobile")); ?></th>
                                <th><?php echo e(__("Status")); ?></th>
                                <th class="keep-show"><?php echo e(__("Action")); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div id="users-pagination-wrap" class="d-flex justify-content-center mt-20 tablePagi"></div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade zModalTwo" id="edit-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            <!-- Content loaded via AJAX -->
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
<script src="<?php echo e(asset('super_admin/js/users.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('auto_posts.super_admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\super_admin\users\index.blade.php ENDPATH**/ ?>