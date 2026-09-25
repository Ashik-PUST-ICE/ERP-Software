<form class="ajax reset" action="<?php echo e($supplier ? route('admin.garments.suppliers.update', $supplier->id) : route('admin.garments.suppliers.store')); ?>" method="post" data-handler="commonResponseWithPageLoad">
    <?php echo csrf_field(); ?>
    <?php if($supplier): ?>
        <?php echo method_field('put'); ?>
    <?php endif; ?>
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e($supplier ? __('Edit Supplier') : __('Add Supplier')); ?></h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>

        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Supplier Code')); ?> <span class="required">*</span></label>
                        <input type="text" class="form-control" name="supplier_code" value="<?php echo e(old('supplier_code', $supplier?->supplier_code)); ?>" placeholder="<?php echo e(__('e.g. SUP-001')); ?>" required>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Company Name')); ?> <span class="required">*</span></label>
                        <input type="text" class="form-control" name="company_name" value="<?php echo e(old('company_name', $supplier?->company_name)); ?>" placeholder="<?php echo e(__('Enter company name')); ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Contact Person')); ?></label>
                        <input type="text" class="form-control" name="contact_person" value="<?php echo e(old('contact_person', $supplier?->contact_person)); ?>" placeholder="<?php echo e(__('Primary contact')); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Category')); ?></label>
                        <input type="text" class="form-control" name="category" value="<?php echo e(old('category', $supplier?->category)); ?>" placeholder="<?php echo e(__('e.g. Fabric, Trims, Accessories')); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Email')); ?></label>
                        <input type="email" class="form-control" name="email" value="<?php echo e(old('email', $supplier?->email)); ?>" placeholder="<?php echo e(__('supplier@domain.com')); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Phone')); ?></label>
                        <input type="text" class="form-control" name="phone" value="<?php echo e(old('phone', $supplier?->phone)); ?>" placeholder="<?php echo e(__('Phone number')); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Status')); ?> <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            <option value="<?php echo e(STATUS_ACTIVE); ?>" <?php if(($supplier?->status ?? STATUS_ACTIVE) == STATUS_ACTIVE): echo 'selected'; endif; ?>><?php echo e(__('Active')); ?></option>
                            <option value="<?php echo e(STATUS_DEACTIVATE); ?>" <?php if(($supplier?->status ?? STATUS_ACTIVE) == STATUS_DEACTIVATE): echo 'selected'; endif; ?>><?php echo e(__('Deactivate')); ?></option>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Payment Terms')); ?></label>
                        <input type="text" class="form-control" name="payment_terms" value="<?php echo e(old('payment_terms', $supplier?->payment_terms)); ?>" placeholder="<?php echo e(__('e.g. 30 days LC, Cash')); ?>">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Address')); ?></label>
                        <textarea class="form-control" name="address" rows="2" placeholder="<?php echo e(__('Supplier address...')); ?>"><?php echo e(old('address', $supplier?->address)); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
            <button type="submit" class="primary-btn"><?php echo e($supplier ? __('Update') : __('Save')); ?></button>
        </div>
    </div>
</form>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\garments\suppliers\form.blade.php ENDPATH**/ ?>