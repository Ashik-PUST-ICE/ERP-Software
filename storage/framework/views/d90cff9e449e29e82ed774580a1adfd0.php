<?php $__env->startPush('title'); ?> <?php echo e($title); ?> <?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="section-title d-flex justify-content-between align-items-center flex-wrap gap-2">
    <h2 class="title"><?php echo e(__($title)); ?></h2>
</div>

<div class="settings-page-area">
    <div class="settings-page-right">
        <div class="section-wrap">
            <div class="table-waraper">
                <div class="search-input-wrap mb-3">
                    <label class="icon" for="searchData">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="#6E5858" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </label>
                    <input type="text" class="search-input" id="searchData" placeholder="<?php echo e(__('Search materials...')); ?>" />
                    <input type="hidden" id="scanner-data-route" value="<?php echo e(route('admin.garments.materials.scanner')); ?>">
                </div>

                <div id="material-scanner-result" class="mb-3"></div>

                <table class="display primary-table dataTable dtr-inline" id="scannerDataTable">
                    <thead>
                        <tr>
                            <th><?php echo e(__('Item Code')); ?></th>
                            <th><?php echo e(__('Item Name')); ?></th>
                            <th><?php echo e(__('Barcode')); ?></th>
                            <th><?php echo e(__('Unit')); ?></th>
                            <th><?php echo e(__('Current Stock')); ?></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
<script src="<?php echo e(asset('admin/js/garment-scanner.js')); ?>?ver=<?php echo e(env('VERSION', 0)); ?>"></script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('auto_posts.admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\garments\materials\scanner.blade.php ENDPATH**/ ?>