<form class="ajax reset"
    action="<?php echo e(route('super_admin.subscription-refund.subscription-model-status-change', $statusChange->id)); ?>"
    method="post" data-handler="commonResponseWithPageLoad">
    <?php echo csrf_field(); ?>
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e(__('Refund Status Change')); ?></h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
        </div>
        <div class="primary-form">
            <div class="row gy-3">
                <div class="row gy-3">



                    <div class="row rg-20">

                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Transaction ID')); ?></label>
                                <div class="position-relative">
                                    <span
                                        class="position-absolute top-50 start-0 translate-middle-y ps-3 text-muted"></span>
                                    <input type="text" class="form-control ps-4" name="transaction_id"
                                        value="<?php echo e($statusChange->transaction_id ?? ''); ?>" required readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Refund Amount')); ?></label>
                                <div class="position-relative">
                                    <span
                                        class="position-absolute top-50 start-0 translate-middle-y ps-3 text-muted">$</span>
                                    <input type="number" step="0.01" class="form-control padding-left-increase" name="refund_amount"
                                        value="<?php echo e($statusChange->buy_amount ?? 0); ?>" required readonly>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Admin Feedback')); ?></label>
                                <textarea class="form-control" name="admin_feedback" rows="3"
                                    placeholder="<?php echo e(__('Enter admin feedback...')); ?>"></textarea>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Status')); ?></label>
                                <select class="form-control select wide sf-select-without-search" name="status" required>
                                    <option value="<?php echo e(STATUS_ACTIVE); ?>"
                                        <?php echo e($statusChange->status == STATUS_ACTIVE ? 'selected' : ''); ?>>
                                        <?php echo e(__('Approve')); ?></option>
                                    <option value="<?php echo e(STATUS_REJECT); ?>"
                                        <?php echo e($statusChange->status == STATUS_REJECT ? 'selected' : ''); ?>>
                                        <?php echo e(__('Reject')); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
            <button type="submit" class="primary-btn"><?php echo e(__('Submit')); ?></button>
        </div>
    </div>
</form><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\super_admin\refund-request\refund-status-change.blade.php ENDPATH**/ ?>