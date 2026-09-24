

<?php $__env->startPush('title'); ?> <?php echo e($title); ?> <?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="section-title">
    <h2 class="title"><?php echo e(__($title)); ?></h2>
</div>

<div class="settings-page-area">
    <div class="settings-page-right">
        
        <div class="row gy-4 mb-20 hrm-dashboard-kpis payroll-summary-kpis">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
                <div class="card-box">
                    <span class="card-icon"><i class="fa-solid fa-money-bill"></i></span>
                    <div class="card-info">
                        <h2><?php echo e(showPrice($summary['total_basic'])); ?></h2>
                        <h3><?php echo e(__('Total Basic')); ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
                <div class="card-box">
                    <span class="card-icon"><i class="fa-solid fa-hand-holding-dollar"></i></span>
                    <div class="card-info">
                        <h2><?php echo e(showPrice($summary['total_allowances'])); ?></h2>
                        <h3><?php echo e(__('Total Allowances')); ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
                <div class="card-box">
                    <span class="card-icon"><i class="fa-solid fa-circle-minus"></i></span>
                    <div class="card-info">
                        <h2><?php echo e(showPrice($summary['total_deductions'])); ?></h2>
                        <h3><?php echo e(__('Total Deductions')); ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
                <div class="card-box">
                    <span class="card-icon"><i class="fa-solid fa-sack-dollar"></i></span>
                    <div class="card-info">
                        <h2><?php echo e(showPrice($summary['total_net'])); ?></h2>
                        <h3><?php echo e(__('Net Payroll')); ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-wrap">
            
            <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                <form method="GET" action="<?php echo e(route('admin.hrm.payroll.index')); ?>" class="d-flex gap-2">
                    <input type="month" class="form-control" name="month" value="<?php echo e($month); ?>">
                    <button type="submit" class="primary-btn"><?php echo e(__('Filter')); ?></button>
                </form>

                <form method="POST" action="<?php echo e(route('admin.hrm.payroll.generate')); ?>" class="ms-auto">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="month" value="<?php echo e($month); ?>">
                    <button type="submit" class="primary-btn">
                        <i class="fa fa-cog me-1"></i><?php echo e(__('Generate Payroll')); ?>

                    </button>
                </form>

                <a class="primary-btn" href="<?php echo e(route('admin.hrm.payroll.print', ['month' => $month])); ?>" target="_blank">
                    <i class="fa fa-print me-1"></i><?php echo e(__('Print')); ?>

                </a>

                <div class="dropdown">
                    <button class="primary-btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fa fa-download me-1"></i><?php echo e(__('Export')); ?>

                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="<?php echo e(route('admin.hrm.payroll.export', ['month' => $month])); ?>">
                                <i class="fa fa-file-excel me-2"></i><?php echo e(__('Export Excel')); ?>

                            </a>
                        </li>
                    </ul>
                </div>

                <?php if($summary['unpaid_count'] > 0): ?>
                <form method="POST" action="<?php echo e(route('admin.hrm.payroll.bulkPay')); ?>">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="month" value="<?php echo e($month); ?>">
                    <button type="submit" class="primary-btn"
                        onclick="return confirm('<?php echo e(__('Mark all unpaid as paid?')); ?>')">
                        <i class="fa fa-check me-1"></i><?php echo e(__('Bulk Pay')); ?> (<?php echo e($summary['unpaid_count']); ?>)
                    </button>
                </form>
                <?php endif; ?>
            </div>

            <div class="d-flex gap-3 mb-3">
                <span class="zBadge zBadge-complete"><?php echo e($summary['paid_count']); ?> <?php echo e(__('Paid')); ?></span>
                <span class="zBadge zBadge-warning"><?php echo e($summary['unpaid_count']); ?> <?php echo e(__('Unpaid')); ?></span>
            </div>

            <div class="table-waraper mt-3">
                <div class="search-input-wrap mb-3">
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
                        placeholder="<?php echo e(__('Search Payroll...')); ?>" />
                </div>
                <input type="hidden" id="payroll-data-route" value="<?php echo e(route('admin.hrm.payroll.index')); ?>">
                <table class="display primary-table dataTable dtr-inline" id="payrollDataTable">
                    <thead>
                        <tr>
                            <th class="keep-show"><?php echo e(__("SL")); ?></th>
                            <th><?php echo e(__("Employee")); ?></th>
                            <th><?php echo e(__("Department")); ?></th>
                            <th><?php echo e(__("Basic")); ?></th>
                            <th><?php echo e(__("Allowances")); ?></th>
                            <th><?php echo e(__("Deductions")); ?></th>
                            <th><?php echo e(__("Net Salary")); ?></th>
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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
<script src="<?php echo e(asset('admin/js/hrm-payroll.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('auto_posts.admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\hrm\payroll\index.blade.php ENDPATH**/ ?>