<form class="ajax reset" action="<?php echo e($incentive ? route('admin.garments.incentives.update', $incentive->id) : route('admin.garments.incentives.store')); ?>" method="post" data-handler="commonResponseWithPageLoad">
    <?php echo csrf_field(); ?>
    <?php if($incentive): ?>
        <?php echo method_field('put'); ?>
    <?php endif; ?>

    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e($incentive ? __('Edit Incentive') : __('Add Incentive')); ?></h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>

        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Worker Name')); ?> <span class="required">*</span></label>
                        <input type="text" class="form-control" name="employee_name" value="<?php echo e(old('employee_name', $incentive?->employee_name)); ?>" placeholder="<?php echo e(__('e.g. Mohammad Rahim')); ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Order')); ?></label>
                        <select class="form-control" name="order_id">
                            <option value=""><?php echo e(__('Not linked')); ?></option>
                            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($order->id); ?>" <?php if(old('order_id', $incentive?->order_id) == $order->id): echo 'selected'; endif; ?>>
                                    <?php echo e($order->order_number); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Production Date')); ?> <span class="required">*</span></label>
                        <input type="date" class="form-control" name="production_date" value="<?php echo e(old('production_date', $incentive?->production_date?->format('Y-m-d') ?? now()->format('Y-m-d'))); ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Operation')); ?> <span class="required">*</span></label>
                        <input type="text" class="form-control" name="operation" value="<?php echo e(old('operation', $incentive?->operation)); ?>" placeholder="<?php echo e(__('e.g. Sewing')); ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Production Quantity')); ?> <span class="required">*</span></label>
                        <input type="number" min="1" class="form-control" name="production_quantity" value="<?php echo e(old('production_quantity', $incentive?->production_quantity ?? 1)); ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Piece Rate')); ?> <span class="required">*</span></label>
                        <input type="number" min="0" step="0.0001" class="form-control" name="piece_rate" value="<?php echo e(old('piece_rate', $incentive?->piece_rate ?? 0)); ?>" required>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Status')); ?> <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            <?php $__currentLoopData = garmentIncentiveStatuses(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($value); ?>" <?php if(old('status', $incentive?->status ?? GARMENT_INCENTIVE_STATUS_DRAFT) == $value): echo 'selected'; endif; ?>>
                                    <?php echo e(__($status[0])); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Notes')); ?></label>
                        <textarea class="form-control" name="notes" rows="2" placeholder="<?php echo e(__('Short description / notes...')); ?>"><?php echo e(old('notes', $incentive?->notes)); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
            <button type="submit" class="primary-btn"><?php echo e($incentive ? __('Update') : __('Save')); ?></button>
        </div>
    </div>
</form>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\garments\incentives\form.blade.php ENDPATH**/ ?>