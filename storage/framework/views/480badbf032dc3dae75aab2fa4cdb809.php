<form class="ajax reset grn-form" action="<?php echo e($grn ? route('admin.garments.grns.update', $grn->id) : route('admin.garments.grns.store')); ?>" method="post" data-handler="commonResponseWithPageLoad">
    <?php echo csrf_field(); ?>
    <?php if($grn): ?>
        <?php echo method_field('put'); ?>
    <?php endif; ?>
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e($grn ? __('Edit GRN') : __('Add Goods Received Note')); ?></h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>

        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('GRN Number')); ?> <span class="required">*</span></label>
                        <input type="text" class="form-control" name="grn_number" value="<?php echo e(old('grn_number', $grn?->grn_number)); ?>" placeholder="<?php echo e(__('e.g. GRN-2026-001')); ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Material')); ?> <span class="required">*</span></label>
                        <select class="form-control" name="material_id" required>
                            <option value=""><?php echo e(__('Select Material')); ?></option>
                            <?php $__currentLoopData = $materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $materialOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($materialOption->id); ?>" <?php if(old('material_id', $grn?->material_id) == $materialOption->id): echo 'selected'; endif; ?>>
                                    <?php echo e($materialOption->item_code); ?> - <?php echo e($materialOption->item_name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Received Date')); ?> <span class="required">*</span></label>
                        <input type="date" class="form-control" name="received_date" value="<?php echo e(old('received_date', $grn?->received_date?->format('Y-m-d') ?? now()->format('Y-m-d'))); ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Supplier Name')); ?> <span class="required">*</span></label>
                        <input type="text" class="form-control" name="supplier_name" value="<?php echo e(old('supplier_name', $grn?->supplier_name)); ?>" placeholder="<?php echo e(__('Enter supplier name')); ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Purchase Order')); ?></label>
                        <select class="form-control" name="purchase_order_id">
                            <option value=""><?php echo e(__('Not linked')); ?></option>
                            <?php $__currentLoopData = $purchaseOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $purchaseOrder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($purchaseOrder->id); ?>" <?php if(old('purchase_order_id', $grn?->purchase_order_id) == $purchaseOrder->id): echo 'selected'; endif; ?>>
                                    <?php echo e($purchaseOrder->po_number); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Ordered Qty')); ?></label>
                        <input type="number" min="0" step="0.0001" class="form-control" name="ordered_quantity" value="<?php echo e(old('ordered_quantity', $grn?->ordered_quantity ?? 0)); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Received Qty')); ?> <span class="required">*</span></label>
                        <input type="number" min="0.0001" step="0.0001" class="form-control grn-quantity" id="grn-received-quantity" name="received_quantity" value="<?php echo e(old('received_quantity', $grn?->received_quantity ?? 0)); ?>" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Rejected Qty')); ?></label>
                        <input type="number" min="0" step="0.0001" class="form-control grn-quantity" id="grn-rejected-quantity" name="rejected_quantity" value="<?php echo e(old('rejected_quantity', $grn?->rejected_quantity ?? 0)); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Unit Cost')); ?></label>
                        <input type="number" min="0" step="0.0001" class="form-control" name="unit_cost" value="<?php echo e(old('unit_cost', $grn?->unit_cost ?? 0)); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Status')); ?> <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            <?php $__currentLoopData = garmentGrnStatuses(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $statusValue => $statusData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($statusValue); ?>" <?php if(old('status', $grn?->status ?? GARMENT_GRN_STATUS_RECEIVED) == $statusValue): echo 'selected'; endif; ?>>
                                    <?php echo e(__($statusData[0])); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Purchase Reference')); ?></label>
                        <input type="text" class="form-control" name="purchase_reference" value="<?php echo e(old('purchase_reference', $grn?->purchase_reference)); ?>" placeholder="<?php echo e(__('PO or invoice reference')); ?>">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Notes')); ?></label>
                        <textarea class="form-control" name="notes" rows="2" placeholder="<?php echo e(__('Add receiving or quality notes...')); ?>"><?php echo e(old('notes', $grn?->notes)); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
            <button type="submit" class="primary-btn"><?php echo e($grn ? __('Update') : __('Save')); ?></button>
        </div>
    </div>
</form>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\garments\grns\form.blade.php ENDPATH**/ ?>