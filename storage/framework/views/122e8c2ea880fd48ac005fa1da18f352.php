<form class="ajax reset" action="<?php echo e($attendance ? route('admin.garments.production-attendance.update', $attendance->id) : route('admin.garments.production-attendance.store')); ?>" method="post" data-handler="commonResponseWithPageLoad">
    <?php echo csrf_field(); ?>
    <?php if($attendance): ?>
        <?php echo method_field('put'); ?>
    <?php endif; ?>

    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e($attendance ? __('Edit Production Attendance') : __('Add Production Attendance')); ?></h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>

        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Employee')); ?> <span class="required">*</span></label>
                        <select class="form-control" name="employee_id" required>
                            <option value=""><?php echo e(__('Select Employee')); ?></option>
                            <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($employee->id); ?>" <?php if(old('employee_id', $attendance?->employee_id) == $employee->id): echo 'selected'; endif; ?>>
                                    <?php echo e($employee->full_name); ?> (<?php echo e($employee->employee_code); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Production Line')); ?> <span class="required">*</span></label>
                        <input type="text" class="form-control" name="line_name" value="<?php echo e(old('line_name', $attendance?->line_name)); ?>" placeholder="<?php echo e(__('e.g. Line 01 / Sewing A')); ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Order')); ?></label>
                        <select class="form-control" name="order_id">
                            <option value=""><?php echo e(__('Not linked')); ?></option>
                            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($order->id); ?>" <?php if(old('order_id', $attendance?->order_id) == $order->id): echo 'selected'; endif; ?>>
                                    <?php echo e($order->order_number); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Date')); ?> <span class="required">*</span></label>
                        <input type="date" class="form-control" name="attendance_date" value="<?php echo e(old('attendance_date', $attendance?->attendance_date?->format('Y-m-d') ?? now()->format('Y-m-d'))); ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Status')); ?> <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            <option value="1" <?php if(old('status', $attendance?->status ?? 1) == 1): echo 'selected'; endif; ?>><?php echo e(__('Present')); ?></option>
                            <option value="2" <?php if(old('status', $attendance?->status) == 2): echo 'selected'; endif; ?>><?php echo e(__('Absent')); ?></option>
                            <option value="3" <?php if(old('status', $attendance?->status) == 3): echo 'selected'; endif; ?>><?php echo e(__('Leave')); ?></option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Production Qty')); ?></label>
                        <input type="number" min="0" class="form-control" name="production_quantity" value="<?php echo e(old('production_quantity', $attendance?->production_quantity ?? 0)); ?>" placeholder="<?php echo e(__('0')); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Working Hours')); ?></label>
                        <input type="number" min="0" max="24" step="0.01" class="form-control" name="working_hours" value="<?php echo e(old('working_hours', $attendance?->working_hours ?? 8)); ?>" placeholder="<?php echo e(__('8')); ?>">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Notes')); ?></label>
                        <textarea class="form-control" name="notes" rows="2" placeholder="<?php echo e(__('Short description...')); ?>"><?php echo e(old('notes', $attendance?->notes)); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
            <button type="submit" class="primary-btn"><?php echo e($attendance ? __('Update') : __('Save')); ?></button>
        </div>
    </div>
</form>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\garments\production-attendance\form.blade.php ENDPATH**/ ?>