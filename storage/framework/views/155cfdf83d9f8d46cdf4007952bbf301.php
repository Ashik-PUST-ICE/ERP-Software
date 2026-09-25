<form class="ajax reset" action="<?php echo e($plan ? route('admin.garments.plans.update', $plan->id) : route('admin.garments.plans.store')); ?>" method="post" data-handler="commonResponseWithPageLoad">
    <?php echo csrf_field(); ?>
    <?php if($plan): ?>
        <?php echo method_field('put'); ?>
    <?php endif; ?>
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e($plan ? __('Edit Production Plan') : __('Add Production Plan')); ?></h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>

        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-md-7">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Order')); ?> <span class="required">*</span></label>
                        <select class="form-control" name="order_id" required>
                            <option value=""><?php echo e(__('Select Order')); ?></option>
                            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $orderOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($orderOption->id); ?>" <?php if(old('order_id', $plan?->order_id) == $orderOption->id): echo 'selected'; endif; ?>>
                                    <?php echo e($orderOption->order_number); ?> | <?php echo e($orderOption->style?->style_code); ?> | <?php echo e($orderOption->buyer?->company_name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Line Name')); ?> <span class="required">*</span></label>
                        <input type="text" class="form-control" name="line_name" value="<?php echo e(old('line_name', $plan?->line_name)); ?>" placeholder="<?php echo e(__('e.g. Sewing Line 01')); ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Planned Quantity')); ?> <span class="required">*</span></label>
                        <input type="number" min="1" class="form-control" name="planned_quantity" value="<?php echo e(old('planned_quantity', $plan?->planned_quantity)); ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Daily Target')); ?> <span class="required">*</span></label>
                        <input type="number" min="1" class="form-control" name="daily_target" value="<?php echo e(old('daily_target', $plan?->daily_target)); ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Capacity / Day')); ?></label>
                        <input type="number" min="0" class="form-control" name="capacity_per_day" value="<?php echo e(old('capacity_per_day', $plan?->capacity_per_day ?? 0)); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Start Date')); ?> <span class="required">*</span></label>
                        <input type="date" class="form-control" name="start_date" value="<?php echo e(old('start_date', $plan?->start_date?->format('Y-m-d') ?? now()->format('Y-m-d'))); ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('End Date')); ?> <span class="required">*</span></label>
                        <input type="date" class="form-control" name="end_date" value="<?php echo e(old('end_date', $plan?->end_date?->format('Y-m-d'))); ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Status')); ?> <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            <?php $__currentLoopData = garmentPlanStatuses(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $statusValue => $statusData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($statusValue); ?>" <?php if(old('status', $plan?->status ?? GARMENT_PLAN_STATUS_DRAFT) == $statusValue): echo 'selected'; endif; ?>>
                                    <?php echo e(__($statusData[0])); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Notes')); ?></label>
                        <textarea class="form-control" name="notes" rows="2" placeholder="<?php echo e(__('Add line allocation or capacity notes...')); ?>"><?php echo e(old('notes', $plan?->notes)); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
            <button type="submit" class="primary-btn"><?php echo e($plan ? __('Update') : __('Save')); ?></button>
        </div>
    </div>
</form>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\garments\plans\form.blade.php ENDPATH**/ ?>