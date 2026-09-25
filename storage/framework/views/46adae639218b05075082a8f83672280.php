<?php $__env->startPush('title'); ?>
    <?php echo e($title); ?>

<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
<div class="section-title">
    <h2 class="title"><?php echo e($title); ?></h2>
</div>
<div class="settings-page-area">
    <?php echo $__env->make('auto_posts.super_admin.setting.partials.general-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div class="settings-page-right">
        <div class="section-wrap">
            <div class="section-inner-title">
                <h3 class="title"><?php echo e(__($title)); ?></h3>
            </div>
            <div class="primary-form">
                <div class="alert alert-info mb-4" role="alert">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0 me-3">
                            <i class="fa-solid fa-info-circle fa-lg mt-1"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="alert-heading mb-2"><?php echo e(__('Important Instructions')); ?></h5>
                            <p class="mb-2"><?php echo e(__('You need to click on')); ?> <strong><?php echo e(__('"Storage Link"')); ?></strong> <?php echo e(__('button after changing')); ?> <strong><?php echo e(__('"Storage Driver"')); ?></strong>.</p>
                            <a href="<?php echo e(route('super_admin.setting.storage.link')); ?>" class="btn btn-sm btn-outline-primary mt-2">
                                <i class="fa-solid fa-link me-1"></i> <?php echo e(__('Storage Link')); ?>

                            </a>
                        </div>
                    </div>
                </div>
                <form id="storage-settings-form" class="ajax" action="<?php echo e(route('super_admin.setting.storage.update')); ?>" method="POST"
                      enctype="multipart/form-data" data-handler="settingCommonHandler">
                    <?php echo csrf_field(); ?>
                    <div class="row gy-4">
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="storage_driver" class="form-label"><?php echo e(__('Storage Driver')); ?><span class="required">*</span></label>
                                <select name="STORAGE_DRIVER" id="storage_driver"
                                        class="select form-control wide sf-select-without-search" required>
                                    <option
                                        value="<?php echo e(STORAGE_DRIVER_PUBLIC); ?>" <?php echo e(env('STORAGE_DRIVER') == STORAGE_DRIVER_PUBLIC ?  'selected':''); ?>><?php echo e(__('Public')); ?></option>
                                    <option
                                        value="<?php echo e(STORAGE_DRIVER_AWS); ?>" <?php echo e(env('STORAGE_DRIVER') == STORAGE_DRIVER_AWS ?  'selected':''); ?>><?php echo e(__('AWS')); ?></option>
                                    <option
                                        value="<?php echo e(STORAGE_DRIVER_WASABI); ?>" <?php echo e(env('STORAGE_DRIVER') == STORAGE_DRIVER_WASABI ?  'selected':''); ?>><?php echo e(__('Wasabi')); ?></option>
                                    <option
                                        value="<?php echo e(STORAGE_DRIVER_VULTR); ?>" <?php echo e(env('STORAGE_DRIVER') == STORAGE_DRIVER_VULTR ?  'selected':''); ?>><?php echo e(__('Vultr')); ?></option>
                                    <option
                                        value="<?php echo e(STORAGE_DRIVER_DO); ?>" <?php echo e(env('STORAGE_DRIVER') == STORAGE_DRIVER_DO ?  'selected':''); ?>><?php echo e(__('Digital Ocean (DO)')); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="d-none storage-driver" id="aws">
                        <div class="row gy-4 mt-3">
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="AWS_ACCESS_KEY_ID" class="form-label"><?php echo e(__('AWS Access Key ID')); ?><span class="required">*</span></label>
                                    <input type="text" name="AWS_ACCESS_KEY_ID" id="AWS_ACCESS_KEY_ID"
                                           value="<?php echo e(env('AWS_ACCESS_KEY_ID')); ?>"
                                           class="form-control" placeholder="<?php echo e(__('Enter AWS Access Key ID')); ?>">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="AWS_SECRET_ACCESS_KEY" class="form-label"><?php echo e(__('AWS Secret Access Key')); ?><span class="required">*</span></label>
                                    <input type="password" name="AWS_SECRET_ACCESS_KEY" id="AWS_SECRET_ACCESS_KEY"
                                           value="<?php echo e(env('AWS_SECRET_ACCESS_KEY')); ?>"
                                           class="form-control" placeholder="<?php echo e(__('Enter AWS Secret Access Key')); ?>">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="AWS_DEFAULT_REGION" class="form-label"><?php echo e(__('AWS Default Region')); ?><span class="required">*</span></label>
                                    <input type="text" name="AWS_DEFAULT_REGION" id="AWS_DEFAULT_REGION"
                                           value="<?php echo e(env('AWS_DEFAULT_REGION')); ?>"
                                           class="form-control" placeholder="<?php echo e(__('e.g., us-east-1')); ?>">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="AWS_BUCKET" class="form-label"><?php echo e(__('AWS Bucket')); ?><span class="required">*</span></label>
                                    <input type="text" name="AWS_BUCKET" id="AWS_BUCKET"
                                           value="<?php echo e(env('AWS_BUCKET')); ?>"
                                           class="form-control" placeholder="<?php echo e(__('Enter AWS Bucket Name')); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-none storage-driver" id="wasabi">
                        <div class="row gy-4 mt-3">
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="WASABI_ACCESS_KEY_ID" class="form-label"><?php echo e(__('WAS Access Key ID')); ?><span class="required">*</span></label>
                                    <input type="text" name="WASABI_ACCESS_KEY_ID" id="WASABI_ACCESS_KEY_ID"
                                           value="<?php echo e(env('WASABI_ACCESS_KEY_ID')); ?>"
                                           class="form-control" placeholder="<?php echo e(__('Enter Wasabi Access Key ID')); ?>">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="WASABI_SECRET_ACCESS_KEY" class="form-label"><?php echo e(__('WAS Secret Access Key')); ?><span class="required">*</span></label>
                                    <input type="password" name="WASABI_SECRET_ACCESS_KEY" id="WASABI_SECRET_ACCESS_KEY"
                                           value="<?php echo e(env('WASABI_SECRET_ACCESS_KEY')); ?>"
                                           class="form-control" placeholder="<?php echo e(__('Enter Wasabi Secret Access Key')); ?>">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="WASABI_DEFAULT_REGION" class="form-label"><?php echo e(__('WAS Default Region')); ?><span class="required">*</span></label>
                                    <input type="text" name="WASABI_DEFAULT_REGION" id="WASABI_DEFAULT_REGION"
                                           value="<?php echo e(env('WASABI_DEFAULT_REGION')); ?>"
                                           class="form-control" placeholder="<?php echo e(__('e.g., us-east-1')); ?>">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="WASABI_BUCKET" class="form-label"><?php echo e(__('WAS Bucket')); ?><span class="required">*</span></label>
                                    <input type="text" name="WASABI_BUCKET" id="WASABI_BUCKET"
                                           value="<?php echo e(env('WASABI_BUCKET')); ?>"
                                           class="form-control" placeholder="<?php echo e(__('Enter Wasabi Bucket Name')); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-none storage-driver" id="vultr">
                        <div class="row gy-4 mt-3">
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="VULTR_ACCESS_KEY_ID" class="form-label"><?php echo e(__('VULTR Access Key')); ?><span class="required">*</span></label>
                                    <input type="text" name="VULTR_ACCESS_KEY_ID" id="VULTR_ACCESS_KEY_ID"
                                           value="<?php echo e(env('VULTR_ACCESS_KEY_ID')); ?>"
                                           class="form-control" placeholder="<?php echo e(__('Enter Vultr Access Key')); ?>">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="VULTR_SECRET_ACCESS_KEY" class="form-label"><?php echo e(__('VULTR Secret Key')); ?><span class="required">*</span></label>
                                    <input type="password" name="VULTR_SECRET_ACCESS_KEY" id="VULTR_SECRET_ACCESS_KEY"
                                           value="<?php echo e(env('VULTR_SECRET_ACCESS_KEY')); ?>"
                                           class="form-control" placeholder="<?php echo e(__('Enter Vultr Secret Key')); ?>">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="VULTR_DEFAULT_REGION" class="form-label"><?php echo e(__('VULTR Region')); ?><span class="required">*</span></label>
                                    <input type="text" name="VULTR_DEFAULT_REGION" id="VULTR_DEFAULT_REGION"
                                           value="<?php echo e(env('VULTR_DEFAULT_REGION')); ?>"
                                           class="form-control" placeholder="<?php echo e(__('Enter Vultr Region')); ?>">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="VULTR_ENDPOINT" class="form-label"><?php echo e(__('VULTR Endpoint')); ?><span class="required">*</span></label>
                                    <input type="text" name="VULTR_ENDPOINT" id="VULTR_ENDPOINT"
                                           value="<?php echo e(env('VULTR_ENDPOINT')); ?>"
                                           class="form-control" placeholder="<?php echo e(__('Enter Vultr Endpoint')); ?>">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="VULTR_BUCKET" class="form-label"><?php echo e(__('VULTR Bucket')); ?><span class="required">*</span></label>
                                    <input type="text" name="VULTR_BUCKET" id="VULTR_BUCKET"
                                           value="<?php echo e(env('VULTR_BUCKET')); ?>"
                                           class="form-control" placeholder="<?php echo e(__('Enter Vultr Bucket Name')); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-none storage-driver" id="do">
                        <div class="row gy-4 mt-3">
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="DO_ACCESS_KEY_ID" class="form-label"><?php echo e(__('DO Access Key ID')); ?><span class="required">*</span></label>
                                    <input type="text" name="DO_ACCESS_KEY_ID" id="DO_ACCESS_KEY_ID"
                                           value="<?php echo e(env('DO_ACCESS_KEY_ID')); ?>"
                                           class="form-control" placeholder="<?php echo e(__('Enter Digital Ocean Access Key ID')); ?>">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="DO_SECRET_ACCESS_KEY" class="form-label"><?php echo e(__('DO Secret Access Key')); ?><span class="required">*</span></label>
                                    <input type="password" name="DO_SECRET_ACCESS_KEY" id="DO_SECRET_ACCESS_KEY"
                                           value="<?php echo e(env('DO_SECRET_ACCESS_KEY')); ?>"
                                           class="form-control" placeholder="<?php echo e(__('Enter Digital Ocean Secret Access Key')); ?>">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="DO_DEFAULT_REGION" class="form-label"><?php echo e(__('DO Default Region')); ?><span class="required">*</span></label>
                                    <input type="text" name="DO_DEFAULT_REGION" id="DO_DEFAULT_REGION"
                                           value="<?php echo e(env('DO_DEFAULT_REGION')); ?>"
                                           class="form-control" placeholder="<?php echo e(__('e.g., nyc3')); ?>">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="DO_BUCKET" class="form-label"><?php echo e(__('DO Bucket')); ?><span class="required">*</span></label>
                                    <input type="text" name="DO_BUCKET" id="DO_BUCKET"
                                           value="<?php echo e(env('DO_BUCKET')); ?>"
                                           class="form-control" placeholder="<?php echo e(__('Enter Digital Ocean Bucket Name')); ?>">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="DO_FOLDER" class="form-label"><?php echo e(__('DO Folder')); ?><span class="required">*</span></label>
                                    <input type="text" name="DO_FOLDER" id="DO_FOLDER"
                                           value="<?php echo e(env('DO_FOLDER')); ?>"
                                           class="form-control" placeholder="<?php echo e(__('Enter Folder Path')); ?>">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="DO_CDN_ID" class="form-label"><?php echo e(__('DO CDN ID')); ?><span class="required">*</span></label>
                                    <input type="text" name="DO_CDN_ID" id="DO_CDN_ID"
                                           value="<?php echo e(env('DO_CDN_ID')); ?>"
                                           class="form-control" placeholder="<?php echo e(__('Enter CDN ID')); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="btn-list">
                <button type="submit" form="storage-settings-form" class="primary-btn"><?php echo e(__('Save')); ?></button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('script'); ?>
    <script src="<?php echo e(asset('admin/js/storage-settings.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('auto_posts.super_admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\super_admin\setting\general_settings\storage-setting.blade.php ENDPATH**/ ?>