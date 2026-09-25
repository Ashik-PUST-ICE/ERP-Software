<?php $__env->startPush('title'); ?> <?php echo e($title); ?> <?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="section-title">
    <h2 class="title"><?php echo e(__($title)); ?></h2>
</div>

<div class="settings-page-area">
    <div class="settings-page-right">
        <div class="section-wrap">
            <div class="table-waraper">
                <div class="search-input-wrap mb-3">
                    <label class="icon" for="searchData">
                        <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10.625 10.625L11.6875 11.6875" stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M11.9944 13.4762C11.5852 13.067 11.5852 12.4035 11.9944 11.9944C12.4035 11.5852 13.067 11.5852 13.4762 11.9944L14.9222 13.4405C15.3314 13.8497 15.3314 14.5131 14.9222 14.9222C14.4405 14.9222L11.9944 13.4762Z" stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M11.6872 6.72982C11.6872 3.99141 9.46726 1.77148 6.72884 1.77148C3.99043 1.77148 1.77051 3.99141 1.77051 6.72982C1.77051 9.46824 3.99043 11.6882 6.72884 11.6882 9.46824 11.6882 6.72982Z" stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </label>
                    <input type="text" class="search-input" id="searchData" placeholder="<?php echo e(__('Search audit logs...')); ?>" />
                    <select class="form-control w-auto" id="auditLogModuleFilter" style="height: 38px; border-radius: 8px; font-size: 13px;">
                        <option value=""><?php echo e(__('All Modules')); ?></option>
                        <?php $__currentLoopData = ['Supplier', 'Purchase Order', 'Email', 'Email Template', 'Queue']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($module); ?>" <?php if(request('module') === $module): echo 'selected'; endif; ?>><?php echo e($module); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <select class="form-control w-auto" id="auditLogActionFilter" style="height: 38px; border-radius: 8px; font-size: 13px;">
                        <option value=""><?php echo e(__('All Actions')); ?></option>
                        <?php $__currentLoopData = ['created', 'updated', 'deleted']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($action); ?>" <?php if(request('action') === $action): echo 'selected'; endif; ?>><?php echo e(ucfirst($action)); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <input type="hidden" id="audit-logs-route" value="<?php echo e(route('admin.garments.audit-logs.index')); ?>">
                </div>

                <table class="display primary-table dataTable dtr-inline" id="auditLogsTable">
                    <thead>
                        <tr>
                            <th><?php echo e(__('Date')); ?></th>
                            <th><?php echo e(__('Action')); ?></th>
                            <th><?php echo e(__('Module')); ?></th>
                            <th><?php echo e(__('Description')); ?></th>
                            <th><?php echo e(__('IP Address')); ?></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
<script src="<?php echo e(asset('admin/js/garment-audit-logs.js')); ?>?ver=<?php echo e(env('VERSION', 0)); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('auto_posts.admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\garments\audit-logs\index.blade.php ENDPATH**/ ?>