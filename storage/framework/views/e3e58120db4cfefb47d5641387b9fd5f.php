<?php $__env->startPush('title'); ?> <?php echo e($title); ?> <?php $__env->stopPush(); ?>

<?php $__env->startPush('style'); ?>
<style>
.mgmt-quick-card {
    background: #fff;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 18px rgba(15, 23, 42, 0.04);
    border: 1px solid #e2e8f0;
    transition: transform .2s ease, box-shadow .2s ease;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
}
.mgmt-quick-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(15, 23, 42, 0.08);
}
.mgmt-card-icon {
    width: 46px;
    height: 46px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    flex-shrink: 0;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<input type="hidden" id="merchandiser-management-data-route" value="<?php echo e(route('admin.garments.merchandiser.management.data')); ?>">


<div class="section-title d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h2 class="title"><?php echo e(__($title)); ?></h2>
        <p class="text-muted mb-0" style="font-size:13.5px;"><?php echo e(__('Manage merchandiser assignments, task tracking, and buyer communication logs')); ?></p>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-4 col-md-6">
        <div class="mgmt-quick-card" style="border-top: 4px solid #4778c7;">
            <div>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="mgmt-card-icon" style="background:rgba(71,120,199,0.12);color:#4778c7;">
                        <i class="fa-solid fa-user-gear"></i>
                    </div>
                    <span class="zBadge zBadge-primary"><?php echo e(__('Assignments')); ?></span>
                </div>
                <h4 class="fw-700 mb-1" style="color:#0f172a;font-size:1.1rem;"><?php echo e(__('Order Assignments')); ?></h4>
                <p class="text-muted mb-3" style="font-size:13px;line-height:1.5;">
                    <?php echo e(__('Assign primary and team merchandisers to garment orders to streamline order execution.')); ?>

                </p>
            </div>
            <div class="pt-3 border-top d-flex justify-content-between align-items-center">
                <span class="text-muted small"><?php echo e(__('Total Orders:')); ?> <strong id="managementOrderCount">0</strong></span>
                <a href="<?php echo e(route('admin.garments.merchandiser.orders')); ?>" class="primary-btn d-inline-flex align-items-center gap-2 py-2 px-3" style="font-size:12.5px;border-radius:8px;">
                    <i class="fa-solid fa-arrow-right"></i><?php echo e(__('View Orders')); ?>

                </a>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <div class="mgmt-quick-card" style="border-top: 4px solid #f59e0b;">
            <div>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="mgmt-card-icon" style="background:rgba(245,158,11,0.12);color:#f59e0b;">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                    <span class="zBadge zBadge-warning"><?php echo e(__('Tasks')); ?></span>
                </div>
                <h4 class="fw-700 mb-1" style="color:#0f172a;font-size:1.1rem;"><?php echo e(__('Merchandiser Tasks')); ?></h4>
                <p class="text-muted mb-3" style="font-size:13px;line-height:1.5;">
                    <?php echo e(__('Delegate follow-up tasks, trim card submissions, lab dip approvals, and set milestone deadlines.')); ?>

                </p>
            </div>
            <div class="pt-3 border-top d-flex justify-content-between align-items-center">
                <span class="text-muted small"><?php echo e(__('Total Tasks:')); ?> <strong id="managementTaskCount">0</strong></span>
                <a href="<?php echo e(route('admin.garments.merchandiser.tasks')); ?>" class="primary-btn d-inline-flex align-items-center gap-2 py-2 px-3" style="background:#f59e0b;border-color:#f59e0b;font-size:12.5px;border-radius:8px;">
                    <i class="fa-solid fa-arrow-right"></i><?php echo e(__('View Tasks')); ?>

                </a>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <div class="mgmt-quick-card" style="border-top: 4px solid #10b981;">
            <div>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="mgmt-card-icon" style="background:rgba(16,185,129,0.12);color:#10b981;">
                        <i class="fa-solid fa-comments"></i>
                    </div>
                    <span class="zBadge zBadge-complete"><?php echo e(__('Communications')); ?></span>
                </div>
                <h4 class="fw-700 mb-1" style="color:#0f172a;font-size:1.1rem;"><?php echo e(__('Buyer Communication')); ?></h4>
                <p class="text-muted mb-3" style="font-size:13px;line-height:1.5;">
                    <?php echo e(__('Log buyer meetings, emails, and call summaries with decisions and next actions.')); ?>

                </p>
            </div>
            <div class="pt-3 border-top d-flex justify-content-between align-items-center">
                <span class="text-muted small"><?php echo e(__('Total Logs:')); ?> <strong id="managementCommunicationCount">0</strong></span>
                <a href="<?php echo e(route('admin.garments.merchandiser.communications')); ?>" class="primary-btn d-inline-flex align-items-center gap-2 py-2 px-3" style="background:#10b981;border-color:#10b981;font-size:12.5px;border-radius:8px;">
                    <i class="fa-solid fa-arrow-right"></i><?php echo e(__('View Logs')); ?>

                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
<script>
(function ($) {
    "use strict";
    $(document).ready(function () {
        var baseUrl = $('#merchandiser-management-data-route').val() || '';
        if (!baseUrl) return;
        $.get(baseUrl, { section: 'cards' }, function (response) {
            var cards = (response && response.cards) || {};
            $('#managementOrderCount').text(Number(cards.orders || 0).toLocaleString());
            $('#managementTaskCount').text(Number(cards.tasks || 0).toLocaleString());
            $('#managementCommunicationCount').text(Number(cards.communications || 0).toLocaleString());
        });
    });
})(jQuery);
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('auto_posts.admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\garments\merchandiser\management.blade.php ENDPATH**/ ?>