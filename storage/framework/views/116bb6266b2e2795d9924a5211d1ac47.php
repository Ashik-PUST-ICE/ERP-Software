<?php $__env->startPush('title'); ?>
<?php echo e($title); ?>

<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>

<div class="section-title">
    <h2 class="title"><?php echo e(__($title)); ?></h2>
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
            <input type="text" class="search-input" id="searchData"
                placeholder="<?php echo e(__('Search By Transaction Id...')); ?>" />
        </div>

        <!-- Order Status Filter Tabs -->
        <div class="mb-3">
            <ul class="nav post-tabs" id="orderTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link orderStatusTab active" data-bs-toggle="tab" type="button" data-status="All"
                        role="tab">
                        <?php echo e(__('All')); ?>

                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link orderStatusTab" data-bs-toggle="tab" type="button" data-status="Paid"
                        role="tab">
                        <?php echo e(__('Paid')); ?>

                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link orderStatusTab" data-bs-toggle="tab" type="button" data-status="Pending"
                        role="tab">
                        <?php echo e(__('Pending')); ?>

                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link orderStatusTab" data-bs-toggle="tab" type="button" data-status="Cancelled"
                        role="tab">
                        <?php echo e(__('Cancelled')); ?>

                    </button>
                </li>
            </ul>
        </div>

        <input type="hidden" id="ordersDataRoute"
            value="<?php echo e(route('super_admin.subscriptions.orders.payment.status')); ?>">
        <input type="hidden" id="orderInfoRoute" value="<?php echo e(route('super_admin.subscriptions.orders.get.info')); ?>">
        <table class="display primary-table dataTable dtr-inline" id="orderDataTable">
            <thead>
                <tr>
                    <th class="keep-show"><?php echo e(__('SL')); ?></th>
                    <th><?php echo e(__('Date')); ?></th>
                    <th><?php echo e(__('Transaction Id')); ?></th>
                    <th><?php echo e(__('User Name')); ?></th>
                    <th><?php echo e(__('User Email')); ?></th>
                    <th><?php echo e(__('Package')); ?></th>
                    <th><?php echo e(__('Gateway')); ?></th>
                    <th><?php echo e(__('Amount')); ?></th>
                    <th><?php echo e(__('Status')); ?></th>
                    <th class="keep-show"><?php echo e(__('Action')); ?></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <div id="orders-pagination-wrap" class="d-flex justify-content-center mt-20 tablePagi"></div>
    </div>
</div>

<!-- Payment Status Change Modal -->
<div class="modal fade zModalTwo" id="payStatusChangeModal" tabindex="-1" aria-labelledby="payStatusChangeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            <form class="ajax reset" action="<?php echo e(route('super_admin.subscriptions.order.payment.status.change')); ?>"
                method="post" data-handler="commonResponseForModal">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="id">
                <div class="modal-body zModalTwo-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e(__('Payment Status Change')); ?></h4>
                        <div class="mClose">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </div>
                    <div class="primary-form">
                        <div class="row gy-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label"><?php echo e(__('Status')); ?></label>
                                    <select class="select form-control wide sf-select-without-search"
                                        name="payment_status">
                                        <option value="<?php echo e(PAYMENT_STATUS_PENDING); ?>"><?php echo e(__('Pending')); ?></option>
                                        <option value="<?php echo e(PAYMENT_STATUS_PAID); ?>"><?php echo e(__('Paid')); ?></option>
                                        <option value="<?php echo e(PAYMENT_STATUS_CANCELLED); ?>"><?php echo e(__('Cancelled')); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn-list mt-4 pt-3 border-top">
                        <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
                        <button type="submit" class="primary-btn"><?php echo e(__('Submit')); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
<script src="<?php echo e(asset('super_admin/js/subscription-orders.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('auto_posts.super_admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\super_admin\subscriptions\orders.blade.php ENDPATH**/ ?>