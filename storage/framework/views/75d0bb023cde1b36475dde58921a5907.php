<?php if(count($ticketConversations) > 0): ?>
<div class="section-wrap mt-20">
    <div class="section-small-title pb-15 mb-20 bd-b-one bd-c-ebedf0">
        <h3 class="title fs-15 fw-600 lh-20 text-title-black"><?php echo e(__('Ticket Replies')); ?></h3>
    </div>
    <div class="d-flex flex-column gap-3">
        <?php $__currentLoopData = $ticketConversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white border rounded p-3">
            <div class="d-flex align-items-center justify-content-between gap-2 mb-2 ticket-client-image-wrapper ticket-client-image ticket-client-image-wrapper-mobile">
                <div class="d-flex align-items-center gap-2 ticket-conversation-box-wrap">
                    <div class="flex-shrink-0 w-32 h-32 rounded-circle overflow-hidden ">
                        <img src="<?php echo e(getFileUrl($item->client_image ?? '')); ?>" alt="" class="w-100 h-100"
                            style="object-fit:cover;" onerror="this.src='<?php echo e(asset('assets/images/avatar-image.png')); ?>'" />
                    </div>
                    <div>
                        <?php if($item->user_id == auth()->id()): ?>
                        <h4 class="fs-13 fw-600 lh-18 text-title-black mb-0"><?php echo e(__('You')); ?></h4>
                        <?php elseif(in_array($item->client_role ?? null, [USER_ROLE_ADMIN, USER_ROLE_STAFF, USER_ROLE_SUPER_ADMIN])): ?>
                        <h4 class="fs-13 fw-600 lh-18 text-title-black mb-0"><?php echo e($item->client_name); ?>

                            <span class="fs-11 fw-400 text-para-text">(<?php echo e(__('Team')); ?>)</span>
                        </h4>
                        <?php else: ?>
                        <h4 class="fs-13 fw-600 lh-18 text-title-black mb-0"><?php echo e($item->client_name); ?>

                            <span class="fs-11 fw-400 text-para-text">(<?php echo e(__('Client')); ?>)</span>
                        </h4>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if($item->user_id == auth()->id() || in_array(auth()->user()->role ?? null, [USER_ROLE_SUPER_ADMIN, USER_ROLE_STAFF])): ?>
                <div class="dropdown options-area">
                    <button class="options-btn" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="fa-solid fa-ellipsis"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <button class="dropdown-item ticket-coversation-dlt-btn" type="button"
                                onclick="deleteItem('<?php echo e(route('super_admin.ticket.conversations.delete', encrypt($item->id))); ?>')">
                                <?php echo e(__('Delete')); ?>

                            </button>
                        </li>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
            <div class="mb-2">
                <p class="fs-13 fw-400 lh-20 text-para-text mb-0"><?php echo $item->conversation_text; ?></p>
            </div>
            <?php if($item->attachment != null && count(json_decode($item->attachment) ?? []) > 0): ?>
            <div class="d-flex flex-wrap gap-2">
                <?php $__currentLoopData = json_decode($item->attachment); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(in_array(getFileData($file, 'extension') ?? '', ['jpg','png','jpeg','webp','JPG','PNG','JPEG','WEBP'])): ?>
                <div class="sf-popup-gallery">
                    <a href="<?php echo e(getFileUrl($file)); ?>">
                        <img src="<?php echo e(getFileUrl($file)); ?>" alt="" class="border rounded"
                            style="width:80px;height:80px;object-fit:cover;" />
                    </a>
                </div>
                <?php else: ?>
                <a href="<?php echo e(getFileUrl($file)); ?>" target="_blank"
                    class="p-2 border rounded d-inline-flex flex-column text-decoration-none">
                    <div><img src="<?php echo e(asset('assets/images/icon/files-1.svg')); ?>" alt="" /></div>
                    <p class="fs-12 fw-400 lh-14 text-title-black mb-0"><?php echo e(getFileData($file, 'file_name')); ?></p>
                </a>
                <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php endif; ?>

