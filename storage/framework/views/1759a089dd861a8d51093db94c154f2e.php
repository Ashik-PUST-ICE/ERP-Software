<form class="ajax reset" action="<?php echo e($material ? route('admin.garments.materials.update', $material->id) : route('admin.garments.materials.store')); ?>" method="post" data-handler="commonResponseWithPageLoad">
    <?php echo csrf_field(); ?>
    <?php if($material): ?>
        <?php echo method_field('put'); ?>
    <?php endif; ?>
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e($material ? __('Edit Material') : __('Add Material')); ?></h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>

        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Item Code')); ?> <span class="required">*</span></label>
                        <input type="text" class="form-control" name="item_code" value="<?php echo e(old('item_code', $material?->item_code)); ?>" placeholder="<?php echo e(__('e.g. FAB-001')); ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Barcode')); ?></label>
                        <input type="text" class="form-control" name="barcode" value="<?php echo e(old('barcode', $material?->barcode)); ?>" placeholder="<?php echo e(__('Auto-generated if blank')); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Status')); ?> <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            <option value="<?php echo e(STATUS_ACTIVE); ?>" <?php if(($material?->status ?? STATUS_ACTIVE) == STATUS_ACTIVE): echo 'selected'; endif; ?>><?php echo e(__('Active')); ?></option>
                            <option value="<?php echo e(STATUS_DEACTIVATE); ?>" <?php if(($material?->status ?? STATUS_ACTIVE) == STATUS_DEACTIVATE): echo 'selected'; endif; ?>><?php echo e(__('Deactivate')); ?></option>
                        </select>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Item Name')); ?> <span class="required">*</span></label>
                        <input type="text" class="form-control" name="item_name" value="<?php echo e(old('item_name', $material?->item_name)); ?>" placeholder="<?php echo e(__('e.g. Cotton Jersey 180 GSM')); ?>" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Category')); ?> <span class="required">*</span></label>
                        <select class="form-control" name="category" required>
                            <option value=""><?php echo e(__('Select Category')); ?></option>
                            <?php $__currentLoopData = garmentMaterialCategories(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoryValue => $categoryLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($categoryValue); ?>" <?php if(old('category', $material?->category) == $categoryValue): echo 'selected'; endif; ?>>
                                    <?php echo e(__($categoryLabel)); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Unit')); ?> <span class="required">*</span></label>
                        <input type="text" class="form-control" name="unit" value="<?php echo e(old('unit', $material?->unit ?? 'pcs')); ?>" placeholder="<?php echo e(__('e.g. kg, meter, pcs')); ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Opening Stock')); ?></label>
                        <input type="number" min="0" step="0.0001" class="form-control" name="opening_stock" value="<?php echo e(old('opening_stock', $material?->opening_stock ?? 0)); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Current Stock')); ?></label>
                        <input type="number" min="0" step="0.0001" class="form-control" name="current_stock" value="<?php echo e(old('current_stock', $material?->current_stock ?? 0)); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Reorder Level')); ?></label>
                        <input type="number" min="0" step="0.0001" class="form-control" name="reorder_level" value="<?php echo e(old('reorder_level', $material?->reorder_level ?? 0)); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Warehouse')); ?></label>
                        <input type="text" class="form-control" name="warehouse" value="<?php echo e(old('warehouse', $material?->warehouse)); ?>" placeholder="<?php echo e(__('e.g. Main Store')); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Location / Rack')); ?></label>
                        <input type="text" class="form-control" name="location" value="<?php echo e(old('location', $material?->location)); ?>" placeholder="<?php echo e(__('e.g. Rack A-03')); ?>">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Notes')); ?></label>
                        <textarea class="form-control" name="notes" rows="2" placeholder="<?php echo e(__('Add material notes...')); ?>"><?php echo e(old('notes', $material?->notes)); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
            <button type="submit" class="primary-btn"><?php echo e($material ? __('Update') : __('Save')); ?></button>
        </div>
    </div>
</form>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\garments\materials\form.blade.php ENDPATH**/ ?>