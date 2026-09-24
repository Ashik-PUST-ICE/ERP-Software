<?php $__env->startPush('title'); ?>
<?php echo e(__('Coupons')); ?>

<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<div class="section-title">
    <h2 class="title"><?php echo e(__('Coupons')); ?></h2>
    <button type="button" class="primary-btn" onclick="openCouponModal()">
        <i class="fa fa-plus me-2"></i><?php echo e(__('Add New Coupon')); ?>

    </button>
</div>
<div class="section-wrap">
    <div class="table-waraper">
        <div class="search-input-wrap">
            <label class="icon" for="searchData">
                <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.625 10.625L11.6875 11.6875" stroke="#6E5858" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path
                        d="M11.9944 13.4762C11.5852 13.067 11.5852 12.4035 11.9944 11.9944C12.4035 11.5852 13.067 11.5852 13.4762 11.9944L14.9222 13.4405C15.3314 13.8497 15.3314 14.5131 14.9222 14.9222C14.5131 15.3314 13.8497 15.3314 13.4405 14.9222L11.9944 13.4762Z"
                        stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                        d="M11.6872 6.72982C11.6872 3.99141 9.46726 1.77148 6.72884 1.77148C3.99043 1.77148 1.77051 3.99141 1.77051 6.72982C1.77051 9.46824 3.99043 11.6882 6.72884 11.6882C9.46726 11.6882 11.6872 9.46824 11.6872 6.72982Z"
                        stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </label>
            <input type="text" class="search-input" id="searchData" placeholder="<?php echo e(__('Search By Name...')); ?>" />
        </div>
        <input type="hidden" id="coupons-data-route" value="<?php echo e(route('super_admin.coupons.data')); ?>">

        <table class="display primary-table dataTable dtr-inline" id="couponsDataTable">
            <thead>
                <tr>
                    <th class="keep-show"><?php echo e(__('SL')); ?></th>
                    <th><?php echo e(__('Name')); ?></th>
                    <th><?php echo e(__('Code')); ?></th>
                    <th><?php echo e(__('Type')); ?></th>
                    <th><?php echo e(__('Amount')); ?></th>
                    <th><?php echo e(__('Start Date')); ?></th>
                    <th><?php echo e(__('End Date')); ?></th>
                    <th><?php echo e(__('Usage Limit')); ?></th>
                    <th><?php echo e(__('Used')); ?></th>
                    <th><?php echo e(__('Status')); ?></th>
                    <th class="keep-show"><?php echo e(__('Action')); ?></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <div id="coupons-pagination-wrap" class="d-flex justify-content-center mt-20 tablePagi"></div>
    </div>
</div>


<!-- Coupon Modal -->
<div class="modal fade primary-modal" id="coupon-modal" tabindex="-1" aria-labelledby="couponModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="couponModalLabel"><?php echo e(__('Add New Coupon')); ?></h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="coupon-form" method="POST" action="<?php echo e(route('super_admin.coupons.store')); ?>"
                data-store="<?php echo e(route('super_admin.coupons.store')); ?>"
                data-update="<?php echo e(route('super_admin.coupons.update', ':id')); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="_method" id="form-method" value="POST">
                <div class="modal-body">
                    <div class="primary-form">
                        <div class="row gy-4">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="name" class="form-label"><?php echo e(__('Coupon Name')); ?><span
                                            class="required">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="<?php echo e(__('Enter coupon name')); ?>" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="code" class="form-label"><?php echo e(__('Coupon Code')); ?><span
                                            class="required">*</span></label>
                                    <input type="text" class="form-control" id="code" name="code"
                                        placeholder="<?php echo e(__('Enter coupon code')); ?>" required
                                        style="text-transform: uppercase;">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="discount_type" class="form-label"><?php echo e(__('Discount Type')); ?><span
                                            class="required">*</span></label>
                                    <select class="select form-control wide sf-select-without-search" id="discount_type"
                                        name="discount_type" required>
                                        <option value="fixed"><?php echo e(__('Fixed Amount')); ?></option>
                                        <option value="percentage"><?php echo e(__('Percentage')); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="amount" class="form-label"><?php echo e(__('Discount Amount')); ?><span
                                            class="required">*</span></label>
                                    <input type="number" class="form-control" id="amount" name="amount"
                                        placeholder="<?php echo e(__('Enter discount amount')); ?>" step="0.01" min="0" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="start_date" class="form-label"><?php echo e(__('Start Date')); ?><span
                                            class="required">*</span></label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="end_date" class="form-label"><?php echo e(__('End Date')); ?><span
                                            class="required">*</span></label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="minimum_spend" class="form-label"><?php echo e(__('Minimum Spend')); ?></label>
                                    <input type="number" class="form-control" id="minimum_spend" name="minimum_spend"
                                        placeholder="<?php echo e(__('Enter minimum spend')); ?>" step="0.01" min="0">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="usage_limit_per_coupon"
                                        class="form-label"><?php echo e(__('Usage Limit Per Coupon')); ?></label>
                                    <input type="number" class="form-control" id="usage_limit_per_coupon"
                                        name="usage_limit_per_coupon" placeholder="<?php echo e(__('Enter global usage limit')); ?>"
                                        min="0">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="usage_limit_per_customer"
                                        class="form-label"><?php echo e(__('Usage Limit Per Customer')); ?></label>
                                    <input type="number" class="form-control" id="usage_limit_per_customer"
                                        name="usage_limit_per_customer"
                                        placeholder="<?php echo e(__('Enter per-user usage limit')); ?>" min="0">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="status" class="form-label"><?php echo e(__('Status')); ?><span
                                            class="required">*</span></label>
                                    <select class="select form-control wide sf-select-without-search" id="status"
                                        name="status" required>
                                        <option value="1"><?php echo e(__('Active')); ?></option>
                                        <option value="0"><?php echo e(__('Inactive')); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="primary-btn btn-secondary"
                        data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
                    <button type="submit" class="primary-btn" id="submit-btn"><?php echo e(__('Create Coupon')); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>



<?php $__env->startPush('script'); ?>
<script src="<?php echo e(asset('super_admin/js/coupons.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('auto_posts.super_admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\super_admin\coupons\index.blade.php ENDPATH**/ ?>