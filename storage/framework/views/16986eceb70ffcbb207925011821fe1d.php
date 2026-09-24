<?php $__env->startPush('title'); ?>
    <?php echo e($title); ?>

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
            <form action="<?php echo e(route('admin.garments.merchandiser.task')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="primary-form">
                    <div class="row gy-3">
                        
                        <div class="col-12">
                            <h5 class="fw-600 mb-2 d-flex align-items-center gap-2" style="color:#0f172a;">
                                <span style="width:4px;height:20px;background:#f59e0b;border-radius:4px;display:inline-block;"></span>
                                <i class="fa-solid fa-list-check text-warning"></i>
                                <?php echo e(__('Task Details & Assignment')); ?>

                            </h5>
                            <hr style="border-color:#f1f5f9;margin-bottom:16px;">
                        </div>

                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Select Garment Order')); ?> <span class="required">*</span></label>
                                <select name="order_id" class="form-control" required>
                                    <option value=""><?php echo e(__('Select order...')); ?></option>
                                    <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($order->id); ?>">
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
                                <label class="form-label"><?php echo e(__('Assigned To (Merchandiser)')); ?> <span class="required">*</span></label>
                                <select name="user_id" class="form-control" required>
                                    <option value=""><?php echo e(__('Select merchandiser...')); ?></option>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?> (<?php echo e($user->email); ?>)</option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Task Title')); ?> <span class="required">*</span></label>
                                <input type="text" name="title" class="form-control" placeholder="<?php echo e(__('e.g. Submit lab dip / trim card to buyer')); ?>" required>
                            </div>
                        </div>

                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Priority')); ?> <span class="required">*</span></label>
                                <select name="priority" class="form-control" required>
                                    <option value="1"><?php echo e(__('Low')); ?></option>
                                    <option value="2" selected><?php echo e(__('Medium')); ?></option>
                                    <option value="3"><?php echo e(__('High')); ?></option>
                                </select>
                            </div>
                        </div>

                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Due Date')); ?></label>
                                <input type="date" name="due_date" class="form-control">
                            </div>
                        </div>

                        
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Notes & Instructions')); ?></label>
                                <textarea name="notes" class="form-control" rows="4" placeholder="<?php echo e(__('Provide detailed task instructions, buyer feedback, or milestones...')); ?>"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="btn-list mt-4 pt-3" style="border-top:2px solid #f1f5f9;">
                    <a href="<?php echo e(route('admin.garments.merchandiser.management')); ?>" class="primary-btn-outline d-inline-flex align-items-center gap-2">
                        <i class="fa fa-arrow-left"></i><?php echo e(__('Cancel')); ?>

                    </a>
                    <button type="submit" class="primary-btn d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-plus"></i><?php echo e(__('Create Task')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('auto_posts.admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\garments\merchandiser\create-task.blade.php ENDPATH**/ ?>