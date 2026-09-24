<form class="ajax reset" action="<?php echo e($production ? route('admin.garments.sewing.update', $production->id) : route('admin.garments.sewing.store')); ?>" method="post" data-handler="commonResponseWithPageLoad">
    <?php echo csrf_field(); ?>
    <?php if($production): ?>
        <?php echo method_field('put'); ?>
    <?php endif; ?>
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e($production ? __('Edit Sewing Production') : __('Add Sewing Production')); ?></h4>
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
                            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($order->id); ?>" <?php if(old('order_id', $production?->order_id) == $order->id): echo 'selected'; endif; ?>>
                                    <?php echo e($order->order_number); ?> | <?php echo e($order->style?->style_code); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Line Name')); ?> <span class="required">*</span></label>
                        <input class="form-control" name="line_name" value="<?php echo e(old('line_name', $production?->line_name)); ?>" placeholder="<?php echo e(__('e.g. Line 01')); ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Production Date')); ?> <span class="required">*</span></label>
                        <input type="date" class="form-control" name="production_date" value="<?php echo e(old('production_date', $production?->production_date?->format('Y-m-d') ?? now()->format('Y-m-d'))); ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Status')); ?> <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            <?php $__currentLoopData = garmentSewingStatuses(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($value); ?>" <?php if(old('status', $production?->status ?? GARMENT_SEWING_STATUS_RUNNING) == $value): echo 'selected'; endif; ?>>
                                    <?php echo e(__($status[0])); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('WIP Quantity')); ?></label>
                        <input type="number" min="0" class="form-control" name="wip_quantity" value="<?php echo e(old('wip_quantity', $production?->wip_quantity ?? 0)); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Daily Target')); ?></label>
                        <input type="number" min="0" class="form-control" name="daily_target" value="<?php echo e(old('daily_target', $production?->daily_target ?? 0)); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Hourly Target')); ?></label>
                        <input type="number" min="0" class="form-control" name="hourly_target" value="<?php echo e(old('hourly_target', $production?->hourly_target ?? 0)); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Hourly Output')); ?></label>
                        <input type="number" min="0" class="form-control" name="hourly_output" value="<?php echo e(old('hourly_output', $production?->hourly_output ?? 0)); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Total Output')); ?></label>
                        <input type="number" min="0" class="form-control" name="total_output" value="<?php echo e(old('total_output', $production?->total_output ?? 0)); ?>">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Notes')); ?></label>
                        <textarea class="form-control" name="notes" rows="2" placeholder="<?php echo e(__('Sewing production notes...')); ?>"><?php echo e(old('notes', $production?->notes)); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
            <button type="submit" class="primary-btn"><?php echo e($production ? __('Update') : __('Save')); ?></button>
        </div>
    </div>
</form>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\garments\sewing\form.blade.php ENDPATH**/ ?>