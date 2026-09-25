<?php $__env->startPush('title'); ?> <?php echo e($title); ?> <?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="garment-page" data-card-data-url="<?php echo e(route('admin.garments.analytics.api.cards')); ?>">
    <div class="section-title d-flex justify-content-between align-items-center">
        <h2 class="title"><?php echo e(__($title)); ?></h2>
        <a class="primary-btn" href="<?php echo e(route('admin.garments.analytics.index')); ?>">
            <i class="fa-solid fa-arrow-left me-2"></i><?php echo e(__('Back to Analytics')); ?>

        </a>
    </div>

    <div class="row gy-4 mb-20 garment-dashboard-kpis garment-analytics-kpis">
        <?php $__currentLoopData = [['orders','Total Orders','fa-clipboard-list'],['planned_quantity','Planned Quantity','fa-bullseye'],['produced_quantity','Produced Quantity','fa-industry'],['achievement','Plan Achievement','fa-chart-line']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
                <div class="card-box" data-card-key="<?php echo e($card[0]); ?>">
                    <span class="card-icon"><i class="fa-solid <?php echo e($card[2]); ?>"></i></span>
                    <div class="card-info">
                        <h2><span data-card-value><?php echo e(number_format($summary['kpis'][$card[0]], $card[0] === 'achievement' ? 1 : 0)); ?></span><?php echo e($card[0] === 'achievement' ? '%' : ''); ?></h2>
                        <h3><?php echo e(__($card[1])); ?></h3>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="row gy-4">
        <div class="col-xl-8">
            <div class="section-wrap p-4 h-100 garment-analytics-panel">
                <div class="section-small-title"><h3 class="title"><?php echo e(__('Last 14 Days: Target vs Output')); ?></h3><i class="fa-solid fa-chart-line text-primary"></i></div>
                <?php $__empty_1 = true; $__currentLoopData = $summary['daily_output']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="analytics-row">
                        <span><?php echo e($day['date']); ?></span>
                        <div class="analytics-track">
                            <div class="analytics-target" style="width: <?php echo e($day['target_width']); ?>%"></div>
                            <div class="analytics-output" style="width: <?php echo e($day['output_width']); ?>%"></div>
                        </div>
                        <strong><?php echo e(number_format($day['output'])); ?></strong>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-muted"><?php echo e(__('No production data available')); ?></p>
                <?php endif; ?>
                <div class="small mt-3"><span class="legend target"></span><?php echo e(__('Target')); ?> <span class="legend output ms-3"></span><?php echo e(__('Output')); ?></div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="section-wrap p-4 h-100 garment-analytics-panel">
                <div class="section-small-title"><h3 class="title"><?php echo e(__('Order Status')); ?></h3><i class="fa-solid fa-list-check text-primary"></i></div>
                <?php $__empty_1 = true; $__currentLoopData = $summary['order_status']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="analytics-status-row"><span><?php echo e(__('Status')); ?> <?php echo e($item['status']); ?></span><strong><?php echo e($item['total']); ?></strong></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-muted"><?php echo e(__('No order data available')); ?></p>
                <?php endif; ?>
                <div class="analytics-rejection mt-4"><p class="mb-1 text-muted"><?php echo e(__('Quality Rejection Rate')); ?></p><h3><?php echo e($summary['kpis']['rejection_rate']); ?>%</h3></div>
            </div>
        </div>
    </div>
    <button type="button" class="mt-2" data-card-retry hidden><?php echo e(__('Retry loading metrics')); ?></button>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('auto_posts.admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\garments\analytics\data.blade.php ENDPATH**/ ?>