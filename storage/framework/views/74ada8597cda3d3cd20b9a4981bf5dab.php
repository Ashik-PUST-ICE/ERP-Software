<form class="ajax reset costing-form" action="<?php echo e($costing ? route('admin.garments.costings.update', $costing->id) : route('admin.garments.costings.store')); ?>" method="post" data-handler="commonResponseWithPageLoad">
    <?php echo csrf_field(); ?>
    <?php if($costing): ?>
        <?php echo method_field('put'); ?>
    <?php endif; ?>
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e($costing ? __('Edit Costing') : __('Add Costing')); ?></h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>

        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Order')); ?> <span class="required">*</span></label>
                        <select class="form-control" name="order_id" required>
                            <option value=""><?php echo e(__('Select Order')); ?></option>
                            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $orderOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($orderOption->id); ?>" <?php if(old('order_id', $costing?->order_id) == $orderOption->id): echo 'selected'; endif; ?>>
                                    <?php echo e($orderOption->order_number); ?> | <?php echo e($orderOption->style?->style_code); ?> - <?php echo e($orderOption->buyer?->company_name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Status')); ?> <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            <?php $__currentLoopData = garmentCostingStatuses(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $statusValue => $statusData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($statusValue); ?>" <?php if(old('status', $costing?->status ?? GARMENT_COSTING_STATUS_DRAFT) == $statusValue): echo 'selected'; endif; ?>>
                                    <?php echo e(__($statusData[0])); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <?php $__currentLoopData = ['fabric_cost' => 'Fabric Cost', 'trims_cost' => 'Trims Cost', 'accessories_cost' => 'Accessories Cost', 'cm_cost' => 'CM Cost', 'washing_cost' => 'Washing Cost', 'printing_cost' => 'Printing Cost', 'embroidery_cost' => 'Embroidery Cost', 'overhead_cost' => 'Overhead Cost', 'other_cost' => 'Other Cost']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label"><?php echo e(__($label)); ?></label>
                            <input type="number" min="0" step="0.0001" class="form-control costing-component" name="<?php echo e($field); ?>" value="<?php echo e(old($field, $costing?->$field ?? 0)); ?>" data-costing-field="<?php echo e($field); ?>">
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Total Cost')); ?></label>
                        <input type="text" class="form-control" id="costing-total-display" value="0.0000" readonly>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('FOB Price')); ?> <span class="required">*</span></label>
                        <input type="number" min="0" step="0.0001" class="form-control" name="fob_price" id="costing-fob-input" value="<?php echo e(old('fob_price', $costing?->fob_price ?? 0)); ?>" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Expected Profit')); ?></label>
                        <input type="text" class="form-control" id="costing-profit-display" value="0.0000" readonly>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Margin')); ?></label>
                        <input type="text" class="form-control" id="costing-margin-display" value="0.00%" readonly>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Notes')); ?></label>
                        <textarea class="form-control" name="notes" rows="2" placeholder="<?php echo e(__('Add costing assumptions or remarks...')); ?>"><?php echo e(old('notes', $costing?->notes)); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
            <button type="submit" class="primary-btn"><?php echo e($costing ? __('Update') : __('Save')); ?></button>
        </div>
    </div>
</form>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\garments\costings\form.blade.php ENDPATH**/ ?>