<form class="ajax reset" action="<?php echo e(route('super_admin.users.update', $user->id)); ?>" method="post" data-handler="commonResponseWithPageLoad">
    <?php echo csrf_field(); ?>
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e(__('Edit User')); ?></h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
        </div>
        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-12">
                    <div class="form-group">
                        <label for="name" class="form-label"><?php echo e(__('Name')); ?><span class="required">*</span></label>
                        <input type="text" class="form-control" name="name" value="<?php echo e($user->name); ?>" required>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="email" class="form-label"><?php echo e(__('Email')); ?><span class="required">*</span></label>
                        <input type="email" class="form-control" name="email" value="<?php echo e($user->email); ?>" required>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="mobile" class="form-label"><?php echo e(__('Mobile')); ?></label>
                        <input type="text" class="form-control" name="mobile" value="<?php echo e($user->mobile); ?>">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="status" class="form-label"><?php echo e(__('Status')); ?><span class="required">*</span></label>
                        <select class="form-control select wide sf-select-without-search" name="status" required>
                            <option value="1" <?php echo e($user->status == 1 ? 'selected' : ''); ?>><?php echo e(__('Active')); ?></option>
                            <option value="3" <?php echo e($user->status == 3 ? 'selected' : ''); ?>><?php echo e(__('Deactivate')); ?></option>
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
</form><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\super_admin\users\edit.blade.php ENDPATH**/ ?>