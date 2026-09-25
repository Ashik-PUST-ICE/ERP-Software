<form class="ajax reset" action="<?php echo e($profitLoss ? route('admin.garments.profit-loss.update', $profitLoss->id) : route('admin.garments.profit-loss.store')); ?>" method="post" data-handler="commonResponseWithPageLoad">
    <?php echo csrf_field(); ?>
    <?php if($profitLoss): ?>
        <?php echo method_field('put'); ?>
    <?php endif; ?>
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e($profitLoss ? __('Edit Order Profit & Loss') : __('Add Order Profit & Loss')); ?></h4>
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
                                <option value="<?php echo e($order->id); ?>" <?php if(old('order_id', $profitLoss?->order_id) == $order->id): echo 'selected'; endif; ?>><?php echo e($order->order_number); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Sales Revenue')); ?> <span class="required">*</span></label>
                        <input type="number" min="0" step="0.0001" class="form-control" name="sales_revenue" value="<?php echo e(old('sales_revenue', $profitLoss?->sales_revenue ?? 0)); ?>" required>
                    </div>
                </div>
                <?php $__currentLoopData = ['material_cost' => 'Material Cost', 'production_cost' => 'Production Cost', 'salary_cost' => 'Salary Cost', 'overhead_cost' => 'Overhead Cost', 'other_cost' => 'Other Cost']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label"><?php echo e(__($label)); ?> <span class="required">*</span></label>
                            <input type="number" min="0" step="0.0001" class="form-control" name="<?php echo e($field); ?>" value="<?php echo e(old($field, $profitLoss?->{$field} ?? 0)); ?>" required>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Status')); ?> <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            <?php $__currentLoopData = garmentProfitLossStatuses(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($value); ?>" <?php if(old('status', $profitLoss?->status ?? GARMENT_PROFIT_LOSS_STATUS_DRAFT) == $value): echo 'selected'; endif; ?>><?php echo e(__($status[0])); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Notes')); ?></label>
                        <textarea class="form-control" name="notes" rows="3"><?php echo e(old('notes', $profitLoss?->notes)); ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
            <button class="primary-btn" type="submit"><?php echo e($profitLoss ? __('Update') : __('Save')); ?></button>
        </div>
    </div>
</form>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\garments\profit-loss\form.blade.php ENDPATH**/ ?>