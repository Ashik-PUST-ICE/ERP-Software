<form class="ajax reset" action="<?php echo e(route('admin.hrm.designations.update', $designation->id)); ?>" method="post"
    data-handler="commonResponseWithPageLoad">
    <?php echo method_field('put'); ?>
    <?php echo csrf_field(); ?>
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e(__('Edit Designation')); ?></h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>
        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Department')); ?> <span class="required">*</span></label>
                        <select class="form-control" name="department_id" required>
                            <option value=""><?php echo e(__('Select Department')); ?></option>
                            <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($dept->id); ?>" <?php echo e($designation->department_id == $dept->id ? 'selected' : ''); ?>><?php echo e($dept->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Designation Name')); ?> <span class="required">*</span></label>
                        <input type="text" class="form-control" name="name" value="<?php echo e($designation->name); ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Code')); ?> <span class="required">*</span></label>
                        <input type="text" class="form-control" name="code" value="<?php echo e($designation->code); ?>" required>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Description')); ?></label>
                        <textarea class="form-control" name="description" rows="2"><?php echo e($designation->description); ?></textarea>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Status')); ?> <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            <option value="<?php echo e(STATUS_ACTIVE); ?>" <?php echo e($designation->status == STATUS_ACTIVE ? 'selected' : ''); ?>><?php echo e(__('Active')); ?></option>
                            <option value="<?php echo e(STATUS_DEACTIVATE); ?>" <?php echo e($designation->status == STATUS_DEACTIVATE ? 'selected' : ''); ?>><?php echo e(__('Deactivate')); ?></option>
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
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\hrm\designations\edit.blade.php ENDPATH**/ ?>