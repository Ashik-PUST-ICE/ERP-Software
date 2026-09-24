<form class="ajax reset" action="<?php echo e($inspection ? route('admin.garments.final-inspections.update', $inspection->id) : route('admin.garments.final-inspections.store')); ?>" method="post" data-handler="commonResponseWithPageLoad">
    <?php echo csrf_field(); ?>
    <?php if($inspection): ?>
        <?php echo method_field('put'); ?>
    <?php endif; ?>
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e($inspection ? __('Edit Final Inspection') : __('Add Final Inspection')); ?></h4>
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
                                <option value="<?php echo e($order->id); ?>" <?php if(old('order_id', $inspection?->order_id) == $order->id): echo 'selected'; endif; ?>><?php echo e($order->order_number); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Inspection Lot')); ?> <span class="required">*</span></label>
                        <input class="form-control" name="inspection_lot" value="<?php echo e(old('inspection_lot', $inspection?->inspection_lot)); ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Date')); ?> <span class="required">*</span></label>
                        <input type="date" class="form-control" name="inspection_date" value="<?php echo e(old('inspection_date', $inspection?->inspection_date?->format('Y-m-d') ?? now()->format('Y-m-d'))); ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Lot Qty')); ?></label>
                        <input type="number" min="0" class="form-control" name="lot_quantity" value="<?php echo e(old('lot_quantity', $inspection?->lot_quantity ?? 0)); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Sample Qty')); ?></label>
                        <input type="number" min="0" class="form-control" name="sample_quantity" value="<?php echo e(old('sample_quantity', $inspection?->sample_quantity ?? 0)); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('AQL Level')); ?></label>
                        <input class="form-control" name="aql_level" value="<?php echo e(old('aql_level', $inspection?->aql_level)); ?>" placeholder="<?php echo e(__('e.g. 2.5')); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Defect Qty')); ?></label>
                        <input type="number" min="0" class="form-control" name="defect_quantity" value="<?php echo e(old('defect_quantity', $inspection?->defect_quantity ?? 0)); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Rejected Qty')); ?></label>
                        <input type="number" min="0" class="form-control" name="rejected_quantity" value="<?php echo e(old('rejected_quantity', $inspection?->rejected_quantity ?? 0)); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Result')); ?> <span class="required">*</span></label>
                        <select class="form-control" name="result" required>
                            <?php $__currentLoopData = garmentInspectionResults(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($value); ?>" <?php if(old('result', $inspection?->result ?? GARMENT_INSPECTION_RESULT_PENDING) == $value): echo 'selected'; endif; ?>><?php echo e(__($status[0])); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Inspector')); ?></label>
                        <input class="form-control" name="inspector_name" value="<?php echo e(old('inspector_name', $inspection?->inspector_name)); ?>">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Notes')); ?></label>
                        <textarea class="form-control" name="notes" rows="3"><?php echo e(old('notes', $inspection?->notes)); ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
            <button class="primary-btn" type="submit"><?php echo e($inspection ? __('Update') : __('Save')); ?></button>
        </div>
    </div>
</form>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\garments\final-inspections\form.blade.php ENDPATH**/ ?>