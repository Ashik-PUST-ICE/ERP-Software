<form class="ajax reset" action="<?php echo e($entry ? route('admin.garments.accounting.update', $entry->id) : route('admin.garments.accounting.store')); ?>" method="post" data-handler="commonResponseWithPageLoad">
    <?php echo csrf_field(); ?>
    <?php if($entry): ?>
        <?php echo method_field('put'); ?>
    <?php endif; ?>
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e($entry ? __('Edit Accounting Entry') : __('Add Accounting Entry')); ?></h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>
        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Order')); ?></label>
                        <select class="form-control" name="order_id">
                            <option value=""><?php echo e(__('Not linked')); ?></option>
                            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($order->id); ?>" <?php if(old('order_id', $entry?->order_id) == $order->id): echo 'selected'; endif; ?>><?php echo e($order->order_number); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Entry Type')); ?> <span class="required">*</span></label>
                        <select class="form-control" name="entry_type" required>
                            <?php $__currentLoopData = garmentAccountingTypes(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($value); ?>" <?php if(old('entry_type', $entry?->entry_type) == $value): echo 'selected'; endif; ?>><?php echo e(__($label)); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Account Code')); ?> <span class="required">*</span></label>
                        <input class="form-control" name="account_code" value="<?php echo e(old('account_code', $entry?->account_code)); ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Account Name')); ?> <span class="required">*</span></label>
                        <input class="form-control" name="account_name" value="<?php echo e(old('account_name', $entry?->account_name)); ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Entry Date')); ?> <span class="required">*</span></label>
                        <input type="date" class="form-control" name="entry_date" value="<?php echo e(old('entry_date', $entry?->entry_date?->format('Y-m-d') ?? now()->format('Y-m-d'))); ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Debit')); ?></label>
                        <input type="number" min="0" step="0.0001" class="form-control" name="debit" value="<?php echo e(old('debit', $entry?->debit ?? 0)); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Credit')); ?></label>
                        <input type="number" min="0" step="0.0001" class="form-control" name="credit" value="<?php echo e(old('credit', $entry?->credit ?? 0)); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Reference')); ?></label>
                        <input class="form-control" name="reference" value="<?php echo e(old('reference', $entry?->reference)); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Status')); ?> <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            <?php $__currentLoopData = garmentAccountingStatuses(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($value); ?>" <?php if(old('status', $entry?->status ?? GARMENT_ACCOUNTING_ENTRY_DRAFT) == $value): echo 'selected'; endif; ?>><?php echo e(__($status[0])); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Description')); ?></label>
                        <textarea class="form-control" name="description" rows="3"><?php echo e(old('description', $entry?->description)); ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
            <button class="primary-btn" type="submit"><?php echo e($entry ? __('Update') : __('Save')); ?></button>
        </div>
    </div>
</form>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\garments\accounting\form.blade.php ENDPATH**/ ?>