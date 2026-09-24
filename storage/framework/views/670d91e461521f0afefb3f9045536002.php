<form class="ajax reset" action="<?php echo e($cutting ? route('admin.garments.cutting.update', $cutting->id) : route('admin.garments.cutting.store')); ?>" method="post" data-handler="commonResponseWithPageLoad">
    <?php echo csrf_field(); ?>
    <?php if($cutting): ?>
        <?php echo method_field('put'); ?>
    <?php endif; ?>
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e($cutting ? __('Edit Cutting Record') : __('Add Cutting Record')); ?></h4>
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
                                <option value="<?php echo e($order->id); ?>" <?php if(old('order_id', $cutting?->order_id) == $order->id): echo 'selected'; endif; ?>>
                                    <?php echo e($order->order_number); ?> | <?php echo e($order->style?->style_code); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Fabric Material')); ?></label>
                        <select class="form-control" name="material_id">
                            <option value=""><?php echo e(__('Select Material')); ?></option>
                            <?php $__currentLoopData = $materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($material->id); ?>" <?php if(old('material_id', $cutting?->material_id) == $material->id): echo 'selected'; endif; ?>>
                                    <?php echo e($material->item_code); ?> - <?php echo e($material->item_name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Marker Number')); ?></label>
                        <input class="form-control" name="marker_number" value="<?php echo e(old('marker_number', $cutting?->marker_number)); ?>" placeholder="<?php echo e(__('e.g. MK-001')); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Cutting Date')); ?> <span class="required">*</span></label>
                        <input type="date" class="form-control" name="cutting_date" value="<?php echo e(old('cutting_date', $cutting?->cutting_date?->format('Y-m-d') ?? now()->format('Y-m-d'))); ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Status')); ?> <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            <?php $__currentLoopData = garmentCuttingStatuses(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($value); ?>" <?php if(old('status', $cutting?->status ?? GARMENT_CUTTING_STATUS_PLANNED) == $value): echo 'selected'; endif; ?>>
                                    <?php echo e(__($status[0])); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Fabric Consumption')); ?></label>
                        <input type="number" min="0" step="0.0001" class="form-control" name="fabric_consumption" value="<?php echo e(old('fabric_consumption', $cutting?->fabric_consumption ?? 0)); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Marker Efficiency %')); ?></label>
                        <input type="number" min="0" max="100" step="0.0001" class="form-control" name="marker_efficiency" value="<?php echo e(old('marker_efficiency', $cutting?->marker_efficiency ?? 0)); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Planned Cut Qty')); ?></label>
                        <input type="number" min="0" class="form-control" name="planned_cut_quantity" value="<?php echo e(old('planned_cut_quantity', $cutting?->planned_cut_quantity ?? 0)); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Cut Qty')); ?></label>
                        <input type="number" min="0" class="form-control" name="cut_quantity" value="<?php echo e(old('cut_quantity', $cutting?->cut_quantity ?? 0)); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Panel Qty')); ?></label>
                        <input type="number" min="0" class="form-control" name="panel_quantity" value="<?php echo e(old('panel_quantity', $cutting?->panel_quantity ?? 0)); ?>">
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Notes')); ?></label>
                        <textarea class="form-control" name="notes" rows="2" placeholder="<?php echo e(__('Cutting notes...')); ?>"><?php echo e(old('notes', $cutting?->notes)); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
            <button type="submit" class="primary-btn"><?php echo e($cutting ? __('Update') : __('Save')); ?></button>
        </div>
    </div>
</form>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\garments\cutting\form.blade.php ENDPATH**/ ?>