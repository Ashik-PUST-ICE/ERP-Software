<?php $__env->startPush('title'); ?>
<?php echo e($pageTitle); ?>

<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="p-30">
    <div class="row gy-4">
        <?php if($ticketDetails): ?>
        <div class="col-12">
            <div class="section-title">
                <h2 class="title"><?php echo e(__($pageTitle)); ?></h2>
                <a href="<?php echo e(route('admin.ticket.list')); ?>"
                    class="primary-btn"><?php echo e(__('Back')); ?></a>
            </div>
            <div class="row gy-4">
                <div class="col-lg-4">
                    <div class="section-wrap my-plan-area h-100">
                        <div class="plan-head"
                            style="padding-bottom: 12px; margin-bottom: 12px; border-bottom: 1px solid #ebedf0;">
                            <h3 style="font-size: 14px; font-weight: 600; margin-bottom: 4px;"><?php echo e(__('Ticket Info')); ?>

                            </h3>
                            <h2 style="font-size: 18px; font-weight: 600; margin-bottom: 4px;">
                                <?php echo e($ticketDetails->ticket_title ?? __('Support Request')); ?></h2>
                            <h3 style="font-size: 12px; font-weight: 500; color: #6b7280;">
                                <?php echo e($ticketDetails->ticket_id ?? '-'); ?></h3>
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
                                    <?php echo e(__('Package')); ?>:
                                    <strong>
                                        <?php
                                        $packageName = '-';
                                        if(isset($ticketDetails->userPackage) &&
                                        isset($ticketDetails->userPackage->packageable)) {
                                        $packageName = $ticketDetails->userPackage->packageable->name;
                                        if(isset($ticketDetails->userPackage->billing_cycle)) {
                                        $packageName .= ' (' . $ticketDetails->userPackage->billing_cycle . ')';
                                        }
                                        } elseif(isset($ticketDetails->payment) &&
                                        isset($ticketDetails->payment->paymentable)) {
                                        $packageName = $ticketDetails->payment->paymentable->name;
                                        } elseif(isset($ticketDetails->order_id)) {
                                        $packageName = $ticketDetails->order_id;
                                        }
                                        ?>
                                        <?php echo e($packageName); ?>

                                    </strong>
                                </li>
                                <?php if(isset($ticketDetails->userPackage) && $ticketDetails->userPackage): ?>
                                <li
                                    style="font-size: 13px; display: flex; align-items: center; gap: 8px; color: #4b5563;">
                                    <svg width="12" height="12" viewBox="0 0 14 14" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M2.91675 8.45898C2.91675 8.45898 3.79175 8.45898 4.95841 10.5007C4.95841 10.5007 8.20105 5.15343 11.0834 4.08398"
                                            stroke="#4b5563" stroke-width="0.875" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                    <?php echo e(__('Type')); ?>:
                                    <strong>
                                        <?php if(isset($ticketDetails->userPackage->subscription_type)): ?>
                                        <?php if($ticketDetails->userPackage->subscription_type == 1): ?>
                                        <?php echo e(__('Monthly')); ?>

                                        <?php elseif($ticketDetails->userPackage->subscription_type == 2): ?>
                                        <?php echo e(__('Yearly')); ?>

                                        <?php else: ?>
                                        <?php echo e($ticketDetails->userPackage->subscription_type); ?>

                                        <?php endif; ?>
                                        <?php else: ?>
                                        -
                                        <?php endif; ?>
                                    </strong>
                                    </strong>
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
                                    <?php echo e(__('Start Date')); ?>:
                                    <strong><?php echo e($ticketDetails->userPackage->start_date ?? '-'); ?></strong>
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
                                    <?php echo e(__('End Date')); ?>:
                                    <strong><?php echo e($ticketDetails->userPackage->end_date ?? '-'); ?></strong>
                                </li>
                                <?php endif; ?>
                                <li
                                    style="font-size: 13px; display: flex; align-items: center; gap: 8px; color: #4b5563;">
                                    <svg width="12" height="12" viewBox="0 0 14 14" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M2.91675 8.45898C2.91675 8.45898 3.79175 8.45898 4.95841 10.5007C4.95841 10.5007 8.20105 5.15343 11.0834 4.08398"
                                            stroke="#4b5563" stroke-width="0.875" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                    <?php echo e(__('Name')); ?>: <strong><?php echo e($ticketDetails->client_name ?? '-'); ?></strong>
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
                                    <?php echo e(__('Email')); ?>: <strong><?php echo e($ticketDetails->client_email ?? '-'); ?></strong>
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
                            <div class="btn-list" style="display: flex; gap: 10px;">
                                <a href="<?php echo e(route('admin.ticket.edit', encrypt($ticketDetails->id))); ?>"
                                    class="primary-btn" style="padding: 8px 20px; font-size: 13px;"><?php echo e(__('Edit')); ?></a>
                                <button type="button" class="primary-btn btn-outline ticket-delete-btn"
                                    data-route="<?php echo e(route('admin.ticket.delete', encrypt($ticketDetails->id))); ?>"
                                    data-redirect="<?php echo e(route('admin.ticket.list')); ?>"
                                    style="padding: 8px 20px;"><?php echo e(__('Delete')); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="section-wrap">
                        <div class="d-flex align-items-center g-10 pb-20 bd-b-one bd-c-ebedf0 mb-20 ticket-client-image-wrapper">
                            <div class="flex-shrink-0 w-40 h-40 rounded-circle overflow-hidden ticket-client-image">
                                <?php if(!empty($ticketDetails->client_image)): ?>
                                <img src="<?php echo e(getFileUrl($ticketDetails->client_image)); ?>" alt="" class="w-100 h-100"
                                    style="object-fit:cover; " />
                                <?php else: ?>
                                <img src="<?php echo e(asset('assets/images/avatar-image.png')); ?>" alt="" class="w-100 h-100"
                                    style="object-fit:cover;" />
                                <?php endif; ?>
                            </div>
                            <div>
                                <h4 class="fs-15 fw-600 lh-20 text-title-black mb-0">
                                    <?php echo e($ticketDetails->client_name ?? 'Client'); ?></h4>
                                <p class="fs-12 fw-400 lh-15 text-para-text mb-0">(<?php echo e(__('Client')); ?>)</p>
                            </div>
                        </div>
                        <div class="section-small-title pb-12 mb-15">
                            <h3 class="title fs-14 fw-600 lh-20 text-title-black"><?php echo e(__('Description')); ?></h3>
                        </div>
                        <div class="fs-14 fw-400 lh-24 text-para-text text-justify pb-20">
                            <?php echo $ticketDetails->ticket_description; ?>

                        </div>
                        <?php if($ticketDetails->file_id != null && count(json_decode($ticketDetails->file_id) ?? []) > 0): ?>
                        <ul class="d-flex flex-wrap g-10 list-unstyled mb-0">
                            <?php $__currentLoopData = json_decode($ticketDetails->file_id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(in_array(getFileData($file, 'extension') ?? '',
                            ['jpg','png','jpeg','webp','JPG','PNG','JPEG','WEBP'])): ?>
                            <li>
                                <div class="sf-popup-gallery">
                                    <a href="<?php echo e(getFileUrl($file)); ?>">
                                        <img src="<?php echo e(getFileUrl($file)); ?>" alt="" class="bd-one bd-c-ebedf0 bd-ra-8"
                                            style="width:120px;height:120px;object-fit:cover;" />
                                    </a>
                                </div>
                            </li>
                            <?php else: ?>
                            <li>
                                <a href="<?php echo e(getFileUrl($file)); ?>" target="_blank"
                                    class="p-10 bd-one bd-c-ebedf0 bd-ra-10 bg-body-bg d-inline-flex flex-column g-10 text-decoration-none">
                                    <div><img src="<?php echo e(asset('assets/images/icon/files-1.svg')); ?>" alt="" /></div>
                                    <p class="fs-14 fw-400 lh-17 text-title-black mb-0">
                                        <?php echo e(getFileData($file, 'file_name')); ?></p>
                                    <div class="d-flex align-items-center g-8">
                                        <span class="fs-12 fw-400 lh-15 text-para-text"><?php echo e(getFileData($file, 'size')); ?>

                                            B</span>
                                        <span class="fs-12 fw-400 lh-15 text-para-text"><?php echo e(__('File')); ?></span>
                                    </div>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <?php endif; ?>
                    </div>

                    <?php echo $__env->make('auto_posts.admin.ticket.conversation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="col-12">
            <div class="alert alert-danger"><?php echo e(__('Ticket not found')); ?></div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
<script src="<?php echo e(asset('admin/js/ticket.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('auto_posts.admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\admin\ticket\details.blade.php ENDPATH**/ ?>