<form class="ajax reset" action="<?php echo e(route('admin.garments.merchandiser.assign')); ?>" method="post"
    data-handler="commonResponseForModal">
    <?php echo csrf_field(); ?>
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e(__('Manage Order Merchandisers')); ?></h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>
        <?php
            $primaryId = $selectedOrder?->merchandisers->firstWhere('pivot.is_primary', true)?->id;
            $assignedIds = $selectedOrder ? $selectedOrder->merchandisers->pluck('id')->map(fn ($id) => (int) $id)->all() : [];
        ?>
        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Select Garment Order')); ?> <span class="required">*</span></label>
                        <select name="order_id" class="form-control" required>
                            <option value=""><?php echo e(__('Select order...')); ?></option>
                            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($order->id); ?>" <?php echo e((int) ($selectedOrder->id ?? 0) === $order->id ? 'selected' : ''); ?>><?php echo e($order->order_number); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Lead / Primary Merchandiser')); ?></label>
                        <select name="primary_user_id" class="form-control">
                            <option value=""><?php echo e(__('Select lead (optional)...')); ?></option>
                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($user->id); ?>" <?php echo e((int) $primaryId === $user->id ? 'selected' : ''); ?>><?php echo e($user->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Assign Merchandisers (Team)')); ?> <span class="required">*</span></label>
                        <select class="form-control multiple-basic-single" multiple="multiple" name="user_ids[]" required>
                            <option value=""></option>
                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($user->id); ?>" <?php echo e(in_array($user->id, $assignedIds) ? 'selected' : ''); ?>><?php echo e($user->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
            <button type="submit" class="primary-btn"><?php echo e(__('Save')); ?></button>
        </div>
    </div>
</form>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\garments\merchandiser\edit-assign.blade.php ENDPATH**/ ?>