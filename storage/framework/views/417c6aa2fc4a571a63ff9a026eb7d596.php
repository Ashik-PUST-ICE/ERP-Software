<?php $__env->startPush('title'); ?> <?php echo e($title); ?> <?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="section-title d-flex justify-content-between align-items-center flex-wrap gap-2">
    <h2 class="title"><?php echo e(__($title)); ?>

        <?php if($pendingCount > 0): ?>
            <span class="zBadge zBadge-warning ms-2"><?php echo e($pendingCount); ?> <?php echo e(__('Pending')); ?></span>
        <?php endif; ?>
    </h2>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <a class="primary-btn hrm-report-link" href="<?php echo e(route('admin.hrm.leaves.print')); ?>" data-base-url="<?php echo e(route('admin.hrm.leaves.print')); ?>" data-filters="filterStatus:status,filterEmployee:employee_id" target="_blank"><i class="fa fa-print me-1"></i><?php echo e(__('Print')); ?></a>
        <a class="primary-btn hrm-report-link" href="<?php echo e(route('admin.hrm.leaves.export')); ?>" data-base-url="<?php echo e(route('admin.hrm.leaves.export')); ?>" data-filters="filterStatus:status,filterEmployee:employee_id"><i class="fa fa-download me-1"></i><?php echo e(__('Export')); ?></a>
        <button type="button" class="primary-btn" data-bs-toggle="modal" data-bs-target="#add-modal"><i class="fa fa-plus me-2"></i><?php echo e(__('New Request')); ?></button>
    </div>
</div>

<div class="settings-page-area">
    <div class="settings-page-right">
        <div class="section-wrap">
            
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                <div class="search-input-wrap mb-0 flex-grow-1" style="max-width: 380px;">
                    <label class="icon" for="searchData">
                        <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10.625 10.625L11.6875 11.6875" stroke="#6E5858" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M11.9944 13.4762C11.5852 13.067 11.5852 12.4035 11.9944 11.9944C12.4035 11.5852 13.067 11.5852 13.4762 11.9944L14.9222 13.4405C15.3314 13.8497 15.3314 14.5131 14.9222 14.9222C14.5131 15.3314 13.8497 15.3314 13.4405 14.9222L11.9944 13.4762Z"
                                stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M11.6872 6.72982C11.6872 3.99141 9.46726 1.77148 6.72884 1.77148C3.99043 1.77148 1.77051 3.99141 1.77051 6.72982C1.77051 9.46824 3.99043 11.6882 6.72884 11.6882C9.46726 11.6882 11.6872 9.46824 11.6872 6.72982Z"
                                stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </label>
                    <input type="text" class="search-input" id="searchData"
                        placeholder="<?php echo e(__('Search Leave Requests...')); ?>" />
                </div>

                <div class="d-flex align-items-center gap-2 flex-wrap ms-auto">
                    <div class="select-wrap" style="min-width: 170px;">
                        <select class="form-select form-control sf-select wide" id="filterStatus" style="height: 42px; border-radius: 10px; font-size: 13px;">
                            <option value=""><?php echo e(__('All Status')); ?></option>
                            <option value="<?php echo e(LEAVE_STATUS_PENDING); ?>"><?php echo e(__('Pending')); ?></option>
                            <option value="<?php echo e(LEAVE_STATUS_APPROVED); ?>"><?php echo e(__('Approved')); ?></option>
                            <option value="<?php echo e(LEAVE_STATUS_REJECTED); ?>"><?php echo e(__('Rejected')); ?></option>
                        </select>
                    </div>
                    <div class="select-wrap" style="min-width: 200px;">
                        <select class="form-select form-control sf-select wide" id="filterEmployee" style="height: 42px; border-radius: 10px; font-size: 13px;">
                            <option value=""><?php echo e(__('All Employees')); ?></option>
                            <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($emp->id); ?>"><?php echo e($emp->first_name); ?> <?php echo e($emp->last_name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="table-waraper">
                <input type="hidden" id="leave-data-route" value="<?php echo e(route('admin.hrm.leaves.index')); ?>">
                <table class="display primary-table dataTable dtr-inline" id="leaveDataTable">
                    <thead>
                        <tr>
                            <th class="keep-show"><?php echo e(__("SL")); ?></th>
                            <th><?php echo e(__("Employee")); ?></th>
                            <th><?php echo e(__("Type")); ?></th>
                            <th><?php echo e(__("Duration")); ?></th>
                            <th><?php echo e(__("Status")); ?></th>
                            <th class="keep-show"><?php echo e(__("Action")); ?></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="modal fade zModalTwo" id="add-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            <form method="POST" action="<?php echo e(route('admin.hrm.leaves.store')); ?>" class="ajax reset" data-handler="commonResponseWithPageLoad">
                <?php echo csrf_field(); ?>
                <div class="modal-body zModalTwo-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fs-20 fw-500 mb-0"><?php echo e(__('New Leave Request')); ?></h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="primary-form">
                        <div class="row gy-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label"><?php echo e(__('Employee')); ?> <span class="required">*</span></label>
                                    <select class="form-control" name="employee_id" required>
                                        <option value=""><?php echo e(__('Select Employee')); ?></option>
                                        <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($emp->id); ?>"><?php echo e($emp->first_name); ?> <?php echo e($emp->last_name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label"><?php echo e(__('Leave Type')); ?> <span class="required">*</span></label>
                                    <select class="form-control" name="leave_type" required>
                                        <option value="<?php echo e(LEAVE_TYPE_CASUAL); ?>"><?php echo e(__('Casual')); ?></option>
                                        <option value="<?php echo e(LEAVE_TYPE_SICK); ?>"><?php echo e(__('Sick')); ?></option>
                                        <option value="<?php echo e(LEAVE_TYPE_ANNUAL); ?>"><?php echo e(__('Annual')); ?></option>
                                        <option value="<?php echo e(LEAVE_TYPE_MATERNITY); ?>"><?php echo e(__('Maternity')); ?></option>
                                        <option value="<?php echo e(LEAVE_TYPE_OTHER); ?>"><?php echo e(__('Other')); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label"><?php echo e(__('Start Date')); ?> <span class="required">*</span></label>
                                    <input type="date" class="form-control" name="start_date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label"><?php echo e(__('End Date')); ?> <span class="required">*</span></label>
                                    <input type="date" class="form-control" name="end_date" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label"><?php echo e(__('Reason')); ?></label>
                                    <textarea class="form-control" name="reason" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn-list mt-4 pt-3 border-top">
                        <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
                        <button type="submit" class="primary-btn"><?php echo e(__('Submit')); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade zModalTwo" id="reject-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            <form method="POST" id="rejectForm" class="ajax reset" data-handler="commonResponseWithPageLoad">
                <?php echo csrf_field(); ?>
                <div class="modal-body zModalTwo-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fs-20 fw-500 mb-0"><?php echo e(__('Reject Leave')); ?></h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Admin Note')); ?></label>
                        <textarea class="form-control" name="admin_note" rows="3"></textarea>
                    </div>
                    <div class="btn-list mt-4 pt-3 border-top">
                        <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
                        <button type="submit" class="primary-btn"><?php echo e(__('Reject')); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
<script src="<?php echo e(asset('admin/js/hrm-report-actions.js')); ?>"></script>
<script src="<?php echo e(asset('admin/js/hrm-leaves.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('auto_posts.admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\hrm\leaves\index.blade.php ENDPATH**/ ?>