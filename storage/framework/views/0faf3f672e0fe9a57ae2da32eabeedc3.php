<form class="ajax reset" action="<?php echo e($defect ? route('admin.garments.defects.update', $defect->id) : route('admin.garments.defects.store')); ?>" method="post" data-handler="commonResponseWithPageLoad">
    <?php echo csrf_field(); ?>
    <?php if($defect): ?>
        <?php echo method_field('put'); ?>
    <?php endif; ?>
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e($defect ? __('Edit Defect Record') : __('Add Defect Record')); ?></h4>
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
                                <option value="<?php echo e($order->id); ?>" <?php if(old('order_id', $defect?->order_id) == $order->id): echo 'selected'; endif; ?>><?php echo e($order->order_number); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Inline QC Reference')); ?></label>
                        <select class="form-control" name="inline_qc_id">
                            <option value=""><?php echo e(__('None')); ?></option>
                            <?php $__currentLoopData = $qcs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $qc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($qc->id); ?>" <?php if(old('inline_qc_id', $defect?->inline_qc_id) == $qc->id): echo 'selected'; endif; ?>><?php echo e($qc->inspection_point); ?> - <?php echo e($qc->inspection_date?->format('d M Y')); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Defect Type')); ?> <span class="required">*</span></label>
                        <input class="form-control" name="defect_type" value="<?php echo e(old('defect_type', $defect?->defect_type)); ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Section')); ?> <span class="required">*</span></label>
                        <input class="form-control" name="section" value="<?php echo e(old('section', $defect?->section)); ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Defect Qty')); ?></label>
                        <input type="number" min="0" class="form-control" name="defect_quantity" value="<?php echo e(old('defect_quantity', $defect?->defect_quantity ?? 0)); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Rejected Qty')); ?></label>
                        <input type="number" min="0" class="form-control" name="rejected_quantity" value="<?php echo e(old('rejected_quantity', $defect?->rejected_quantity ?? 0)); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Reported Date')); ?> <span class="required">*</span></label>
                        <input type="date" class="form-control" name="reported_date" value="<?php echo e(old('reported_date', $defect?->reported_date?->format('Y-m-d') ?? now()->format('Y-m-d'))); ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Root Cause')); ?></label>
                        <input class="form-control" name="root_cause" value="<?php echo e(old('root_cause', $defect?->root_cause)); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Status')); ?> <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            <?php $__currentLoopData = garmentDefectStatuses(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($value); ?>" <?php if(old('status', $defect?->status ?? GARMENT_DEFECT_STATUS_OPEN) == $value): echo 'selected'; endif; ?>><?php echo e(__($status[0])); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Corrective Action')); ?></label>
                        <textarea class="form-control" name="corrective_action" rows="3"><?php echo e(old('corrective_action', $defect?->corrective_action)); ?></textarea>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Notes')); ?></label>
                        <textarea class="form-control" name="notes" rows="2"><?php echo e(old('notes', $defect?->notes)); ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
            <button class="primary-btn" type="submit"><?php echo e($defect ? __('Update') : __('Save')); ?></button>
        </div>
    </div>
</form>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\garments\defects\form.blade.php ENDPATH**/ ?>