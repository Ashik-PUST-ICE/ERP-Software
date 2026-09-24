<?php $__env->startPush('title'); ?>
<?php echo e($title); ?>

<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<div class="section-title">
    <h2 class="title"><?php echo e(__($title)); ?></h2>
    <button type="button" class="primary-btn" data-bs-toggle="modal" data-bs-target="#assignPackageModal">
        <i class="fa fa-plus me-2"></i><?php echo e(__('Assign Package')); ?>

    </button>
</div>
<div class="section-wrap">
    <div class="table-waraper">
        <div class="search-input-wrap">
            <label class="icon" for="searchData">
                <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.625 10.625L11.6875 11.6875" stroke="#6E5858" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path
                        d="M11.9944 13.4762C11.5852 13.067 11.5852 12.4035 11.9944 11.9944C12.4035 11.5852 13.067 11.5852 13.4762 11.9944L14.9222 13.4405C15.3314 13.8497 15.3314 14.5131 14.9222 14.9222C14.5131 15.3314 13.8497 15.3314 13.4405 14.9222L11.9944 13.4762Z"
                        stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                        d="M11.6872 6.72982C11.6872 3.99141 9.46726 1.77148 6.72884 1.77148C3.99043 1.77148 1.77051 3.99141 1.77051 6.72982C1.77051 9.46824 3.99043 11.6882 6.72884 11.6882C9.46726 11.6882 11.6872 9.46824 11.6872 6.72982Z"
                        stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </label>
            <input type="text" class="search-input" id="searchData" placeholder="<?php echo e(__('Search By Name...')); ?>" />
        </div>

        <!-- Package Filter Tabs -->
        <div class="mb-3">
            <ul class="nav post-tabs" id="orderTab" role="tablist">
                        <li class="nav-item" role="presentation">
                    <button class="nav-link packageId active" data-bs-toggle="tab" type="button" value="All" role="tab">
                        <?php echo e(__('All')); ?>

                    </button>
                </li>
                <?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link packageId" data-bs-toggle="tab" type="button" value="<?php echo e($data->id); ?>"
                        role="tab">
                        <?php echo e($data->name); ?>

                            </button>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                        </div>

        <input type="hidden" id="packagesUserRoute" value="<?php echo e(route('super_admin.packages.user')); ?>">
        <table class="display primary-table dataTable dtr-inline" id="commonDataTable">
                            <thead>
                <tr>
                    <th class="keep-show"><?php echo e(__('SL')); ?></th>
                    <th><?php echo e(__('Name')); ?></th>
                    <th><?php echo e(__('Email')); ?></th>
                    <th><?php echo e(__('Package Name')); ?></th>
                    <th><?php echo e(__('Gateway')); ?></th>
                    <th><?php echo e(__('Start Date')); ?></th>
                    <th><?php echo e(__('End Date')); ?></th>
                    <th><?php echo e(__('Status')); ?></th>
                    <th class="keep-show"><?php echo e(__('Action')); ?></th>
                </tr>
                            </thead>
            <tbody>
            </tbody>
                        </table>
                    <div id="user-packages-pagination-wrap" class="d-flex justify-content-center mt-20 tablePagi"></div>
    </div>
</div>

<!-- Assign Package Modal -->
<div class="modal fade zModalTwo" id="assignPackageModal" tabindex="-1" aria-labelledby="assignPackageModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            <form id="assign-package-form" method="POST" action="<?php echo e(route('super_admin.packages.assign')); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="gateway" value="cash">
                <input type="hidden" name="currency" value="<?php echo e(currentCurrencyType()); ?>">
                <div class="modal-body zModalTwo-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e(__('Assign Package')); ?></h4>
                        <div class="mClose">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </div>
                    <div class="primary-form">
                        <div class="row gy-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label"><?php echo e(__('User')); ?><span class="required">*</span></label>
                                    <select name="user_id" class="select form-control wide sf-select-without-search"
                                        id="user_id" required>
                                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($user->id); ?>">
                                            <?php echo e($user->name.' - '.$user->email); ?>

                                        </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label"><?php echo e(__('Package')); ?><span class="required">*</span></label>
                                    <select name="package_id" class="select form-control wide sf-select-without-search"
                                        id="package_id" required>
                                        <?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($package->id); ?>"><?php echo e($package->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label"><?php echo e(__('Duration Type')); ?><span
                                            class="required">*</span></label>
                                    <select name="duration_type"
                                        class="select form-control wide sf-select-without-search" id="duration_type"
                                        required>
                                        <option value="1"><?php echo e(__('Monthly')); ?></option>
                                        <option value="2"><?php echo e(__('Yearly')); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn-list mt-4 pt-3 border-top">
                        <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
                        <button type="submit" class="primary-btn"><?php echo e(__('Assign')); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade zModalTwo" id="edit-modal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            <!-- Content loaded via AJAX -->
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
<script src="<?php echo e(asset('super_admin/js/user-packages.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('auto_posts.super_admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\super_admin\packages\user.blade.php ENDPATH**/ ?>