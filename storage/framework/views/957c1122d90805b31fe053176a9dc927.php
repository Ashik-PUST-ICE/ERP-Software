<?php $__env->startPush('title'); ?>
<?php echo e($pageTitle); ?>

<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
<div class="p-30">
    <div class="row gy-4">
        <div class="col-12">
            <div class="section-title">
                <h2 class="title"><?php echo e(__($pageTitle)); ?></h2>
                <a href="<?php echo e(route('super_admin.ticket.details', encrypt($ticketDetails->id))); ?>"
                    class="primary-btn"><?php echo e(__('Back')); ?></a>
            </div>
            <form action="<?php echo e(route('super_admin.ticket.store')); ?>" method="POST" class="ajax reset"
                data-handler="commonResponseRedirect" data-redirect-url="<?php echo e(route('super_admin.ticket.list')); ?>"
                enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <input type="hidden" name="id" value="<?php echo e($ticketDetails->id); ?>">
                <input type="hidden" name="client_id" id="clientId" value="<?php echo e($ticketDetails->client_id); ?>">

                <div class="row">
                    <div class="col-lg-8">
                        <div class="section-wrap">
                            <div class="primary-form">
                                <div class="row gy-4">
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label"><?php echo e(__('Select Package')); ?> <span
                                                    class="required">*</span></label>
                                            <select class="select form-control wide sf-select-without-search" name="order_id" id="selectPackage" required
                                                onchange="updateClientId()">
                                                <option value=""><?php echo e(__('Select Package')); ?></option>
                                                <?php $__empty_1 = true; $__currentLoopData = ($paymentOrderList ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <option value="<?php echo e($payment->id); ?>"
                                                    data-user-id="<?php echo e($payment->user_id); ?>"
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
                                            <label class="form-label"><?php echo e(__('Title')); ?> <span
                                                    class="required">*</span></label>
                                            <input type="text" class="form-control" name="ticket_title"
                                                id="ticket_title" value="<?php echo e($ticketDetails->ticket_title); ?>"
                                                placeholder="<?php echo e(__('Support Request')); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label"><?php echo e(__('Priority')); ?></label>
                                            <select class="select form-control wide sf-select-without-search" name="priority" id="priority">
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
                                            <select class="select form-control wide sf-select-without-search" name="status" id="status">
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
                                            <label class="form-label"><?php echo e(__('Assign to Team Member')); ?></label>
                                            <select class="select form-control wide sf-select-without-search" name="assign_member">
                                                <option value=""><?php echo e(__('Select Team Member')); ?></option>
                                                <?php $__currentLoopData = $teamMemberList ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($member->id); ?>"
                                                    <?php echo e(in_array($member->id, $ticketAssignee ?? []) ? 'selected' : ''); ?>>
                                                    <?php echo e($member->name); ?>

                                                    (<?php echo e($member->email); ?>)</option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="form-label"><?php echo e(__('Description')); ?> <span
                                                    class="required">*</span></label>
                                            <textarea id="editDescription" class="summernote" name="description"
                                                rows="5" placeholder="<?php echo e(__('Write description here...')); ?>"
                                                required><?php echo e($ticketDetails->ticket_description); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="form-label"><?php echo e(__('Change Image (JPG, JPEG, PNG)')); ?></label>
                                            <div class="file-upload">
                                                <input type="file" class="file-input" id="mAttachment" name="file[]"
                                                    multiple accept="image/*">
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
                    </div>
                    <div class="col-lg-4">
                        <div class="section-wrap my-plan-area h-100">
                            <div class="plan-head"
                                style="padding-bottom: 12px; margin-bottom: 12px; border-bottom: 1px solid #ebedf0;">
                                <h3 style="font-size: 14px; font-weight: 600; margin-bottom: 4px;">
                                    <?php echo e(__('Ticket Actions')); ?></h3>
                            </div>
                            <div class="plan-body" style="padding-top: 10px;">
                                <ul class="plan-features" style="gap: 8px; margin: 0; padding: 0; list-style: none;">
                                    <li
                                        style="font-size: 13px; display: flex; align-items: center; gap: 8px; color: #4b5563;">
                                        <svg width="12" height="12" viewBox="0 0 14 14" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M2.91675 8.45898C2.91675 8.45898 3.79175 8.45898 4.95841 10.5007C4.95841 10.5007 8.20105 5.15343 11.0834 4.08398"
                                                stroke="#4b5563" stroke-width="0.875" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                        <?php echo e(__('Ticket ID')); ?>: <strong><?php echo e($ticketDetails->ticket_id ?? '-'); ?></strong>
                                    </li>
                                    <li
                                        style="font-size: 13px; display: flex; align-items: center; gap: 8px; color: #4b5563;">
                                        <svg width="12" height="12" viewBox="0 0 14 14" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M2.91675 8.45898C2.91675 8.45898 3.79175 8.45898 4.95841 10.5007C4.95841 10.5007 8.20105 5.15343 11.0834 4.08398"
                                                stroke="#4b5563" stroke-width="0.875" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                        <?php echo e(__('Priority')); ?>:
                                        <?php if($ticketDetails->priority == TICKET_PRIORITY_LOW): ?>
                                        <span style="color: #6b7280;"><?php echo e(__('Low')); ?></span>
                                        <?php elseif($ticketDetails->priority == TICKET_PRIORITY_HIGH): ?>
                                        <span style="color: #dc2626; font-weight: 600;"><?php echo e(__('High')); ?></span>
                                        <?php else: ?>
                                        <span style="color: #f59e0b; font-weight: 600;"><?php echo e(__('Medium')); ?></span>
                                        <?php endif; ?>
                                    </li>
                                    <li
                                        style="font-size: 13px; display: flex; align-items: center; gap: 8px; color: #4b5563;">
                                        <svg width="12" height="12" viewBox="0 0 14 14" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M2.91675 8.45898C2.91675 8.45898 3.79175 8.45898 4.95841 10.5007C4.95841 10.5007 8.20105 5.15343 11.0834 4.08398"
                                                stroke="#4b5563" stroke-width="0.875" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                        <?php echo e(__('Status')); ?>:
                                        <?php if($ticketDetails->status == TICKET_STATUS_OPEN): ?>
                                        <span class="zBadge zBadge-open"><?php echo e(__('Open')); ?></span>
                                        <?php elseif($ticketDetails->status == TICKET_STATUS_IN_PROGRESS): ?>
                                        <span class="zBadge zBadge-onHold"><?php echo e(__('In Progress')); ?></span>
                                        <?php elseif($ticketDetails->status == TICKET_STATUS_RESOLVED): ?>
                                        <span class="zBadge zBadge-complete"><?php echo e(__('Resolved')); ?></span>
                                        <?php elseif($ticketDetails->status == TICKET_STATUS_CLOSED): ?>
                                        <span class="zBadge zBadge-closed"><?php echo e(__('Closed')); ?></span>
                                        <?php else: ?>
                                        <span class="zBadge zBadge-open"><?php echo e(__('Open')); ?></span>
                                        <?php endif; ?>
                                    </li>
                                </ul>
                            </div>
                            <div class="plan-footer"
                                style="padding-top: 15px; margin-top: 15px; border-top: 1px solid #ebedf0;">
                                <button type="submit" class="primary-btn w-100"><?php echo e(__('Update Ticket')); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('auto_posts.super_admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\super_admin\ticket\edit.blade.php ENDPATH**/ ?>