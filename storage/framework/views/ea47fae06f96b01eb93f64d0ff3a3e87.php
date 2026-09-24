<form class="ajax reset" action="<?php echo e($packing ? route('admin.garments.packing-lists.update', $packing->id) : route('admin.garments.packing-lists.store')); ?>" method="post" data-handler="commonResponseWithPageLoad">
    <?php echo csrf_field(); ?>
    <?php if($packing): ?>
        <?php echo method_field('put'); ?>
    <?php endif; ?>
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e($packing ? __('Edit Packing Row') : __('Add Packing Row')); ?></h4>
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
                                <option value="<?php echo e($order->id); ?>" <?php if(old('order_id', $packing?->order_id) == $order->id): echo 'selected'; endif; ?>><?php echo e($order->order_number); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Packing Date')); ?> <span class="required">*</span></label>
                        <input type="date" class="form-control" name="packing_date" value="<?php echo e(old('packing_date', $packing?->packing_date?->format('Y-m-d') ?? now()->format('Y-m-d'))); ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Carton Number')); ?> <span class="required">*</span></label>
                        <input class="form-control" name="carton_number" value="<?php echo e(old('carton_number', $packing?->carton_number)); ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Color')); ?></label>
                        <input class="form-control" name="color" value="<?php echo e(old('color', $packing?->color)); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Size')); ?></label>
                        <input class="form-control" name="size" value="<?php echo e(old('size', $packing?->size)); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Quantity')); ?> <span class="required">*</span></label>
                        <input type="number" min="1" class="form-control" name="quantity" value="<?php echo e(old('quantity', $packing?->quantity ?? 1)); ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Net Weight')); ?> <span class="required">*</span></label>
                        <input type="number" min="0" step="0.001" class="form-control" name="net_weight" value="<?php echo e(old('net_weight', $packing?->net_weight ?? 0)); ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Gross Weight')); ?> <span class="required">*</span></label>
                        <input type="number" min="0" step="0.001" class="form-control" name="gross_weight" value="<?php echo e(old('gross_weight', $packing?->gross_weight ?? 0)); ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Length')); ?></label>
                        <input type="number" min="0" step="0.01" class="form-control" name="carton_length" value="<?php echo e(old('carton_length', $packing?->carton_length)); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Width')); ?></label>
                        <input type="number" min="0" step="0.01" class="form-control" name="carton_width" value="<?php echo e(old('carton_width', $packing?->carton_width)); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Height')); ?></label>
                        <input type="number" min="0" step="0.01" class="form-control" name="carton_height" value="<?php echo e(old('carton_height', $packing?->carton_height)); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Status')); ?> <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            <?php $__currentLoopData = garmentPackingStatuses(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($value); ?>" <?php if(old('status', $packing?->status ?? GARMENT_PACKING_STATUS_DRAFT) == $value): echo 'selected'; endif; ?>><?php echo e(__($status[0])); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Notes')); ?></label>
                        <textarea class="form-control" name="notes" rows="3"><?php echo e(old('notes', $packing?->notes)); ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
            <button class="primary-btn" type="submit"><?php echo e($packing ? __('Update') : __('Save')); ?></button>
        </div>
    </div>
</form>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\garments\packing-lists\form.blade.php ENDPATH**/ ?>