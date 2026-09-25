<?php $__env->startPush('title'); ?> <?php echo e($title); ?> <?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="section-title d-flex justify-content-between align-items-center flex-wrap gap-2">
    <h2 class="title"><?php echo e(__($title)); ?></h2>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <a class="primary-btn hrm-report-link" href="<?php echo e(route('admin.hrm.attendance.print')); ?>" data-base-url="<?php echo e(route('admin.hrm.attendance.print')); ?>" data-filters="filterDate:date,filterStatus:status,filterDepartment:department_id" target="_blank"><i class="fa fa-print me-1"></i><?php echo e(__('Print')); ?></a>
        <a class="primary-btn hrm-report-link" href="<?php echo e(route('admin.hrm.attendance.export')); ?>" data-base-url="<?php echo e(route('admin.hrm.attendance.export')); ?>" data-filters="filterDate:date,filterStatus:status,filterDepartment:department_id"><i class="fa fa-download me-1"></i><?php echo e(__('Export')); ?></a>
    </div>
</div>

<div class="settings-page-area">
    <div class="settings-page-right">
        <div class="section-wrap">
            <div class="table-waraper">
                
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
                            placeholder="<?php echo e(__('Search Attendance...')); ?>" />
                    </div>

                    <div class="d-flex align-items-center gap-2 flex-wrap ms-auto">
                        <div class="select-wrap" style="min-width: 170px;">
                            <input type="date" class="form-control form-control sf-select wide" id="filterDate" value="<?php echo e($date); ?>" style="height: 42px; border-radius: 10px; font-size: 13px;">
                        </div>
                        <div class="select-wrap" style="min-width: 170px;">
                            <select class="form-select form-control sf-select wide" id="filterStatus" style="height: 42px; border-radius: 10px; font-size: 13px;">
                                <option value=""><?php echo e(__('All Status')); ?></option>
                                <option value="<?php echo e(ATTENDANCE_STATUS_PRESENT); ?>"><?php echo e(__('Present')); ?></option>
                                <option value="<?php echo e(ATTENDANCE_STATUS_LATE); ?>"><?php echo e(__('Late')); ?></option>
                                <option value="<?php echo e(ATTENDANCE_STATUS_ABSENT); ?>"><?php echo e(__('Absent')); ?></option>
                                <option value="<?php echo e(ATTENDANCE_STATUS_ON_LEAVE); ?>"><?php echo e(__('On Leave')); ?></option>
                            </select>
                        </div>
                        <div class="select-wrap" style="min-width: 200px;">
                            <select class="form-select form-control sf-select wide" id="filterDepartment" style="height: 42px; border-radius: 10px; font-size: 13px;">
                                <option value=""><?php echo e(__('All Departments')); ?></option>
                                <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($dept->id); ?>"><?php echo e($dept->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="attendance-data-route" value="<?php echo e(route('admin.hrm.attendance.index')); ?>">
                <table class="display primary-table dataTable dtr-inline" id="attendanceDataTable">
                    <thead>
                        <tr>
                            <th class="keep-show"><?php echo e(__("SL")); ?></th>
                            <th><?php echo e(__("Employee Code")); ?></th>
                            <th><?php echo e(__("Name")); ?></th>
                            <th><?php echo e(__("Department")); ?></th>
                            <th><?php echo e(__("Check In")); ?></th>
                            <th><?php echo e(__("Check Out")); ?></th>
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


<div class="modal fade zModalTwo" id="mark-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            <form method="POST" action="<?php echo e(route('admin.hrm.attendance.mark')); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="employee_id" id="att_employee_id">
                <input type="hidden" name="date" id="att_date">
                <div class="modal-body zModalTwo-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fs-20 fw-500 mb-0"><?php echo e(__('Mark Attendance')); ?></h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="primary-form">
                        <div class="row gy-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label"><?php echo e(__('Check In')); ?></label>
                                    <input type="time" class="form-control" name="check_in">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label"><?php echo e(__('Check Out')); ?></label>
                                    <input type="time" class="form-control" name="check_out">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label"><?php echo e(__('Status')); ?> <span class="required">*</span></label>
                                    <select class="form-control" name="status" required>
                                        <option value="<?php echo e(ATTENDANCE_STATUS_PRESENT); ?>"><?php echo e(__('Present')); ?></option>
                                        <option value="<?php echo e(ATTENDANCE_STATUS_LATE); ?>"><?php echo e(__('Late')); ?></option>
                                        <option value="<?php echo e(ATTENDANCE_STATUS_ABSENT); ?>"><?php echo e(__('Absent')); ?></option>
                                        <option value="<?php echo e(ATTENDANCE_STATUS_HALF_DAY); ?>"><?php echo e(__('Half Day')); ?></option>
                                        <option value="<?php echo e(ATTENDANCE_STATUS_ON_LEAVE); ?>"><?php echo e(__('On Leave')); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label"><?php echo e(__('Notes')); ?></label>
                                    <textarea class="form-control" name="notes" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn-list mt-4 pt-3 border-top">
                        <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
                        <button type="submit" class="primary-btn"><?php echo e(__('Save')); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
<script src="<?php echo e(asset('admin/js/hrm-report-actions.js')); ?>"></script>
<script src="<?php echo e(asset('admin/js/hrm-attendance.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('auto_posts.admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\hrm\attendance\index.blade.php ENDPATH**/ ?>