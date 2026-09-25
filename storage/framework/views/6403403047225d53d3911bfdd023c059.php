<form class="ajax reset" action="<?php echo e($issue ? route('admin.garments.issues.update', $issue->id) : route('admin.garments.issues.store')); ?>" method="post" data-handler="commonResponseWithPageLoad">
    <?php echo csrf_field(); ?>
    <?php if($issue): ?>
        <?php echo method_field('put'); ?>
    <?php endif; ?>
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e($issue ? __('Edit Store Issue') : __('Issue Material')); ?></h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>

        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Issue Number')); ?> <span class="required">*</span></label>
                        <input type="text" class="form-control" name="issue_number" value="<?php echo e(old('issue_number', $issue?->issue_number)); ?>" placeholder="<?php echo e(__('e.g. ISS-2026-001')); ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Material')); ?> <span class="required">*</span></label>
                        <select class="form-control" name="material_id" required>
                            <option value=""><?php echo e(__('Select Material')); ?></option>
                            <?php $__currentLoopData = $materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($material->id); ?>" <?php if(old('material_id', $issue?->material_id) == $material->id): echo 'selected'; endif; ?>>
                                    <?php echo e($material->item_code); ?> - <?php echo e($material->item_name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Order')); ?></label>
                        <select class="form-control" name="order_id">
                            <option value=""><?php echo e(__('General Store / No Order')); ?></option>
                            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($order->id); ?>" <?php if(old('order_id', $issue?->order_id) == $order->id): echo 'selected'; endif; ?>>
                                    <?php echo e($order->order_number); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Section')); ?> <span class="required">*</span></label>
                        <input type="text" class="form-control" name="section" value="<?php echo e(old('section', $issue?->section)); ?>" placeholder="<?php echo e(__('e.g. Cutting, Sewing, Finishing')); ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Line Name')); ?></label>
                        <input type="text" class="form-control" name="line_name" value="<?php echo e(old('line_name', $issue?->line_name)); ?>" placeholder="<?php echo e(__('e.g. Line 01')); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Issue Date')); ?> <span class="required">*</span></label>
                        <input type="date" class="form-control" name="issue_date" value="<?php echo e(old('issue_date', $issue?->issue_date?->format('Y-m-d') ?? now()->format('Y-m-d'))); ?>" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Issued Qty')); ?> <span class="required">*</span></label>
                        <input type="number" min="0.0001" step="0.0001" class="form-control issue-qty" id="issued-quantity" name="issued_quantity" value="<?php echo e(old('issued_quantity', $issue?->issued_quantity ?? 0)); ?>" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Returned Qty')); ?></label>
                        <input type="number" min="0" step="0.0001" class="form-control issue-qty" id="returned-quantity" name="returned_quantity" value="<?php echo e(old('returned_quantity', $issue?->returned_quantity ?? 0)); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Status')); ?> <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            <?php $__currentLoopData = garmentIssueStatuses(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($value); ?>" <?php if(old('status', $issue?->status ?? GARMENT_ISSUE_STATUS_ISSUED) == $value): echo 'selected'; endif; ?>>
                                    <?php echo e(__($status[0])); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Notes')); ?></label>
                        <textarea class="form-control" name="notes" rows="2" placeholder="<?php echo e(__('Add issue or return notes...')); ?>"><?php echo e(old('notes', $issue?->notes)); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
            <button type="submit" class="primary-btn"><?php echo e($issue ? __('Update') : __('Save')); ?></button>
        </div>
    </div>
</form>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\garments\issues\form.blade.php ENDPATH**/ ?>