<form class="ajax reset" action="<?php echo e($task ? route('admin.garments.tna.update', $task->id) : route('admin.garments.tna.store')); ?>" method="post" data-handler="commonResponseWithPageLoad">
    <?php echo csrf_field(); ?>
    <?php if($task): ?>
        <?php echo method_field('put'); ?>
    <?php endif; ?>
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e($task ? __('Edit TNA Task') : __('Add TNA Task')); ?></h4>
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
                            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $orderOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($orderOption->id); ?>" <?php if(old('order_id', $task?->order_id) == $orderOption->id): echo 'selected'; endif; ?>>
                                    <?php echo e($orderOption->order_number); ?> | <?php echo e($orderOption->style?->style_code); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Responsible Employee')); ?></label>
                        <select class="form-control" name="employee_id">
                            <option value=""><?php echo e(__('Unassigned')); ?></option>
                            <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employeeOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($employeeOption->id); ?>" <?php if(old('employee_id', $task?->employee_id) == $employeeOption->id): echo 'selected'; endif; ?>>
                                    <?php echo e($employeeOption->full_name); ?> (<?php echo e($employeeOption->employee_code); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Task Name')); ?> <span class="required">*</span></label>
                        <input type="text" class="form-control" name="task_name" value="<?php echo e(old('task_name', $task?->task_name)); ?>" placeholder="<?php echo e(__('e.g. PP Meeting, Fabric In-house, Shipment')); ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Task Type')); ?></label>
                        <input type="text" class="form-control" name="task_type" value="<?php echo e(old('task_type', $task?->task_type)); ?>" placeholder="<?php echo e(__('Merchandising, Production...')); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Planned Date')); ?> <span class="required">*</span></label>
                        <input type="date" class="form-control" name="planned_date" value="<?php echo e(old('planned_date', $task?->planned_date?->format('Y-m-d') ?? now()->format('Y-m-d'))); ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Actual Date')); ?></label>
                        <input type="date" class="form-control" name="actual_date" value="<?php echo e(old('actual_date', $task?->actual_date?->format('Y-m-d'))); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Status')); ?> <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            <?php $__currentLoopData = garmentTnaStatuses(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $statusValue => $statusData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($statusValue); ?>" <?php if(old('status', $task?->status ?? GARMENT_TNA_STATUS_PENDING) == $statusValue): echo 'selected'; endif; ?>>
                                    <?php echo e(__($statusData[0])); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Notes')); ?></label>
                        <textarea class="form-control" name="notes" rows="2" placeholder="<?php echo e(__('Add milestone notes or dependencies...')); ?>"><?php echo e(old('notes', $task?->notes)); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
            <button type="submit" class="primary-btn"><?php echo e($task ? __('Update') : __('Save')); ?></button>
        </div>
    </div>
</form>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\garments\tna\form.blade.php ENDPATH**/ ?>