<?php $__env->startPush('title'); ?> <?php echo e($title); ?> <?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
<div class="section-title">
    <h2 class="title"><?php echo e(__($title)); ?></h2>
    <button class="primary-btn" data-bs-toggle="modal" data-bs-target="#add-modal">
        <i class="fa fa-plus me-2"></i><?php echo e(__('Add Inspection')); ?>

    </button>
</div>
<div class="settings-page-area">
    <div class="settings-page-right">
        <div class="section-wrap">
            <div class="table-waraper">
                <div class="search-input-wrap mb-3">
                    <label class="icon" for="finalInspectionSearch">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </label>
                    <input class="search-input" id="finalInspectionSearch" placeholder="<?php echo e(__('Search lot or order...')); ?>">
                </div>
                <input type="hidden" id="final-inspection-route" value="<?php echo e(route('admin.garments.final-inspections.index')); ?>">
                <table class="display primary-table dataTable dtr-inline" id="garmentFinalInspectionDataTable">
                    <thead>
                        <tr>
                            <th class="keep-show"><?php echo e(__('SL')); ?></th>
                            <th><?php echo e(__('Order')); ?></th>
                            <th><?php echo e(__('Lot')); ?></th>
                            <th><?php echo e(__('Sample / Lot')); ?></th>
                            <th><?php echo e(__('Date')); ?></th>
                            <th><?php echo e(__('Result')); ?></th>
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
            <?php echo $__env->make('admin.garments.final-inspections.form', ['inspection' => null, 'orders' => $orders], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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
<script src="<?php echo e(asset('admin/js/garment-quality.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('auto_posts.admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\garments\final-inspections\index.blade.php ENDPATH**/ ?>