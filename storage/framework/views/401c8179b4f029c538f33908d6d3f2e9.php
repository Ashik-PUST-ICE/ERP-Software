<?php $__env->startPush('title'); ?> <?php echo e($title); ?> <?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="section-title">
    <h2 class="title"><?php echo e(__($title)); ?></h2>
    <button type="button" class="primary-btn" data-bs-toggle="modal" data-bs-target="#add-modal">
        <i class="fa fa-plus me-2"></i><?php echo e(__('Assign Merchandiser')); ?>

    </button>
</div>

<div class="settings-page-area">
    <div class="settings-page-right">
        <div class="section-wrap">
            <div class="table-waraper">
                <div class="search-input-wrap mb-3">
                    <label class="icon" for="searchData">
                        <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10.625 10.625L11.6875 11.6875" stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M11.6872 6.72982C11.6872 3.99141 9.46726 1.77148 6.72884 1.77148C3.99043 1.77148 1.77051 3.99141 1.77051 6.72982C1.77051 9.46824 3.99043 11.6882 6.72884 11.6882C9.46726 11.6882 11.6872 9.46824 11.6872 6.72982Z" stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </label>
                    <input type="text" class="search-input" id="searchData" placeholder="<?php echo e(__('Search Orders...')); ?>" />
                </div>
                <input type="hidden" id="merchandiser-orders-data-route" value="<?php echo e(route('admin.garments.merchandiser.management.data')); ?>?section=orders">
                <table class="display primary-table dataTable dtr-inline" id="merchandiserOrderDataTable">
                    <thead>
                        <tr>
                            <th class="keep-show"><?php echo e(__('SL')); ?></th>
                            <th><?php echo e(__('Order #')); ?></th>
                            <th><?php echo e(__('Buyer')); ?></th>
                            <th><?php echo e(__('Style')); ?></th>
                            <th><?php echo e(__('Primary Merchandiser')); ?></th>
                            <th><?php echo e(__('Assigned Team')); ?></th>
                            <th class="keep-show"><?php echo e(__('Action')); ?></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Add Modal -->
<div class="modal fade zModalTwo" id="add-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content zModalTwo-content">
            <form class="ajax reset" action="<?php echo e(route('admin.garments.merchandiser.assign')); ?>" method="post"
                data-handler="commonResponseForModal">
                <?php echo csrf_field(); ?>
                <div class="modal-body zModalTwo-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e(__('Assign Merchandiser')); ?></h4>
                        <div class="mClose">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </div>
                    <div class="primary-form">
                        <div class="row gy-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label"><?php echo e(__('Select Garment Order')); ?> <span class="required">*</span></label>
                                    <select name="order_id" class="form-control" required>
                                        <option value=""><?php echo e(__('Select order...')); ?></option>
                                        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($order->id); ?>"><?php echo e($order->order_number); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label"><?php echo e(__('Lead / Primary Merchandiser')); ?></label>
                                    <select name="primary_user_id" class="form-control">
                                        <option value=""><?php echo e(__('Select lead (optional)...')); ?></option>
                                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group merchandiser-team-wrap">
                                    <label class="form-label"><?php echo e(__('Assign Merchandisers (Team)')); ?> <span class="required">*</span></label>
                                    <select class="form-control multiple-basic-single" multiple="multiple" name="user_ids[]" required>
                                        <option value=""></option>
                                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn-list mt-4 pt-3 border-top">
                        <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
                        <button type="submit" class="primary-btn"><?php echo e(__('Save')); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Edit Modal -->
<div class="modal fade zModalTwo" id="edit-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content zModalTwo-content">
            <!-- Content loaded via AJAX -->
        </div>
    </div>
</div>
<!-- PART2 -->
<?php $__env->stopSection(); ?>

<?php $__env->startPush('style'); ?>
<style>
#add-modal .select2-container.select2-container--open,
#edit-modal .select2-container.select2-container--open {
    z-index: 9999;
}
#add-modal .modal-body,
#edit-modal .modal-body {
    overflow: visible;
}
#add-modal .modal-content,
#edit-modal .modal-content {
    overflow: visible;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
<script src="<?php echo e(asset('admin/js/garment-merchandiser-orders.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('auto_posts.admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\garments\merchandiser\orders.blade.php ENDPATH**/ ?>