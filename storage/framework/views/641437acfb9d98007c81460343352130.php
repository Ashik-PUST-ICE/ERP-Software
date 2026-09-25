<?php $__env->startPush('title'); ?> <?php echo e($title); ?> <?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="section-title">
    <h2 class="title"><?php echo e(__($title)); ?></h2>
    <a href="<?php echo e(route('admin.hrm.employees.index')); ?>" class="primary-btn">
        <i class="fa fa-arrow-left me-2"></i><?php echo e(__('Back to List')); ?>

    </a>
</div>

<div class="settings-page-area">
    <div class="settings-page-right">
        <div class="section-wrap">
            <?php $isEdit = isset($employee); ?>
            <form action="<?php echo e($isEdit ? route('admin.hrm.employees.update', $employee->id) : route('admin.hrm.employees.store')); ?>"
                method="POST">
                <?php echo csrf_field(); ?>
                <?php if($isEdit): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

                <div class="primary-form">
                    <div class="row gy-3">
                        
                        <div class="col-12">
                            <h5 class="fw-600 mb-2 d-flex align-items-center gap-2" style="color:#0f172a;">
                                <span style="width:4px;height:20px;background:#4778c7;border-radius:4px;display:inline-block;"></span>
                                <i class="fa-solid fa-user text-primary"></i>
                                <?php echo e(__('Personal Information')); ?>

                            </h5>
                            <hr style="border-color:#f1f5f9;margin-bottom:16px;">
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('First Name')); ?> <span class="required">*</span></label>
                                <input type="text" class="form-control" name="first_name"
                                    value="<?php echo e(old('first_name', $employee->first_name ?? '')); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Last Name')); ?> <span class="required">*</span></label>
                                <input type="text" class="form-control" name="last_name"
                                    value="<?php echo e(old('last_name', $employee->last_name ?? '')); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Email')); ?> <span class="required">*</span></label>
                                <input type="email" class="form-control" name="email"
                                    value="<?php echo e(old('email', $employee->email ?? '')); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Phone')); ?></label>
                                <input type="text" class="form-control" name="phone"
                                    value="<?php echo e(old('phone', $employee->phone ?? '')); ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Gender')); ?></label>
                                <select class="form-control" name="gender">
                                    <option value=""><?php echo e(__('Select')); ?></option>
                                    <option value="<?php echo e(GENDER_MALE); ?>" <?php echo e(old('gender', $employee->gender ?? '') == GENDER_MALE ? 'selected' : ''); ?>><?php echo e(__('Male')); ?></option>
                                    <option value="<?php echo e(GENDER_FEMALE); ?>" <?php echo e(old('gender', $employee->gender ?? '') == GENDER_FEMALE ? 'selected' : ''); ?>><?php echo e(__('Female')); ?></option>
                                    <option value="<?php echo e(GENDER_OTHER); ?>" <?php echo e(old('gender', $employee->gender ?? '') == GENDER_OTHER ? 'selected' : ''); ?>><?php echo e(__('Other')); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Date of Birth')); ?></label>
                                <input type="date" class="form-control" name="date_of_birth"
                                    value="<?php echo e(old('date_of_birth', $employee->date_of_birth ?? '')); ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Joining Date')); ?> <span class="required">*</span></label>
                                <input type="date" class="form-control" name="joining_date"
                                    value="<?php echo e(old('joining_date', $employee->joining_date ?? '')); ?>" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Address')); ?></label>
                                <textarea class="form-control" name="address" rows="2"><?php echo e(old('address', $employee->address ?? '')); ?></textarea>
                            </div>
                        </div>

                        
                        <div class="col-12 mt-3">
                            <h5 class="fw-600 mb-2 d-flex align-items-center gap-2" style="color:#0f172a;">
                                <span style="width:4px;height:20px;background:#2c9567;border-radius:4px;display:inline-block;"></span>
                                <i class="fa-solid fa-briefcase text-success"></i>
                                <?php echo e(__('Job Information')); ?>

                            </h5>
                            <hr style="border-color:#f1f5f9;margin-bottom:16px;">
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Department')); ?> <span class="required">*</span></label>
                                <select class="form-control" name="department_id" id="department_id" required>
                                    <option value=""><?php echo e(__('Select Department')); ?></option>
                                    <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($dept->id); ?>"
                                            <?php echo e(old('department_id', $employee->department_id ?? '') == $dept->id ? 'selected' : ''); ?>>
                                            <?php echo e($dept->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Designation')); ?> <span class="required">*</span></label>
                                <select class="form-control" name="designation_id" id="designation_id" required>
                                    <option value=""><?php echo e(__('Select Designation')); ?></option>
                                    <?php if($isEdit && isset($designations)): ?>
                                        <?php $__currentLoopData = $designations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $desig): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($desig->id); ?>" <?php echo e($employee->designation_id == $desig->id ? 'selected' : ''); ?>><?php echo e($desig->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Employment Type')); ?> <span class="required">*</span></label>
                                <select class="form-control" name="employment_type" required>
                                    <option value="<?php echo e(EMPLOYMENT_TYPE_FULL_TIME); ?>" <?php echo e(old('employment_type', $employee->employment_type ?? '') == EMPLOYMENT_TYPE_FULL_TIME ? 'selected' : ''); ?>><?php echo e(__('Full Time')); ?></option>
                                    <option value="<?php echo e(EMPLOYMENT_TYPE_PART_TIME); ?>" <?php echo e(old('employment_type', $employee->employment_type ?? '') == EMPLOYMENT_TYPE_PART_TIME ? 'selected' : ''); ?>><?php echo e(__('Part Time')); ?></option>
                                    <option value="<?php echo e(EMPLOYMENT_TYPE_CONTRACT); ?>" <?php echo e(old('employment_type', $employee->employment_type ?? '') == EMPLOYMENT_TYPE_CONTRACT ? 'selected' : ''); ?>><?php echo e(__('Contract')); ?></option>
                                    <option value="<?php echo e(EMPLOYMENT_TYPE_INTERN); ?>" <?php echo e(old('employment_type', $employee->employment_type ?? '') == EMPLOYMENT_TYPE_INTERN ? 'selected' : ''); ?>><?php echo e(__('Intern')); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Basic Salary')); ?> <span class="required">*</span></label>
                                <input type="number" step="0.01" class="form-control" name="basic_salary"
                                    value="<?php echo e(old('basic_salary', $employee->basic_salary ?? '')); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Status')); ?> <span class="required">*</span></label>
                                <select class="form-control" name="status" required>
                                    <option value="<?php echo e(EMPLOYEE_STATUS_ACTIVE); ?>" <?php echo e(old('status', $employee->status ?? EMPLOYEE_STATUS_ACTIVE) == EMPLOYEE_STATUS_ACTIVE ? 'selected' : ''); ?>><?php echo e(__('Active')); ?></option>
                                    <option value="<?php echo e(EMPLOYEE_STATUS_ON_LEAVE); ?>" <?php echo e(old('status', $employee->status ?? '') == EMPLOYEE_STATUS_ON_LEAVE ? 'selected' : ''); ?>><?php echo e(__('On Leave')); ?></option>
                                    <option value="<?php echo e(EMPLOYEE_STATUS_TERMINATED); ?>" <?php echo e(old('status', $employee->status ?? '') == EMPLOYEE_STATUS_TERMINATED ? 'selected' : ''); ?>><?php echo e(__('Terminated')); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="btn-list mt-4 pt-3" style="border-top: 2px solid #f1f5f9;">
                    <a href="<?php echo e(route('admin.hrm.employees.index')); ?>" class="primary-btn-outline d-inline-flex align-items-center gap-2">
                        <i class="fa fa-arrow-left"></i><?php echo e(__('Cancel')); ?>

                    </a>
                    <button type="submit" class="primary-btn d-inline-flex align-items-center gap-2">
                        <i class="fa-solid <?php echo e($isEdit ? 'fa-floppy-disk' : 'fa-user-plus'); ?>"></i>
                        <?php echo e($isEdit ? __('Update Employee') : __('Save Employee')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
<script>
$(document).ready(function () {
    $('#department_id').on('change', function () {
        var deptId = $(this).val();
        var $desigSelect = $('#designation_id');
        $desigSelect.html('<option value=""><?php echo e(__("Loading...")); ?></option>');
        if (deptId) {
            $.get('<?php echo e(route("admin.hrm.employees.getDesignations")); ?>', { department_id: deptId }, function (data) {
                var options = '<option value=""><?php echo e(__("Select Designation")); ?></option>';
                $.each(data, function (i, item) {
                    options += '<option value="' + item.id + '">' + item.name + '</option>';
                });
                $desigSelect.html(options);
            });
        } else {
            $desigSelect.html('<option value=""><?php echo e(__("Select Designation")); ?></option>');
        }
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('auto_posts.admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\hrm\employees\create.blade.php ENDPATH**/ ?>