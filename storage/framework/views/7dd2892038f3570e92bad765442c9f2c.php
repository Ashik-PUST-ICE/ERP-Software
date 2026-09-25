<?php $__env->startPush('title'); ?>
<?php echo e($pageTitle); ?>

<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="section-wrap">
    <div class="row bd-c-ebedf0 bd-half bd-ra-25 bg-white h-100 p-30">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between align-items-center g-10 pb-12">
                <h4 class="fs-18 fw-600 lh-20 text-title-black"><?php echo e(__('Edit Ticket')); ?></h4>

            </div>

            <?php if($ticketDetails): ?>
            <form class="ajax reset" action="<?php echo e(route('admin.ticket.store')); ?>" method="POST"
                enctype="multipart/form-data" data-handler="commonResponseRedirect"
                data-redirect-url="<?php echo e(route('admin.ticket.list')); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <input type="hidden" value="<?php echo e($ticketDetails->id); ?>" name="id">

                <div class="primary-form">
                    <div class="row gy-4">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Select Package')); ?> <span
                                        class="required">*</span></label>
                                <select class="form-control select wide" name="order_id" id="selectPackage" required>
                                    <option value=""><?php echo e(__('Select Package')); ?></option>
                                    <?php $__empty_1 = true; $__currentLoopData = ($paymentOrderList ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <option value="<?php echo e($payment->id); ?>" data-user-id="<?php echo e($payment->user_id); ?>"
                                        <?php echo e($ticketDetails->order_id == $payment->id ? 'selected' : ''); ?>>
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
                                    value="<?php echo e($ticketDetails->ticket_title); ?>" placeholder="<?php echo e(__('Support Request')); ?>"
                                    required>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Description')); ?> <span
                                        class="required">*</span></label>
                                <textarea id="description" class="summernote" name="description" rows="3"
                                    required><?php echo e($ticketDetails->ticket_description); ?></textarea>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Priority')); ?></label>
                                <select class="form-control select wide" name="priority" id="priority">
                                    <option value="<?php echo e(TICKET_PRIORITY_LOW); ?>"
                                        <?php echo e($ticketDetails->priority == TICKET_PRIORITY_LOW ? 'selected' : ''); ?>>
                                        <?php echo e(__('Low')); ?></option>
                                    <option value="<?php echo e(TICKET_PRIORITY_MEDIUM); ?>"
                                        <?php echo e($ticketDetails->priority == TICKET_PRIORITY_MEDIUM ? 'selected' : ''); ?>>
                                        <?php echo e(__('Medium')); ?></option>
                                    <option value="<?php echo e(TICKET_PRIORITY_HIGH); ?>"
                                        <?php echo e($ticketDetails->priority == TICKET_PRIORITY_HIGH ? 'selected' : ''); ?>>
                                        <?php echo e(__('High')); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Status')); ?></label>
                                <select class="form-control select wide" name="status" id="status">
                                    <option value="<?php echo e(TICKET_STATUS_OPEN); ?>"
                                        <?php echo e($ticketDetails->status == TICKET_STATUS_OPEN ? 'selected' : ''); ?>>
                                        <?php echo e(__('Open')); ?></option>
                                    <option value="<?php echo e(TICKET_STATUS_IN_PROGRESS); ?>"
                                        <?php echo e($ticketDetails->status == TICKET_STATUS_IN_PROGRESS ? 'selected' : ''); ?>>
                                        <?php echo e(__('In Progress')); ?></option>
                                    <option value="<?php echo e(TICKET_STATUS_RESOLVED); ?>"
                                        <?php echo e($ticketDetails->status == TICKET_STATUS_RESOLVED ? 'selected' : ''); ?>>
                                        <?php echo e(__('Resolved')); ?></option>
                                    <option value="<?php echo e(TICKET_STATUS_CLOSED); ?>"
                                        <?php echo e($ticketDetails->status == TICKET_STATUS_CLOSED ? 'selected' : ''); ?>>
                                        <?php echo e(__('Closed')); ?></option>
                                </select>
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
                        <div class="col-lg-12">
                            <div class="form-group">
                                <button type="submit" class="primary-btn"><?php echo e(__('Update Ticket')); ?></button>
                                <a href="<?php echo e(route('admin.ticket.list')); ?>" class="primary-btn btn-outline "><?php echo e(__('Cancel')); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <?php else: ?>
            <div class="alert alert-danger"><?php echo e(__('Ticket not found')); ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('auto_posts.admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\admin\ticket\edit.blade.php ENDPATH**/ ?>