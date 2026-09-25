<form class="ajax reset" action="<?php echo e($buyer ? route('admin.garments.buyers.update', $buyer->id) : route('admin.garments.buyers.store')); ?>" method="post" data-handler="commonResponseWithPageLoad">
    <?php echo csrf_field(); ?>
    <?php if($buyer): ?>
        <?php echo method_field('put'); ?>
    <?php endif; ?>
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e($buyer ? __('Edit Buyer') : __('Add Buyer')); ?></h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>

        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Buyer Code')); ?> <span class="required">*</span></label>
                        <input type="text" class="form-control" name="buyer_code" value="<?php echo e(old('buyer_code', $buyer?->buyer_code)); ?>" placeholder="<?php echo e(__('e.g. BUY-001')); ?>" required>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Company Name')); ?> <span class="required">*</span></label>
                        <input type="text" class="form-control" name="company_name" value="<?php echo e(old('company_name', $buyer?->company_name)); ?>" placeholder="<?php echo e(__('Enter buyer company name')); ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Country')); ?></label>
                        <input type="text" class="form-control" name="country" value="<?php echo e(old('country', $buyer?->country)); ?>" placeholder="<?php echo e(__('e.g. United States')); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Currency')); ?> <span class="required">*</span></label>
                        <input type="text" class="form-control" name="currency" value="<?php echo e(old('currency', $buyer?->currency ?? 'USD')); ?>" placeholder="<?php echo e(__('e.g. USD')); ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Status')); ?> <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            <option value="<?php echo e(STATUS_ACTIVE); ?>" <?php if(($buyer?->status ?? STATUS_ACTIVE) == STATUS_ACTIVE): echo 'selected'; endif; ?>><?php echo e(__('Active')); ?></option>
                            <option value="<?php echo e(STATUS_DEACTIVATE); ?>" <?php if(($buyer?->status ?? STATUS_ACTIVE) == STATUS_DEACTIVATE): echo 'selected'; endif; ?>><?php echo e(__('Deactivate')); ?></option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Contact Person')); ?></label>
                        <input type="text" class="form-control" name="contact_person" value="<?php echo e(old('contact_person', $buyer?->contact_person)); ?>" placeholder="<?php echo e(__('Primary contact name')); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Email Address')); ?></label>
                        <input type="email" class="form-control" name="email" value="<?php echo e(old('email', $buyer?->email)); ?>" placeholder="<?php echo e(__('name@company.com')); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Phone Number')); ?></label>
                        <input type="text" class="form-control" name="phone" value="<?php echo e(old('phone', $buyer?->phone)); ?>" placeholder="<?php echo e(__('Contact phone number')); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Payment Terms')); ?></label>
                        <input type="text" class="form-control" name="payment_terms" value="<?php echo e(old('payment_terms', $buyer?->payment_terms)); ?>" placeholder="<?php echo e(__('e.g. 60 days after shipment')); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Office Address')); ?></label>
                        <textarea class="form-control" name="office_address" rows="2" placeholder="<?php echo e(__('Buyer office address')); ?>"><?php echo e(old('office_address', $buyer?->office_address)); ?></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Notes')); ?></label>
                        <textarea class="form-control" name="notes" rows="2" placeholder="<?php echo e(__('Internal notes...')); ?>"><?php echo e(old('notes', $buyer?->notes)); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
            <button type="submit" class="primary-btn"><?php echo e($buyer ? __('Update') : __('Save')); ?></button>
        </div>
    </div>
</form>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\garments\buyers\form.blade.php ENDPATH**/ ?>