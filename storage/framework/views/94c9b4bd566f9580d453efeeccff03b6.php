
<?php $__env->startPush('title'); ?> <?php echo e(__('HRM Dashboard')); ?> <?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
<script src="<?php echo e(asset('admin/js/hrm-dashboard.js')); ?>?ver=<?php echo e(env('VERSION', 0)); ?>"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<input type="hidden" id="dashboard-data-url" value="<?php echo e(route('admin.hrm.dashboard.data')); ?>">

<div class="section-title">
    <h2 class="title"><?php echo e(__('HRM Dashboard')); ?></h2>
    <span class="text-muted" style="font-size:1.3rem;"><?php echo e(now()->format('l, d F Y')); ?></span>
</div>


<div class="row gy-4 mb-20 hrm-dashboard-kpis">

    
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon">
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="19" cy="19" r="19" fill="#02BCFF"/>
                    <path d="M19 19C21.2091 19 23 17.2091 23 15C23 12.7909 21.2091 11 19 11C16.7909 11 15 12.7909 15 15C15 17.2091 16.7909 19 19 19Z" stroke="white" stroke-width="1.5"/>
                    <path d="M11 27C11 23.134 14.134 20 18 20H20C23.866 20 27 23.134 27 27" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </span>
            <div class="card-info">
                <h2 id="kpiTotalEmployees"><?php echo e($totalEmployees); ?></h2>
                <h3><?php echo e(__('Total Employees')); ?></h3>
            </div>
            <span class="card-status up">
                <a href="<?php echo e(route('admin.hrm.employees.index')); ?>" style="color:inherit; text-decoration:none;"><?php echo e(__('View All')); ?></a>
                <span class="arrow"><i class="fa-solid fa-arrow-up"></i></span>
            </span>
        </div>
    </div>

    
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon">
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="19" cy="19" r="19" fill="#0FA958"/>
                    <path d="M13 19L17 23L25 15" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
            <div class="card-info">
                <h2 id="kpiTodayPresent"><?php echo e($todayPresent); ?></h2>
                <h3><?php echo e(__("Today's Present")); ?></h3>
            </div>
            <span class="card-status <?php echo e($todayPresent > 0 ? 'up' : 'down'); ?>">
                <a href="<?php echo e(route('admin.hrm.attendance.index')); ?>" style="color:inherit; text-decoration:none;"><?php echo e(__('Attendance')); ?></a>
                <span class="arrow"><i class="fa-solid fa-arrow-<?php echo e($todayPresent > 0 ? 'up' : 'down'); ?>"></i></span>
            </span>
        </div>
    </div>

    
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon">
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="19" cy="19" r="19" fill="#FF4F02"/>
                    <path d="M19 13V19L22 22" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M11 19C11 14.5817 14.5817 11 19 11C23.4183 11 27 14.5817 27 19C27 23.4183 23.4183 27 19 27C14.5817 27 11 23.4183 11 19Z" stroke="white" stroke-width="1.5"/>
                </svg>
            </span>
            <div class="card-info">
                <h2 id="kpiPendingLeaves"><?php echo e($pendingLeaves); ?></h2>
                <h3><?php echo e(__('Pending Leaves')); ?></h3>
            </div>
            <span class="card-status <?php echo e($pendingLeaves > 0 ? 'down' : 'up'); ?>">
                <a href="<?php echo e(route('admin.hrm.leaves.index')); ?>" style="color:inherit; text-decoration:none;"><?php echo e(__('Review')); ?></a>
                <span class="arrow"><i class="fa-solid fa-arrow-<?php echo e($pendingLeaves > 0 ? 'up' : 'up'); ?>"></i></span>
            </span>
        </div>
    </div>

    
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon">
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="19" cy="19" r="19" fill="#0D0D0D"/>
                    <path d="M13 15H25M13 19H25M13 23H20" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M11 13C11 11.8954 11.8954 11 13 11H25C26.1046 11 27 11.8954 27 13V25C27 26.1046 26.1046 27 25 27H13C11.8954 27 11 26.1046 11 25V13Z" stroke="white" stroke-width="1.5"/>
                </svg>
            </span>
            <div class="card-info">
                <h2 style="font-size:2rem;" id="kpiMonthlyPayroll"><?php echo e(showPrice($monthlyPayroll)); ?></h2>
                <h3><?php echo e(__('Monthly Payroll')); ?></h3>
            </div>
            <span class="card-status up">
                <a href="<?php echo e(route('admin.hrm.payroll.index')); ?>" style="color:inherit; text-decoration:none;"><?php echo e(__('View Payroll')); ?></a>
                <span class="arrow"><i class="fa-solid fa-arrow-up"></i></span>
            </span>
        </div>
    </div>

