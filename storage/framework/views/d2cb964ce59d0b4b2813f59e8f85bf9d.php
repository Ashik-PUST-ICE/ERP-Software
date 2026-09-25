<?php $__env->startPush('title'); ?>
<?php echo e(__($pageTitle)); ?>

<?php $__env->stopPush(); ?>


<?php $__env->startSection('content'); ?>
<div class="section-title">
    <h2 class="title"><?php echo e(__($pageTitle)); ?></h2>
</div>
<div class="settings-page-area refund-request-page-area">
    <div class="settings-page-right">
        <div class="section-wrap">


            <div class="table-waraper">
                <input type="hidden" id="refund-request-route"
                    value="<?php echo e(route('super_admin.subscription-refund.list')); ?>">
                <table class="display primary-table dataTable dtr-inline" id="refundRequestDatatable">
                    <thead>
                        <tr>
                            <th class="keep-show"><?php echo e(__("SL")); ?></th>
                            <th><?php echo e(__("Subscription Type")); ?></th>
                            <th><?php echo e(__("Name")); ?></th>
                            <th><?php echo e(__("Email")); ?></th>
                            <th><?php echo e(__("Package Name")); ?></th>
                            <th><?php echo e(__("Refund Reason")); ?></th>
                            <th><?php echo e(__("Request time")); ?></th>
                            <th><?php echo e(__("Status")); ?></th>
                            <th class="keep-show"><?php echo e(__("Action")); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <div id="refund-pagination-wrap" class="d-flex justify-content-center mt-20 tablePagi"></div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade zModalTwo" id="edit-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content zModalTwo-content">
            <!-- Content loaded via AJAX -->
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
<script src="<?php echo e(asset('admin/js/refund-request.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('auto_posts.super_admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\super_admin\refund-request\index.blade.php ENDPATH**/ ?>