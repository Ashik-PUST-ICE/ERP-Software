<?php $__env->startPush('title'); ?> <?php echo e($title); ?> <?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
<div class="section-title">
    <h2 class="title"><?php echo e(__($title)); ?></h2>
    <button class="primary-btn" data-bs-toggle="modal" data-bs-target="#add-modal">
        <i class="fa fa-plus me-2"></i><?php echo e(__('Add P&L Record')); ?>

    </button>
</div>
<div class="settings-page-area">
    <div class="settings-page-right">
        <div class="section-wrap">
            <div class="table-waraper">
                <input type="hidden" id="profit-loss-route" value="<?php echo e(route('admin.garments.profit-loss.index')); ?>">
                <table class="display primary-table dataTable dtr-inline" id="garmentProfitLossDataTable">
                    <thead>
                        <tr>
                            <th class="keep-show"><?php echo e(__('SL')); ?></th>
                            <th><?php echo e(__('Order')); ?></th>
                            <th><?php echo e(__('Revenue')); ?></th>
                            <th><?php echo e(__('Total Cost')); ?></th>
                            <th><?php echo e(__('Profit / Margin')); ?></th>
                            <th><?php echo e(__('Status')); ?></th>
                            <th class="keep-show"><?php echo e(__('Action')); ?></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade zModalTwo" id="add-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            <?php echo $__env->make('admin.garments.profit-loss.form', ['profitLoss' => null, 'orders' => $orders], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade zModalTwo" id="edit-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content"></div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('script'); ?>
<script>
$(function () {
    var t = $('#garmentProfitLossDataTable').DataTable({
        pageLength: 10,
        ordering: false,
        serverSide: true,
        processing: true,
        responsive: true,
        dom: 't',
        ajax: $('#profit-loss-route').val(),
        columns: [
            { data: 'sl' },
            { data: 'order_number' },
            { data: 'sales_revenue' },
            { data: 'total_cost' },
            { data: 'profit_display' },
            { data: 'status' },
            { data: 'action' }
        ],
        columnDefs: [{ targets: 'keep-show', className: 'all' }]
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('auto_posts.admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\garments\profit-loss\index.blade.php ENDPATH**/ ?>