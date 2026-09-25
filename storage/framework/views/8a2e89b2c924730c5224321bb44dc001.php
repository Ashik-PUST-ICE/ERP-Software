<?php $__env->startPush('title'); ?> <?php echo e($title); ?> <?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="section-title">
    <h2 class="title"><?php echo e(__($title)); ?></h2>
</div>

<div class="settings-page-area">
    <div class="settings-page-right">
        <div class="section-wrap">
            <div class="table-waraper">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                    <div class="search-input-wrap mb-0 flex-grow-1" style="max-width: 380px;">
                        <label class="icon" for="searchData">
                            <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.625 10.625L11.6875 11.6875" stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M11.9944 13.4762C11.5852 13.067 11.5852 12.4035 11.9944 11.9944C12.4035 11.5852 13.067 11.5852 13.4762 11.9944L14.9222 13.4405C15.3314 13.8497 15.3314 14.5131 14.9222 14.9222C14.5131 15.3314 13.8497 15.3314 14.4405 14.9222L11.9944 13.4762Z" stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M11.6872 6.72982C11.6872 3.99141 9.46726 1.77148 6.72884 1.77148C3.99043 1.77148 1.77051 3.99141 1.77051 6.72982C1.77051 9.46824 3.99043 11.6882 6.72884 11.6882C9.46726 11.6882 11.6882 9.46824 11.6882 6.72982Z" stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </label>
                        <input type="text" class="search-input" id="searchData" placeholder="<?php echo e(__('Search costing variance...')); ?>" />
                    </div>

                    <div class="d-flex align-items-center gap-2 flex-wrap ms-auto">
                        <div class="select-wrap" style="min-width: 170px;">
                            <select class="form-select form-control sf-select wide" id="costingVarianceOrderFilter" style="height: 42px; border-radius: 10px; font-size: 13px;">
                                <option value=""><?php echo e(__('All Orders')); ?></option>
                                <?php $__currentLoopData = $orderOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($option->id); ?>" <?php if((int) request('order_id') === $option->id): echo 'selected'; endif; ?>><?php echo e($option->order_number); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="costing-variance-route" value="<?php echo e(route('admin.garments.costing-variance.index')); ?>">
                <table class="display primary-table dataTable dtr-inline" id="costingVarianceTable">
                    <thead>
                        <tr>
                            <th><?php echo e(__('Order')); ?></th>
                            <th><?php echo e(__('Buyer')); ?></th>
                            <th><?php echo e(__('Estimated Cost')); ?></th>
                            <th><?php echo e(__('Actual Cost')); ?></th>
                            <th><?php echo e(__('Variance')); ?></th>
                            <th><?php echo e(__('Margin')); ?></th>
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
<script src="<?php echo e(asset('admin/js/garment-costing-variance.js')); ?>?ver=<?php echo e(env('VERSION', 0)); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('auto_posts.admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\garments\costing-variance\index.blade.php ENDPATH**/ ?>