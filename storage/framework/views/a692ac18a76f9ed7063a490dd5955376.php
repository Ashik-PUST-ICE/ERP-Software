<?php $__env->startPush('title'); ?> <?php echo e($title); ?> <?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
<script src="<?php echo e(asset('admin/js/garment-buyer-portal.js')); ?>?ver=<?php echo e(env('VERSION', 0)); ?>"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<input type="hidden" id="buyer-data-route" value="<?php echo e(route('admin.garments.buyer-portal.index')); ?>">

<div class="section-title d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h2 class="title"><?php echo e(__($title)); ?></h2>
        <span class="text-muted" style="font-size:1.3rem;"><?php echo e(now()->format('l, d F Y')); ?></span>
    </div>
    <div class="buyer-portal-selector">
        <i class="fa-solid fa-building-user"></i>
        <select class="form-control" id="buyerPortalBuyerSelect">
        <?php $__currentLoopData = $buyers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($item->id); ?>" <?php if($buyer?->id === $item->id): echo 'selected'; endif; ?>><?php echo e($item->company_name); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
</div>

<div class="row gy-4 mb-20 garment-dashboard-kpis buyer-portal-kpis">
    <div class="col-xl-4 col-lg-6 col-md-6 col-6">
        <div class="card-box">
            <span class="card-icon"><i class="fa-solid fa-user-tie"></i></span>
            <div class="card-info">
                <h2 id="buyerPortalName"><?php echo e($buyer?->company_name ?? '--'); ?></h2>
                <h3 id="buyerPortalEmail"><?php echo e($buyer?->email ?? ''); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-lg-6 col-md-6 col-6">
        <div class="card-box">
            <span class="card-icon"><i class="fa-solid fa-clipboard-list"></i></span>
            <div class="card-info">
                <h2 id="buyerPortalTotalOrders">--</h2>
                <h3><?php echo e(__('Total Orders')); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-lg-6 col-md-6 col-6">
        <div class="card-box">
            <span class="card-icon"><i class="fa-solid fa-boxes-stacked"></i></span>
            <div class="card-info">
                <h2 id="buyerPortalTotalQuantity">--</h2>
                <h3><?php echo e(__('Total Quantity')); ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="section-wrap">
    <div class="section-small-title">
        <h3 class="title"><?php echo e(__('Order Status Overview')); ?></h3>
        <i class="fa-solid fa-chart-simple text-primary"></i>
    </div>
    <div class="table-responsive">
        <table class="display primary-table w-100" id="buyerPortalOrdersTable">
            <thead>
                <tr>
                    <th><?php echo e(__('Order')); ?></th>
                    <th><?php echo e(__('Description')); ?></th>
                    <th><?php echo e(__('Quantity')); ?></th>
                    <th><?php echo e(__('Delivery Date')); ?></th>
                    <th><?php echo e(__('Status')); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="5" class="text-center text-muted py-3"><i class="fa fa-spinner fa-spin"></i> <?php echo e(__('Loading...')); ?></td></tr>
            </tbody>
        </table>
    </div>
    <div id="buyer-portal-pagination-wrap" class="d-flex justify-content-center mt-20 tablePagi"></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('auto_posts.admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\garments\buyer-portal\index.blade.php ENDPATH**/ ?>