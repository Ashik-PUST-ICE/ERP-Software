<form class="ajax reset" action="<?php echo e($efficiency ? route('admin.garments.efficiency.update', $efficiency->id) : route('admin.garments.efficiency.store')); ?>" method="post" data-handler="commonResponseWithPageLoad">
    <?php echo csrf_field(); ?>
    <?php if($efficiency): ?>
        <?php echo method_field('put'); ?>
    <?php endif; ?>
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e($efficiency ? __('Edit Efficiency Record') : __('Add Efficiency Record')); ?></h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>

        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Operator')); ?></label>
                        <select class="form-control" name="employee_id">
                            <option value=""><?php echo e(__('Machine / Unassigned')); ?></option>
                            <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($employee->id); ?>" <?php if(old('employee_id', $efficiency?->employee_id) == $employee->id): echo 'selected'; endif; ?>>
                                    <?php echo e($employee->full_name); ?> (<?php echo e($employee->employee_code); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Order')); ?></label>
                        <select class="form-control" name="order_id">
                            <option value=""><?php echo e(__('No Order')); ?></option>
                            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($order->id); ?>" <?php if(old('order_id', $efficiency?->order_id) == $order->id): echo 'selected'; endif; ?>>
                                    <?php echo e($order->order_number); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Machine Name')); ?></label>
                        <input class="form-control" name="machine_name" value="<?php echo e(old('machine_name', $efficiency?->machine_name)); ?>" placeholder="<?php echo e(__('e.g. SNLS-001')); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Line Name')); ?></label>
                        <input class="form-control" name="line_name" value="<?php echo e(old('line_name', $efficiency?->line_name)); ?>" placeholder="<?php echo e(__('e.g. Line 01')); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Work Date')); ?> <span class="required">*</span></label>
                        <input type="date" class="form-control" name="work_date" value="<?php echo e(old('work_date', $efficiency?->work_date?->format('Y-m-d') ?? now()->format('Y-m-d'))); ?>" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Working Minutes')); ?></label>
                        <input type="number" min="0" class="form-control" name="working_minutes" value="<?php echo e(old('working_minutes', $efficiency?->working_minutes ?? 0)); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Target Output')); ?></label>
                        <input type="number" min="0" class="form-control" name="target_output" value="<?php echo e(old('target_output', $efficiency?->target_output ?? 0)); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Actual Output')); ?></label>
                        <input type="number" min="0" class="form-control" name="actual_output" value="<?php echo e(old('actual_output', $efficiency?->actual_output ?? 0)); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Status')); ?> <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            <?php $__currentLoopData = garmentEfficiencyStatuses(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($value); ?>" <?php if(old('status', $efficiency?->status ?? GARMENT_EFFICIENCY_STATUS_RECORDED) == $value): echo 'selected'; endif; ?>>
                                    <?php echo e(__($status[0])); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Notes')); ?></label>
                        <textarea class="form-control" name="notes" rows="2" placeholder="<?php echo e(__('Efficiency notes...')); ?>"><?php echo e(old('notes', $efficiency?->notes)); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
            <button type="submit" class="primary-btn"><?php echo e($efficiency ? __('Update') : __('Save')); ?></button>
        </div>
    </div>
</form>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\garments\efficiency\form.blade.php ENDPATH**/ ?>