</div>


<div class="row gy-4 mb-20 hrm-dashboard-kpis hrm-dashboard-kpis-secondary">

    
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon">
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="19" cy="19" r="19" fill="#FFC402"/>
                    <path d="M19 13V19" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M19 22V23" stroke="white" stroke-width="2" stroke-linecap="round"/>
                    <path d="M11 19C11 14.5817 14.5817 11 19 11C23.4183 11 27 14.5817 27 19C27 23.4183 23.4183 27 19 27C14.5817 27 11 23.4183 11 19Z" stroke="white" stroke-width="1.5"/>
                </svg>
            </span>
            <div class="card-info">
                <h2 id="kpiTodayLate"><?php echo e($todayLate); ?></h2>
                <h3><?php echo e(__('Today Late')); ?></h3>
            </div>
            <span class="card-status <?php echo e($todayLate > 0 ? 'down' : 'up'); ?>">
                <?php echo e($todayLate); ?> <?php echo e(__('Late')); ?>

                <span class="arrow"><i class="fa-solid fa-arrow-<?php echo e($todayLate > 0 ? 'down' : 'up'); ?>"></i></span>
            </span>
        </div>
    </div>

    
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon">
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="19" cy="19" r="19" fill="#FF4F02"/>
                    <path d="M15 15L23 23M23 15L15 23" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </span>
            <div class="card-info">
                <h2 id="kpiTodayAbsent"><?php echo e($todayAbsent); ?></h2>
                <h3><?php echo e(__('Today Absent')); ?></h3>
            </div>
            <span class="card-status <?php echo e($todayAbsent > 0 ? 'down' : 'up'); ?>">
                <?php echo e($todayAbsent); ?> <?php echo e(__('Absent')); ?>

                <span class="arrow"><i class="fa-solid fa-arrow-<?php echo e($todayAbsent > 0 ? 'down' : 'up'); ?>"></i></span>
            </span>
        </div>
    </div>

    
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon">
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="19" cy="19" r="19" fill="#0FA958"/>
                    <path d="M11 27V15L19 11L27 15V27" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M16 27V22H22V27" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M15 18H15.01M19 18H19.01M23 18H23.01" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </span>
            <div class="card-info">
                <h2 id="kpiTotalDepartments"><?php echo e($totalDepartments); ?></h2>
                <h3><?php echo e(__('Departments')); ?></h3>
            </div>
            <span class="card-status up">
                <a href="<?php echo e(route('admin.hrm.departments.index')); ?>" style="color:inherit; text-decoration:none;"><?php echo e(__('Manage')); ?></a>
                <span class="arrow"><i class="fa-solid fa-arrow-up"></i></span>
            </span>
        </div>
    </div>

    
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon">
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="19" cy="19" r="19" fill="#6B02FF"/>
                    <path d="M19 11V19L24 24" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M11 19C11 14.5817 14.5817 11 19 11C23.4183 11 27 14.5817 27 19C27 23.4183 23.4183 27 19 27C14.5817 27 11 23.4183 11 19Z" stroke="white" stroke-width="1.5"/>
                </svg>
            </span>
            <div class="card-info">
                <h2 id="kpiNotCheckedIn"><?php echo e($totalEmployees - $todayPresent); ?></h2>
                <h3><?php echo e(__('Not Checked In')); ?></h3>
            </div>
            <span class="card-status <?php echo e(($totalEmployees - $todayPresent) > 0 ? 'down' : 'up'); ?>">
                <?php echo e(__('Today')); ?>

                <span class="arrow"><i class="fa-solid fa-arrow-<?php echo e(($totalEmployees - $todayPresent) > 0 ? 'down' : 'up'); ?>"></i></span>
            </span>
        </div>
    </div>