<div class="section-wrap mt-20">
    <div class="section-small-title pb-15 mb-20 bd-b-one bd-c-ebedf0">
        <h3 class="title fs-15 fw-600 lh-20 text-title-black"><?php echo e(__('Write a Reply')); ?></h3>
    </div>
    <form class="ajax reset" action="<?php echo e(route('super_admin.ticket.conversations.store')); ?>" method="POST"
        enctype="multipart/form-data" data-handler="commonResponseWithPageLoad">
        <?php echo csrf_field(); ?>
        <div class="primary-form">
            <div class="row gy-4">
                <div class="col-lg-12">
                    <div class="form-group">
                        <div class="d-flex justify-content-end align-items-center flex-wrap g-10 pb-8">
                            <div class="d-flex flex-wrap align-items-center write-replay-radio-box">
                                <div class="zForm-wrap-checkbox-2">
                                    <input type="radio" name="status"
                                        class="form-check-input ticket-status-change status<?php echo e(TICKET_STATUS_OPEN); ?>"
                                        <?php echo e($ticketDetails->status == TICKET_STATUS_OPEN ? 'checked' : ''); ?>

                                        data-ticket="<?php echo e(encrypt($ticketDetails->id)); ?>" id="pending"
                                        value="<?php echo e(TICKET_STATUS_OPEN); ?>" data-status="<?php echo e($ticketDetails->status); ?>" />
                                    <label for="pending"><?php echo e(__('Open')); ?></label>
                                </div>
                                <div class="zForm-wrap-checkbox-2">
                                    <input type="radio" name="status"
                                        class="form-check-input ticket-status-change status<?php echo e(TICKET_STATUS_IN_PROGRESS); ?>"
                                        <?php echo e($ticketDetails->status == TICKET_STATUS_IN_PROGRESS ? 'checked' : ''); ?>

                                        data-ticket="<?php echo e(encrypt($ticketDetails->id)); ?>" id="processing"
                                        value="<?php echo e(TICKET_STATUS_IN_PROGRESS); ?>"
                                        data-status="<?php echo e($ticketDetails->status); ?>" />
                                    <label for="processing"><?php echo e(__('Processing')); ?></label>
                                </div>
                                <div class="zForm-wrap-checkbox-2">
                                    <input type="radio" name="status"
                                        class="form-check-input ticket-status-change status<?php echo e(TICKET_STATUS_RESOLVED); ?>"
                                        <?php echo e($ticketDetails->status == TICKET_STATUS_RESOLVED ? 'checked' : ''); ?>

                                        data-ticket="<?php echo e(encrypt($ticketDetails->id)); ?>" id="solved"
                                        value="<?php echo e(TICKET_STATUS_RESOLVED); ?>"
                                        data-status="<?php echo e($ticketDetails->status); ?>" />
                                    <label for="solved"><?php echo e(__('Solved')); ?></label>
                                </div>
                                <div class="zForm-wrap-checkbox-2">
                                    <input type="radio" name="status"
                                        class="form-check-input ticket-status-change status<?php echo e(TICKET_STATUS_CLOSED); ?>"
                                        <?php echo e($ticketDetails->status == TICKET_STATUS_CLOSED ? 'checked' : ''); ?>

                                        data-ticket="<?php echo e(encrypt($ticketDetails->id)); ?>" id="closed"
                                        value="<?php echo e(TICKET_STATUS_CLOSED); ?>"
                                        data-status="<?php echo e($ticketDetails->status); ?>" />
                                    <label for="closed"><?php echo e(__('Closed')); ?></label>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" value="<?php echo e(encrypt($ticketDetails->id)); ?>" name="ticket_id">
                        <textarea id="ticketReply" class="form-control" rows="5" style="min-height: 170px"
                            placeholder="<?php echo e(__('Write Reply here')); ?>...." name="conversation_text"></textarea>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Upload Image (JPG, JPEG, PNG)')); ?></label>
                        <div class="file-upload">
                            <input type="file" name="file[]" id="mAttachment" class="file-input" multiple
                                accept="image/*" />
                            <label for="mAttachment" class="file-input-label">
                                <span class="file-text"><?php echo e(__('Choose image to upload')); ?></span>
                                <span class="file-btn"><?php echo e(__('Browse File')); ?></span>
                            </label>
                        </div>
                    </div>
                </div>
                <?php if($ticketDetails->status == TICKET_STATUS_CLOSED): ?>
                <div class="col-lg-12">
                    <p class="fs-14 fw-400 lh-20 text-para-text mb-0">
                        <?php echo e(__('Note: Not possible to conversation for this ticket. Because, This ticket is closed')); ?>.
                    </p>
                </div>
                <?php else: ?>
                <div class="col-lg-12">
                    <button type="submit" class="primary-btn"><?php echo e(__('Send Message')); ?></button>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>
<input type="hidden" id="statusChangeRoute" value="<?php echo e(route('super_admin.ticket.status.change')); ?>">
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\super_admin\ticket\conversation.blade.php ENDPATH**/ ?>