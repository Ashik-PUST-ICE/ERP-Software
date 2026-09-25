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
            <form action="<?php echo e(route('admin.garments.merchandiser.communication')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="primary-form">
                    <div class="row gy-3">
                        
                        <div class="col-12">
                            <h5 class="fw-600 mb-2 d-flex align-items-center gap-2" style="color:#0f172a;">
                                <span style="width:4px;height:20px;background:#10b981;border-radius:4px;display:inline-block;"></span>
                                <i class="fa-solid fa-comments text-success"></i>
                                <?php echo e(__('Communication Details')); ?>

                            </h5>
                            <hr style="border-color:#f1f5f9;margin-bottom:16px;">
                        </div>

                        
                        <div class="col-md-4">
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

                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Communication Channel')); ?> <span class="required">*</span></label>
                                <select name="channel" class="form-control" required>
                                    <option value="email"><?php echo e(__('Email')); ?></option>
                                    <option value="phone"><?php echo e(__('Phone Call')); ?></option>
                                    <option value="meeting"><?php echo e(__('In-person / Online Meeting')); ?></option>
                                    <option value="whatsapp"><?php echo e(__('WhatsApp / Messaging')); ?></option>
                                </select>
                            </div>
                        </div>

                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Date & Time')); ?> <span class="required">*</span></label>
                                <input type="datetime-local" name="communicated_at" class="form-control" value="<?php echo e(now()->format('Y-m-d\TH:i')); ?>" required>
                            </div>
                        </div>

                        
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Subject / Topic')); ?></label>
                                <input type="text" name="subject" class="form-control" placeholder="<?php echo e(__('e.g. Fit sample approval & bulk fabric delivery update')); ?>">
                            </div>
                        </div>

                        
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Discussion Summary / Notes')); ?> <span class="required">*</span></label>
                                <textarea name="notes" class="form-control" rows="5" placeholder="<?php echo e(__('Provide detailed summary of communication, decisions made, buyer expectations, or next action items...')); ?>" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="btn-list mt-4 pt-3" style="border-top:2px solid #f1f5f9;">
                    <a href="<?php echo e(route('admin.garments.merchandiser.management')); ?>" class="primary-btn-outline d-inline-flex align-items-center gap-2">
                        <i class="fa fa-arrow-left"></i><?php echo e(__('Cancel')); ?>

                    </a>
                    <button type="submit" class="primary-btn d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i><?php echo e(__('Save Communication Log')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('auto_posts.admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\garments\merchandiser\create-communication.blade.php ENDPATH**/ ?>