<div class="modal-header">
    <h2 class="modal-title"><?php echo e(__('Add Ticket')); ?></h2>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="<?php echo e(route('admin.ticket.store')); ?>" method="POST" class="ajax reset" data-handler="settingCommonHandler"
    enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <div class="modal-body">
        <div class="primary-form">
            <div class="row gy-4">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Select Package')); ?> <span class="required">*</span></label>
                        <select class="form-control select wide" name="order_id" id="selectPackage" required>
                            <option value=""><?php echo e(__('Select Package')); ?></option>
                            <?php $__empty_1 = true; $__currentLoopData = ($paymentOrderList ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <option value="<?php echo e($payment->id); ?>" data-user-id="<?php echo e($payment->user_id); ?>">
                                <?php echo e($payment->packageable->name ?? 'Package'); ?>

                                (<?php echo e($payment->user->email ?? 'N/A'); ?>)
                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <option value=""><?php echo e(__('No packages found')); ?></option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Title')); ?> <span class="required">*</span></label>
                        <input type="text" class="form-control" name="ticket_title" id="ticket_title"
                            placeholder="<?php echo e(__('Support Request')); ?>" required>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Description')); ?> <span class="required">*</span></label>
                        <textarea id="description" class="summernote" name="description"
                            rows="3"><?php echo e(old('description')); ?></textarea>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Change Image (JPG, JPEG, PNG)')); ?></label>
                        <div class="file-upload">
                            <input type="file" class="file-input" id="mAttachment" name="file[]" multiple
                                accept="image/*">
                            <label for="mAttachment" class="file-input-label">
                                <span class="file-text"><?php echo e(__('Choose image to upload')); ?></span>
                                <span class="file-btn"><?php echo e(__('Browse File')); ?></span>
                            </label>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <div class="modal-footer">

        <button type="submit" class="primary-btn"><?php echo e(__('Save Ticket')); ?></button>
    </div>

</form><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\admin\ticket\add-new.blade.php ENDPATH**/ ?>