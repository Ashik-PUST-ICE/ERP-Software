<?php $__env->startPush('title'); ?> <?php echo e($title); ?> <?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
<script src="<?php echo e(asset('admin/js/garment-merchandiser.js')); ?>?ver=<?php echo e(env('VERSION', 0)); ?>"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<input type="hidden" id="merchandiser-data-url" value="<?php echo e(route('admin.garments.merchandiser.index')); ?>">

<div class="section-title"><h2 class="title"><?php echo e(__($title)); ?></h2></div>

<div class="row g-4">
    <div class="col-xl-7">
        <div class="section-wrap p-4">
            <h4 class="mb-3"><?php echo e(__('Order Follow-up')); ?></h4>
            <div class="table-responsive">
                <table class="display primary-table w-100" id="merchandiserOrdersTable">
                    <thead>
                        <tr>
                            <th class="keep-show"><?php echo e(__('Order')); ?></th>
                            <th><?php echo e(__('Buyer')); ?></th>
                            <th><?php echo e(__('Delivery')); ?></th>
                            <th class="keep-show"><?php echo e(__('Qty')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td colspan="4" class="text-center text-muted py-3"><i class="fa fa-spinner fa-spin"></i> <?php echo e(__('Loading...')); ?></td></tr>
                    </tbody>
                </table>
            </div>
            <div id="merchandiser-orders-pagination-wrap" class="d-flex justify-content-center mt-20 tablePagi"></div>
        </div>
    </div>
    <div class="col-xl-5">
        <div class="section-wrap p-4">
            <h4 class="mb-3"><?php echo e(__('Overdue TNA Tasks')); ?></h4>
            <div id="merchandiserOverdueTasks"><div class="text-center text-muted py-3"><i class="fa fa-spinner fa-spin"></i> <?php echo e(__('Loading...')); ?></div></div>
        </div>
    </div>
    <div class="col-12">
        <div class="section-wrap p-4">
            <h4 class="mb-3"><?php echo e(__('Shipment Document Follow-up')); ?></h4>
            <div id="merchandiserShipments"><div class="text-center text-muted py-3"><i class="fa fa-spinner fa-spin"></i> <?php echo e(__('Loading...')); ?></div></div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('auto_posts.admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\garments\merchandiser\index.blade.php ENDPATH**/ ?>