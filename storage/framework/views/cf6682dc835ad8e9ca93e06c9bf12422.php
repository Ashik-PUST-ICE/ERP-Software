<form id="edit-user-package-form" method="POST"
    action="<?php echo e(route('super_admin.packages.update_user_package', $userPackage->id)); ?>">
    <?php echo csrf_field(); ?>
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e(__('Edit User Package Status')); ?></h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>
        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-12">
                    <div class="form-group">
                        <label for="status" class="form-label"><?php echo e(__('Status')); ?><span class="required">*</span></label>
                        <select class="select form-control wide sf-select-without-search" id="status" name="status"
                            required>
                            <option value="<?php echo e(STATUS_ACTIVE); ?>"
                                <?php echo e($userPackage->status == STATUS_ACTIVE ? 'selected' : ''); ?>><?php echo e(__('Active')); ?>

                            </option>
                            <option value="<?php echo e(STATUS_CANCELLED); ?>"
                                <?php echo e($userPackage->status == STATUS_CANCELLED ? 'selected' : ''); ?>><?php echo e(__('Deactive')); ?>

                            </option>
                            <option value="<?php echo e(STATUS_REFUND); ?>"
                                <?php echo e($userPackage->status == STATUS_REFUND ? 'selected' : ''); ?>><?php echo e(__('Refund')); ?>

                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
            <button type="submit" class="primary-btn"><?php echo e(__('Update')); ?></button>
        </div>
    </div>
</form>
<script src="<?php echo e(asset('super_admin/js/edit-user-package.js')); ?>"></script><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\super_admin\packages\edit_user_package_form.blade.php ENDPATH**/ ?>