</div>


<div class="row gy-4">

    
    <div class="col-xl-6 col-lg-6">
        <div class="section-wrap h-100">
            <div class="section-small-title">
                <h3 class="title"><?php echo e(__('Recent Employees')); ?></h3>
                <a href="<?php echo e(route('admin.hrm.employees.index')); ?>" class="text-primary" style="font-size:1.2rem;"><?php echo e(__('View All')); ?></a>
            </div>
            <table class="display primary-table w-100" id="recentEmployeesTable" data-url="<?php echo e(route('admin.hrm.employees.index')); ?>">
                <thead>
                    <tr>
                        <th class="keep-show"><?php echo e(__('Name')); ?></th>
                        <th><?php echo e(__('Employee Code')); ?></th>
                        <th><?php echo e(__('Department')); ?></th>
                        <th><?php echo e(__('Designation')); ?></th>
                        <th><?php echo e(__('Status')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recentEmployees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <strong><?php echo e($emp->first_name); ?> <?php echo e($emp->last_name); ?></strong><br>
                            <small class="text-muted"><?php echo e($emp->employee_code); ?></small>
                        </td>
                        <td><?php echo e($emp->department->name ?? 'N/A'); ?></td>
                        <td><?php echo e($emp->designation->name ?? 'N/A'); ?></td>
                        <td>
                            <?php if($emp->status == EMPLOYEE_STATUS_ACTIVE): ?>
                                <span class="zBadge zBadge-complete"><?php echo e(__('Active')); ?></span>
                            <?php else: ?>
                                <span class="zBadge zBadge-deactive"><?php echo e(ucfirst($emp->status)); ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-3"><?php echo e(__('No employees found')); ?></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="col-xl-6 col-lg-6">
        <div class="section-wrap h-100">
            <div class="section-small-title">
                <h3 class="title"><?php echo e(__('Pending Leave Requests')); ?></h3>
                <a href="<?php echo e(route('admin.hrm.leaves.index')); ?>" class="text-primary" style="font-size:1.2rem;"><?php echo e(__('View All')); ?></a>
            </div>
            <table class="display primary-table w-100" id="pendingLeavesTable" data-url="<?php echo e(route('admin.hrm.leaves.index')); ?>">
                <thead>
                    <tr>
                        <th><?php echo e(__('Employee')); ?></th>
                        <th><?php echo e(__('Type')); ?></th>
                        <th><?php echo e(__('Duration')); ?></th>
                        <th><?php echo e(__('Status')); ?></th>
                        <th><?php echo e(__('Action')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $pendingLeaveList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($leave->employee->first_name ?? ''); ?> <?php echo e($leave->employee->last_name ?? ''); ?></td>
                        <td><?php echo e(ucfirst($leave->leave_type)); ?></td>
                        <td><?php echo e($leave->days_count); ?></td>
                        <td><?php echo e(optional($leave->start_date)->format('d M')); ?></td>
                        <td>
                            <a href="<?php echo e(route('admin.hrm.leaves.approve', $leave->id)); ?>"
                               class="zBadge zBadge-complete"><?php echo e(__('Approve')); ?></a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-3"><?php echo e(__('No pending leaves')); ?></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="col-12">
        <div class="section-wrap">
            <div class="section-small-title mb-20">
                <h3 class="title"><?php echo e(__('Department Headcount')); ?></h3>
            </div>
            <div class="row gy-3 hrm-dashboard-kpis department-headcount-kpis" id="departmentStatsContainer">
                <?php
                    $topDepartments = $departmentStats->sortByDesc('employees_count')->take(3);
                ?>
                <?php $__empty_1 = true; $__currentLoopData = $topDepartments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-6">
                    <div class="card-box">
                        <div class="card-info">
                            <h2><?php echo e($dept->employees_count); ?></h2>
                            <h3><?php echo e($dept->name); ?></h3>
                        </div>
                        <span class="card-status up">
                            <span class="arrow"><i class="fa-solid fa-arrow-up"></i></span>
                        </span>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-12 text-center text-muted"><?php echo e(__('No departments found')); ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('auto_posts.admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\hrm\dashboard.blade.php ENDPATH**/ ?>