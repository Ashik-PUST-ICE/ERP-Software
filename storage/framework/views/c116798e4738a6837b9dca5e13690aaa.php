<form class="ajax reset" action="<?php echo e($purchaseOrder ? route('admin.garments.purchase-orders.update', $purchaseOrder->id) : route('admin.garments.purchase-orders.store')); ?>" method="post" data-handler="commonResponseWithPageLoad">
    <?php echo csrf_field(); ?>
    <?php if($purchaseOrder): ?>
        <?php echo method_field('put'); ?>
    <?php endif; ?>
    <?php ($existingItem = $purchaseOrder?->items?->first()); ?>
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e($purchaseOrder ? __('Edit Purchase Order') : __('Add Purchase Order')); ?></h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>

        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Supplier')); ?> <span class="required">*</span></label>
                        <select class="form-control" name="supplier_id" required>
                            <option value=""><?php echo e(__('Select Supplier')); ?></option>
                            <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($supplier->id); ?>" <?php if(old('supplier_id', $purchaseOrder?->supplier_id) == $supplier->id): echo 'selected'; endif; ?>>
                                    <?php echo e($supplier->company_name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('PO Number')); ?> <span class="required">*</span></label>
                        <input type="text" class="form-control" name="po_number" value="<?php echo e(old('po_number', $purchaseOrder?->po_number)); ?>" placeholder="<?php echo e(__('e.g. PO-2026-001')); ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Order Date')); ?> <span class="required">*</span></label>
                        <input type="date" class="form-control" name="order_date" value="<?php echo e(old('order_date', $purchaseOrder?->order_date?->format('Y-m-d') ?? now()->format('Y-m-d'))); ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Expected Date')); ?></label>
                        <input type="date" class="form-control" name="expected_date" value="<?php echo e(old('expected_date', $purchaseOrder?->expected_date?->format('Y-m-d'))); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Total Amount')); ?> <span class="required">*</span></label>
                        <input type="number" min="0" step="0.01" class="form-control" name="total_amount" value="<?php echo e(old('total_amount', $purchaseOrder?->total_amount ?? 0)); ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Status')); ?> <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            <option value="<?php echo e(STATUS_PENDING); ?>" <?php if(old('status', $purchaseOrder?->status) == STATUS_PENDING): echo 'selected'; endif; ?>><?php echo e(__('Pending')); ?></option>
                            <option value="<?php echo e(STATUS_ACTIVE); ?>" <?php if(old('status', $purchaseOrder?->status) == STATUS_ACTIVE): echo 'selected'; endif; ?>><?php echo e(__('Approved')); ?></option>
                            <option value="<?php echo e(STATUS_CANCELLED); ?>" <?php if(old('status', $purchaseOrder?->status) == STATUS_CANCELLED): echo 'selected'; endif; ?>><?php echo e(__('Cancelled')); ?></option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Notes')); ?></label>
                        <input type="text" class="form-control" name="notes" value="<?php echo e(old('notes', $purchaseOrder?->notes)); ?>" placeholder="<?php echo e(__('Remarks / terms...')); ?>">
                    </div>
                </div>

                <div class="col-12"><hr class="my-2"></div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Material Line Item')); ?></label>
                        <select class="form-control" name="items[0][material_id]">
                            <option value=""><?php echo e(__('Optional material line')); ?></option>
                            <?php $__currentLoopData = $materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($material->id); ?>" <?php if($existingItem?->material_id == $material->id): echo 'selected'; endif; ?>>
                                    <?php echo e($material->item_code); ?> - <?php echo e($material->item_name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Quantity')); ?></label>
                        <input type="number" min="0" step="0.0001" class="form-control" name="items[0][quantity]" value="<?php echo e($existingItem?->quantity); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Unit Rate')); ?></label>
                        <input type="number" min="0" step="0.0001" class="form-control" name="items[0][unit_rate]" value="<?php echo e($existingItem?->unit_rate); ?>">
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
            <button type="submit" class="primary-btn"><?php echo e($purchaseOrder ? __('Update') : __('Save')); ?></button>
        </div>
    </div>
</form>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\garments\purchase-orders\form.blade.php ENDPATH**/ ?>