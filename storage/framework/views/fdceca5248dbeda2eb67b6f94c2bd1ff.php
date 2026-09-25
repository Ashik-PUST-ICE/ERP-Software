<?php $__env->startPush('title'); ?> <?php echo e($title); ?> <?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="section-title">
    <h2 class="title"><?php echo e(__($title)); ?></h2>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('admin.hrm.employees.edit', $employee->id)); ?>" class="primary-btn">
            <i class="fa fa-edit me-2"></i><?php echo e(__('Edit')); ?>

        </a>
        <a href="<?php echo e(route('admin.hrm.employees.index')); ?>" class="primary-btn">
            <i class="fa fa-arrow-left me-2"></i><?php echo e(__('Back')); ?>

        </a>
    </div>
</div>

<div class="settings-page-area">
    <div class="settings-page-right">
        
        <div class="section-wrap mb-4">
            <div class="row align-items-center">
                <div class="col-md-2 text-center">
                    <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center"
                        style="width:80px;height:80px;font-size:2rem;color:#fff;">
                        <?php echo e(strtoupper(substr($employee->first_name,0,1))); ?><?php echo e(strtoupper(substr($employee->last_name,0,1))); ?>

                    </div>
                </div>
                <div class="col-md-10">
                    <h4 class="mb-1"><?php echo e($employee->first_name); ?> <?php echo e($employee->last_name); ?>

                        <?php if($employee->status == EMPLOYEE_STATUS_ACTIVE): ?>
                            <span class="zBadge zBadge-complete ms-2"><?php echo e(__('Active')); ?></span>
                        <?php elseif($employee->status == EMPLOYEE_STATUS_ON_LEAVE): ?>
                            <span class="zBadge zBadge-warning ms-2"><?php echo e(__('On Leave')); ?></span>
                        <?php else: ?>
                            <span class="zBadge zBadge-deactive ms-2"><?php echo e(__('Terminated')); ?></span>
                        <?php endif; ?>
                    </h4>
                    <p class="text-muted mb-0"><?php echo e($employee->designation->name ?? 'N/A'); ?> &bull; <?php echo e($employee->department->name ?? 'N/A'); ?></p>
                    <p class="text-muted mb-0"><?php echo e($employee->employee_code); ?> &bull; <?php echo e($employee->email); ?></p>
                </div>
            </div>
        </div>

        
        <div class="row gy-4">
            <div class="col-md-6">
                <div class="section-wrap">
                    <h5 class="fw-600 mb-3"><?php echo e(__('Personal Information')); ?></h5>
                    <table class="table table-borderless">
                        <tr><td class="text-muted"><?php echo e(__('Phone')); ?></td><td><?php echo e($employee->phone ?? 'N/A'); ?></td></tr>
                        <tr><td class="text-muted"><?php echo e(__('Gender')); ?></td><td><?php echo e(ucfirst($employee->gender ?? 'N/A')); ?></td></tr>
                        <tr><td class="text-muted"><?php echo e(__('Date of Birth')); ?></td><td><?php echo e($employee->date_of_birth ?? 'N/A'); ?></td></tr>
                        <tr><td class="text-muted"><?php echo e(__('Address')); ?></td><td><?php echo e($employee->address ?? 'N/A'); ?></td></tr>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="section-wrap">
                    <h5 class="fw-600 mb-3"><?php echo e(__('Job Information')); ?></h5>
                    <table class="table table-borderless">
                        <tr><td class="text-muted"><?php echo e(__('Joining Date')); ?></td><td><?php echo e($employee->joining_date); ?></td></tr>
                        <tr><td class="text-muted"><?php echo e(__('Employment Type')); ?></td><td><?php echo e(ucfirst(str_replace('_', ' ', $employee->employment_type))); ?></td></tr>
                        <tr><td class="text-muted"><?php echo e(__('Basic Salary')); ?></td><td><?php echo e(showPrice($employee->basic_salary)); ?></td></tr>
                    </table>
                </div>
            </div>

            
            <div class="col-md-6">
                <div class="section-wrap">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                        <h5 class="fw-600 mb-0"><?php echo e(__('Recent Attendance')); ?></h5>
                        <select class="form-select form-control sf-select wide" id="attendanceFilterStatus" style="height: 36px; border-radius: 8px; font-size: 13px; min-width: 140px;">
                            <option value=""><?php echo e(__('All Status')); ?></option>
                            <option value="<?php echo e(ATTENDANCE_STATUS_PRESENT); ?>"><?php echo e(__('Present')); ?></option>
                            <option value="<?php echo e(ATTENDANCE_STATUS_LATE); ?>"><?php echo e(__('Late')); ?></option>
                            <option value="<?php echo e(ATTENDANCE_STATUS_ABSENT); ?>"><?php echo e(__('Absent')); ?></option>
                            <option value="<?php echo e(ATTENDANCE_STATUS_ON_LEAVE); ?>"><?php echo e(__('On Leave')); ?></option>
                        </select>
                    </div>
                    <input type="hidden" id="attendanceEmployeeId" value="<?php echo e($employee->id); ?>">
                    <input type="hidden" id="attendance-history-route" value="<?php echo e(route('admin.hrm.employees.attendance.data', $employee->id)); ?>">
                    <table class="display primary-table dataTable dtr-inline" id="attendanceHistoryTable">
                        <thead>
                            <tr>
                                <th class="keep-show"><?php echo e(__("Date")); ?></th>
                                <th><?php echo e(__("Check In")); ?></th>
                                <th><?php echo e(__("Check Out")); ?></th>
                                <th><?php echo e(__("Status")); ?></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            
            <div class="col-md-6">
                <div class="section-wrap">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                        <h5 class="fw-600 mb-0"><?php echo e(__('Recent Leaves')); ?></h5>
                        <select class="form-select form-control sf-select wide" id="leaveFilterStatus" style="height: 36px; border-radius: 8px; font-size: 13px; min-width: 140px;">
                            <option value=""><?php echo e(__('All Status')); ?></option>
                            <option value="<?php echo e(LEAVE_STATUS_PENDING); ?>"><?php echo e(__('Pending')); ?></option>
                            <option value="<?php echo e(LEAVE_STATUS_APPROVED); ?>"><?php echo e(__('Approved')); ?></option>
                            <option value="<?php echo e(LEAVE_STATUS_REJECTED); ?>"><?php echo e(__('Rejected')); ?></option>
                        </select>
                    </div>
                    <input type="hidden" id="leaveEmployeeId" value="<?php echo e($employee->id); ?>">
                    <input type="hidden" id="leave-history-route" value="<?php echo e(route('admin.hrm.employees.leaves.data', $employee->id)); ?>">
                    <table class="display primary-table dataTable dtr-inline" id="leaveHistoryTable">
                        <thead>
                            <tr>
                                <th class="keep-show"><?php echo e(__("Type")); ?></th>
                                <th><?php echo e(__("Start Date")); ?></th>
                                <th><?php echo e(__("End Date")); ?></th>
                                <th><?php echo e(__("Days")); ?></th>
                                <th><?php echo e(__("Status")); ?></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            
            <div class="col-12">
                <div class="section-wrap">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                        <h5 class="fw-600 mb-0"><?php echo e(__('Recent Payroll')); ?></h5>
                        <select class="form-select form-control sf-select wide" id="payrollFilterStatus" style="height: 36px; border-radius: 8px; font-size: 13px; min-width: 140px;">
                            <option value=""><?php echo e(__('All Status')); ?></option>
                            <option value="<?php echo e(PAYMENT_STATUS_PAID); ?>"><?php echo e(__('Paid')); ?></option>
                            <option value="<?php echo e(PAYMENT_STATUS_PENDING); ?>"><?php echo e(__('Unpaid')); ?></option>
                        </select>
                    </div>
                    <input type="hidden" id="payrollEmployeeId" value="<?php echo e($employee->id); ?>">
                    <input type="hidden" id="payroll-history-route" value="<?php echo e(route('admin.hrm.employees.payrolls.data', $employee->id)); ?>">
                    <table class="display primary-table dataTable dtr-inline" id="payrollHistoryTable">
                        <thead>
                            <tr>
                                <th class="keep-show"><?php echo e(__("Month")); ?></th>
                                <th><?php echo e(__("Basic")); ?></th>
                                <th><?php echo e(__("Allowances")); ?></th>
                                <th><?php echo e(__("Deductions")); ?></th>
                                <th><?php echo e(__("Net Salary")); ?></th>
                                <th><?php echo e(__("Status")); ?></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
<script src="<?php echo e(asset('admin/js/employee-show-attendance.js')); ?>?ver=<?php echo e(env('VERSION', 0)); ?>"></script>
<script src="<?php echo e(asset('admin/js/employee-show-leaves.js')); ?>?ver=<?php echo e(env('VERSION', 0)); ?>"></script>
<script src="<?php echo e(asset('admin/js/employee-show-payrolls.js')); ?>?ver=<?php echo e(env('VERSION', 0)); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('auto_posts.admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\hrm\employees\show.blade.php ENDPATH**/ ?>