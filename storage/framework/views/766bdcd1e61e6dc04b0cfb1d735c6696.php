<form class="ajax reset" action="<?php echo e($style ? route('admin.garments.styles.update', $style->id) : route('admin.garments.styles.store')); ?>" method="post" data-handler="commonResponseWithPageLoad">
    <?php echo csrf_field(); ?>
    <?php if($style): ?>
        <?php echo method_field('put'); ?>
    <?php endif; ?>
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e($style ? __('Edit Style') : __('Add Style')); ?></h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>

        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Style Code')); ?> <span class="required">*</span></label>
                        <input type="text" class="form-control" name="style_code" value="<?php echo e(old('style_code', $style?->style_code)); ?>" placeholder="<?php echo e(__('e.g. ST-2026-001')); ?>" required>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Style Name')); ?> <span class="required">*</span></label>
                        <input type="text" class="form-control" name="style_name" value="<?php echo e(old('style_name', $style?->style_name)); ?>" placeholder="<?php echo e(__('e.g. Basic Polo Shirt')); ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Product Type')); ?></label>
                        <input type="text" class="form-control" name="product_type" value="<?php echo e(old('product_type', $style?->product_type)); ?>" placeholder="<?php echo e(__('e.g. Knit, Woven, Denim')); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Season')); ?></label>
                        <input type="text" class="form-control" name="season" value="<?php echo e(old('season', $style?->season)); ?>" placeholder="<?php echo e(__('e.g. SS26')); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Status')); ?> <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            <option value="<?php echo e(STATUS_ACTIVE); ?>" <?php if(($style?->status ?? STATUS_ACTIVE) == STATUS_ACTIVE): echo 'selected'; endif; ?>><?php echo e(__('Active')); ?></option>
                            <option value="<?php echo e(STATUS_DEACTIVATE); ?>" <?php if(($style?->status ?? STATUS_ACTIVE) == STATUS_DEACTIVATE): echo 'selected'; endif; ?>><?php echo e(__('Deactivate')); ?></option>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Description')); ?></label>
                        <textarea class="form-control" name="description" rows="2" placeholder="<?php echo e(__('Describe the garment style and specifications')); ?>"><?php echo e(old('description', $style?->description)); ?></textarea>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Notes')); ?></label>
                        <textarea class="form-control" name="notes" rows="2" placeholder="<?php echo e(__('Add internal notes...')); ?>"><?php echo e(old('notes', $style?->notes)); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
            <button type="submit" class="primary-btn"><?php echo e($style ? __('Update') : __('Save')); ?></button>
        </div>
    </div>
</form>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\garments\styles\form.blade.php ENDPATH**/ ?>