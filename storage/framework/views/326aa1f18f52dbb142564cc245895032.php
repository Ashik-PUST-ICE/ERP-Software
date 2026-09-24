<?php $__env->startPush('title'); ?>
    <?php echo e($title); ?>

<?php $__env->stopPush(); ?>

<?php $__env->startPush('style'); ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.select2-container--default .select2-selection--multiple {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    min-height: 45px;
    padding: 4px 10px;
    background-color: #fff;
    transition: border-color .2s ease;
}
.select2-container--default.select2-container--focus .select2-selection--multiple {
    border-color: #4778c7;
    outline: 0;
    box-shadow: 0 0 0 3px rgba(71, 120, 199, 0.15);
}
.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #4778c7;
    border: 1px solid #3b66aa;
    color: #ffffff;
    border-radius: 6px;
    padding: 3px 10px;
    font-size: 13px;
    font-weight: 500;
    margin-top: 4px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: #ffffff;
    margin-right: 6px;
    border-right: 1px solid rgba(255, 255, 255, 0.35);
    padding-right: 6px;
    font-weight: bold;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
    color: #fee2e2;
    background-color: transparent;
}
.select2-dropdown {
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    box-shadow: 0 10px 25px rgba(15, 23, 42, 0.1);
    z-index: 9999;
}
.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #4778c7;
    color: #fff;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="section-title">
    <h2 class="title"><?php echo e(__($title)); ?></h2>
    <a href="<?php echo e(route('admin.garments.merchandiser.management')); ?>" class="primary-btn">
        <i class="fa fa-arrow-left me-2"></i><?php echo e(__('Back to Activities')); ?>

    </a>
</div>

<div class="settings-page-area">
    <div class="settings-page-right">
        <div class="section-wrap">
            <form action="<?php echo e(route('admin.garments.merchandiser.assign')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="primary-form">
                    <div class="row gy-3">
                        
                        <div class="col-12">
                            <h5 class="fw-600 mb-2 d-flex align-items-center gap-2" style="color:#0f172a;">
                                <span style="width:4px;height:20px;background:#4778c7;border-radius:4px;display:inline-block;"></span>
                                <i class="fa-solid fa-user-gear text-primary"></i>
                                <?php echo e(__('Order & Merchandiser Assignment')); ?>

                            </h5>
                            <hr style="border-color:#f1f5f9;margin-bottom:16px;">
                        </div>

                        <?php
                            $primaryId = $selectedOrder?->merchandisers->firstWhere('pivot.is_primary', true)?->id;
                            $assignedIds = $selectedOrder ? $selectedOrder->merchandisers->pluck('id')->all() : [];
                        ?>

                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Select Garment Order')); ?> <span class="required">*</span></label>
                                <select name="order_id" class="form-control" required>
                                    <option value=""><?php echo e(__('Select order...')); ?></option>
                                    <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($order->id); ?>" <?php if(isset($selectedOrder) && $selectedOrder->id === $order->id): echo 'selected'; endif; ?>>
                                            <?php echo e($order->order_number); ?>

                                            <?php if($order->buyer): ?> (<?php echo e($order->buyer->company_name); ?>) <?php endif; ?>
                                            <?php if($order->style): ?> - <?php echo e($order->style->style_code); ?> <?php endif; ?>
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Lead / Primary Merchandiser')); ?></label>
                                <select name="primary_user_id" class="form-control">
                                    <option value=""><?php echo e(__('Select lead merchandiser (optional)...')); ?></option>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($user->id); ?>" <?php if(isset($primaryId) && $primaryId === $user->id): echo 'selected'; endif; ?>>
                                            <?php echo e($user->name); ?> (<?php echo e($user->email); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Assign Merchandisers (Team)')); ?> <span class="required">*</span></label>
                                <select class="multipleSelect2" multiple="true" name="user_ids[]" required>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($user->id); ?>" <?php if(in_array($user->id, $assignedIds)): echo 'selected'; endif; ?>>
                                            <?php echo e($user->name); ?> (<?php echo e($user->email); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <small class="text-muted d-block mt-2">
                                    <i class="fa-solid fa-circle-info me-1 text-primary"></i>
                                    <?php echo e(__('Search, select or remove multiple merchandisers for this order team.')); ?>

                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="btn-list mt-4 pt-3" style="border-top:2px solid #f1f5f9;">
                    <a href="<?php echo e(route('admin.garments.merchandiser.management')); ?>" class="primary-btn-outline d-inline-flex align-items-center gap-2">
                        <i class="fa fa-arrow-left"></i><?php echo e(__('Cancel')); ?>

                    </a>
                    <button type="submit" class="primary-btn d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-link"></i><?php echo e(__('Assign Merchandisers')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Select2 Multi-Select matching package create implementation
    $(".multipleSelect2").select2({
        placeholder: "<?php echo e(__('Select Merchandisers...')); ?>",
        allowClear: true,
        width: '100%'
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('auto_posts.admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\garments\merchandiser\assign.blade.php ENDPATH**/ ?>