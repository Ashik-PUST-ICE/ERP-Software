<?php $__env->startPush('title'); ?> <?php echo e($title); ?> <?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="section-title">
    <h2 class="title"><?php echo e(__($title)); ?></h2>
    <button type="button" class="primary-btn" data-bs-toggle="modal" data-bs-target="#add-modal">
        <i class="fa fa-plus me-2"></i><?php echo e(__('Add Department')); ?>

    </button>
</div>

<div class="settings-page-area">
    <div class="settings-page-right">
        <div class="section-wrap">
            <div class="table-waraper">
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
                        placeholder="<?php echo e(__('Search Departments...')); ?>" />
                </div>
                <input type="hidden" id="department-data-route" value="<?php echo e(route('admin.hrm.departments.index')); ?>">
                <table class="display primary-table dataTable dtr-inline" id="departmentDataTable">
                    <thead>
                        <tr>
                            <th class="keep-show"><?php echo e(__("SL")); ?></th>
                            <th><?php echo e(__("Name")); ?></th>
                            <th><?php echo e(__("Code")); ?></th>
                            <th><?php echo e(__("Employees")); ?></th>
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

<!-- Add Modal -->
<div class="modal fade zModalTwo" id="add-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            <form class="ajax reset" action="<?php echo e(route('admin.hrm.departments.store')); ?>" method="post"
                data-handler="commonResponseWithPageLoad">
                <?php echo csrf_field(); ?>
                <div class="modal-body zModalTwo-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e(__('Add Department')); ?></h4>
                        <div class="mClose">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </div>
                    <div class="primary-form">
                        <div class="row gy-3">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="form-label"><?php echo e(__('Department Name')); ?> <span class="required">*</span></label>
                                    <input type="text" class="form-control" name="name" placeholder="<?php echo e(__('e.g. Human Resources')); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label"><?php echo e(__('Code')); ?> <span class="required">*</span></label>
                                    <input type="text" class="form-control" name="code" placeholder="<?php echo e(__('e.g. HR')); ?>" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label"><?php echo e(__('Description')); ?></label>
                                    <textarea class="form-control" name="description" rows="2" placeholder="<?php echo e(__('Short description...')); ?>"></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label"><?php echo e(__('Status')); ?> <span class="required">*</span></label>
                                    <select class="form-control" name="status" required>
                                        <option value="<?php echo e(STATUS_ACTIVE); ?>"><?php echo e(__('Active')); ?></option>
                                        <option value="<?php echo e(STATUS_DEACTIVATE); ?>"><?php echo e(__('Deactivate')); ?></option>
                                    </select>
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

<!-- Edit Modal -->
<div class="modal fade zModalTwo" id="edit-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            <!-- Content loaded via AJAX -->
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
<script src="<?php echo e(asset('admin/js/hrm-departments.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('auto_posts.admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\hrm\departments\index.blade.php ENDPATH**/ ?>