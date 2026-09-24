<?php $__env->startPush('title'); ?> <?php echo e($title); ?> <?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
<script src="<?php echo e(asset('admin/js/garment-merchandiser-insights.js')); ?>?ver=<?php echo e(config('app.version', 0)); ?>"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="garment-page" data-card-data-url="<?php echo e(route('admin.garments.merchandiser.insights.data')); ?>">
    <input type="hidden" id="garment-merchandiser-insights-data-url" value="<?php echo e(route('admin.garments.merchandiser.insights.data')); ?>">
    <div class="section-title">
        <h2 class="title"><?php echo e(__($title)); ?></h2>
        <span class="text-muted" style="font-size:1.3rem;"><?php echo e(now()->format('l, d F Y')); ?></span>
    </div>

    <div class="row gy-4 mb-20 garment-dashboard-kpis merchandiser-insights-kpis">
        <?php $__currentLoopData = [
            ['insightOrders', $orderCount, 'Assigned Orders', 'fa-list-check'],
            ['insightOverdueTasks', $overdueTasks, 'Overdue Tasks', 'fa-calendar-xmark'],
            ['insightDeliveryRisk', $deliveryRisk, 'Delivery Risk (7 Days)', 'fa-triangle-exclamation'],
            ['insightBuyers', $buyerCount, 'Active Buyers', 'fa-users'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
                <div class="card-box">
                    <span class="card-icon"><i class="fa-solid <?php echo e($card[3]); ?>"></i></span>
                    <div class="card-info"><h2 id="<?php echo e($card[0]); ?>"><?php echo e(number_format($card[1])); ?></h2><h3><?php echo e(__($card[2])); ?></h3></div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="row gy-4">
        <div class="col-xl-6">
            <div class="section-wrap p-4 h-100 merchandiser-panel">
                <div class="section-small-title"><h3 class="title"><?php echo e(__('Order Handover')); ?></h3><i class="fa-solid fa-right-left text-primary"></i></div>
                <div class="merchandiser-handover-cta">
                    <div class="merchandiser-cta-graphic"><i class="fa-solid fa-people-arrows"></i><span><i class="fa-solid fa-arrow-right"></i></span><i class="fa-solid fa-user-tie"></i></div>
                    <h4><?php echo e(__('Assign a new merchandiser')); ?></h4>
                    <p><?php echo e(__('Move an order to another merchandiser with notes and a complete handover history.')); ?></p>
                    <button type="button" class="primary-btn merchandiser-open-modal" data-bs-toggle="modal" data-bs-target="#orderHandoverModal"><i class="fa-solid fa-right-left me-1"></i><?php echo e(__('Hand Over Order')); ?></button>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="section-wrap p-4 h-100 merchandiser-panel">
                <div class="section-small-title"><h3 class="title"><?php echo e(__('Handover History')); ?></h3><i class="fa-solid fa-clock-rotate-left text-primary"></i></div>
                <div id="merchandiserHandoverHistory">
                    <?php $__empty_1 = true; $__currentLoopData = $handovers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $handover): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="merchandiser-handover-item"><strong><?php echo e($handover->order?->order_number); ?></strong><p><?php echo e($handover->fromUser?->name); ?> <span>→</span> <?php echo e($handover->toUser?->name); ?></p><small><?php echo e($handover->handed_over_at?->format('d M Y, h:i A')); ?></small></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="merchandiser-empty"><i class="fa-solid fa-clock"></i><span><?php echo e(__('No handover records')); ?></span></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <button type="button" class="mt-2" data-card-retry hidden><?php echo e(__('Retry loading metrics')); ?></button>
</div>

<div class="modal fade zModalTwo" id="orderHandoverModal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            <form method="post" action="<?php echo e(route('admin.garments.merchandiser.handover')); ?>" class="merchandiser-handover-form">
                <?php echo csrf_field(); ?>
                <div class="modal-body zModalTwo-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="merchandiser-form-heading mb-0 pb-0 border-0"><span class="merchandiser-form-icon"><i class="fa-solid fa-people-arrows"></i></span><div><h4><?php echo e(__('Assign a new merchandiser')); ?></h4><p><?php echo e(__('Keep order ownership and notes in one place.')); ?></p></div></div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="primary-form">
                        <div class="row gy-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label" for="insightOrderSelect"><?php echo e(__('Order')); ?> <span class="required">*</span></label>
                                    <select class="form-control" name="order_id" id="insightOrderSelect" required><option value=""><?php echo e(__('Select Order')); ?></option><?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($order->id); ?>"><?php echo e($order->order_number); ?> - <?php echo e($order->buyer?->company_name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label" for="insightUserSelect"><?php echo e(__('New Merchandiser')); ?> <span class="required">*</span></label>
                                    <select class="form-control" name="to_user_id" id="insightUserSelect" required><option value=""><?php echo e(__('New Merchandiser')); ?></option><?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label" for="handoverNotes"><?php echo e(__('Handover Notes')); ?> <span class="required">*</span></label>
                                    <textarea class="form-control" name="notes" id="handoverNotes" rows="3" placeholder="<?php echo e(__('Handover notes')); ?>" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn-list mt-4 pt-3 border-top"><button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button><button class="primary-btn merchandiser-submit" type="submit"><i class="fa-solid fa-paper-plane"></i><?php echo e(__('Hand Over Order')); ?></button></div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('auto_posts.admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\garments\merchandiser\insights.blade.php ENDPATH**/ ?>