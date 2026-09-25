<form class="ajax reset" action="<?php echo e($qc ? route('admin.garments.inline-qc.update', $qc->id) : route('admin.garments.inline-qc.store')); ?>" method="post" data-handler="commonResponseWithPageLoad">
    <?php echo csrf_field(); ?>
    <?php if($qc): ?>
        <?php echo method_field('put'); ?>
    <?php endif; ?>
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e($qc ? __('Edit Inline QC') : __('Add Inline QC')); ?></h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>
        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Order')); ?> <span class="required">*</span></label>
                        <select class="form-control" name="order_id" required>
                            <option value=""><?php echo e(__('Select Order')); ?></option>
                            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($order->id); ?>" <?php if(old('order_id', $qc?->order_id) == $order->id): echo 'selected'; endif; ?>><?php echo e($order->order_number); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Inspection Point')); ?> <span class="required">*</span></label>
                        <input class="form-control" name="inspection_point" value="<?php echo e(old('inspection_point', $qc?->inspection_point)); ?>" placeholder="<?php echo e(__('Sewing line, operation or checkpoint')); ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Date')); ?> <span class="required">*</span></label>
                        <input type="date" class="form-control" name="inspection_date" value="<?php echo e(old('inspection_date', $qc?->inspection_date?->format('Y-m-d') ?? now()->format('Y-m-d'))); ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Checked Qty')); ?></label>
                        <input type="number" min="0" class="form-control" name="checked_quantity" value="<?php echo e(old('checked_quantity', $qc?->checked_quantity ?? 0)); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Passed Qty')); ?></label>
                        <input type="number" min="0" class="form-control" name="passed_quantity" value="<?php echo e(old('passed_quantity', $qc?->passed_quantity ?? 0)); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Defect Qty')); ?></label>
                        <input type="number" min="0" class="form-control" name="defect_quantity" value="<?php echo e(old('defect_quantity', $qc?->defect_quantity ?? 0)); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Inspector')); ?></label>
                        <input class="form-control" name="inspector_name" value="<?php echo e(old('inspector_name', $qc?->inspector_name)); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Status')); ?> <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            <?php $__currentLoopData = garmentQcStatuses(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($value); ?>" <?php if(old('status', $qc?->status ?? GARMENT_QC_STATUS_PENDING) == $value): echo 'selected'; endif; ?>><?php echo e(__($status[0])); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Notes')); ?></label>
                        <textarea class="form-control" name="notes" rows="3"><?php echo e(old('notes', $qc?->notes)); ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
            <button class="primary-btn" type="submit"><?php echo e($qc ? __('Update') : __('Save')); ?></button>
        </div>
    </div>
</form>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\garments\inline-qc\form.blade.php ENDPATH**/ ?>