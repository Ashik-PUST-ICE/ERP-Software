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
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="title"><?php echo e(__('Language Settings')); ?></h3>
                    <button type="button" class="primary-btn" data-bs-toggle="modal" data-bs-target="#add-modal">
                        <i class="fa fa-plus me-2"></i><?php echo e(__('Add Language')); ?>

                    </button>
                </div>
            </div>
            <div class="table-waraper">
                <div class="search-input-wrap">
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
                        placeholder="<?php echo e(__('Search By Language...')); ?>" />
                </div>
                <table class="display search-datatable primary-table dataTable dtr-inline">
                    <thead>
                        <tr>
                            <th class="keep-show"><?php echo e(__("Flag")); ?></th>
                            <th><?php echo e(__("Language")); ?></th>
                            <th><?php echo e(__("ISO Code")); ?></th>
                            <th><?php echo e(__("RTL")); ?></th>
                            <th class="keep-show"><?php echo e(__("Action")); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <img class="flag-icon" src="<?php echo e(getFileUrl($language->flag_id)); ?>" alt="flag">
                            </td>
                            <td>
                                <?php echo e($language->language); ?>

                                <?php if($language->default == STATUS_ACTIVE): ?>
                                <b>(<?php echo e(__('Default')); ?>)</b>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($language->iso_code); ?></td>
                            <td><?php echo e($language->rtl == STATUS_ACTIVE ? __('Yes') : __('No')); ?></td>
                            <td>
                                <div class="inline-flex">
                                    <div class="dropdown options-area">
                                        <a class="options-btn" href="#" role="button" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0)"
                                                    onclick="openEditModal('<?php echo e(route('super_admin.setting.languages.edit', $language->id)); ?>', <?php echo e($language->id); ?>)">
                                                    <?php echo e(__('Edit')); ?>

                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0)"
                                                    onclick="deleteItem('<?php echo e(route('super_admin.setting.languages.delete', $language->id)); ?>')">
                                                    <?php echo e(__('Delete')); ?>

                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <a href="<?php echo e(route('super_admin.setting.languages.translate', $language->id)); ?>"
                                        class="translator">
                                        <?php echo e(__('Translator')); ?>

                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center"><?php echo e(__('No languages found')); ?></td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php echo $__env->make('auto_posts.super_admin.pagination.common-pagination', ['paginationUrl' => request()->url()], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>
</div>

<!-- Add Modal section start -->
<div class="modal fade zModalTwo" id="add-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            <form class="ajax reset" action="<?php echo e(route('super_admin.setting.languages.store')); ?>" method="post"
                data-handler="languageHandler" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="modal-body zModalTwo-body">
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e(__('Add Language')); ?></h4>
                        <div class="mClose">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                
                            </button>
                        </div>
                    </div>

                    <div class="primary-form">
                        <div class="row gy-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="language" class="form-label"><?php echo e(__('Language')); ?><span
                                            class="required">*</span></label>
                                    <input type="text" class="form-control" name="language" id="language"
                                        placeholder="<?php echo e(__('Enter language name')); ?>" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="iso_code" class="form-label"><?php echo e(__('ISO Code')); ?><span
                                            class="required">*</span></label>
                                    <select name="iso_code" class="select form-control wide sf-select-without-search"
                                        id="sf-select-modal-add" required>
                                        <option value=""><?php echo e(__('Select ISO Code')); ?></option>
                                        <?php $__currentLoopData = languageIsoCode(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $isoCountryName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($code); ?>"><?php echo e($isoCountryName.'('.$code.')'); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="flag" class="form-label"><?php echo e(__('Flag')); ?> <span
                                            class="text-muted small">(jpeg, png, jpg, svg, webp)</span><span
                                            class="required">*</span></label>
                                    <div class="zImage-upload-details mw-100">
                                        <div class="zImage-inside">
                                            <div class="d-flex pb-12">
                                                <img src="<?php echo e(asset('assets/images/icon/upload-img-1.svg')); ?>" alt="" />
                                            </div>
                                            <p class="fs-15 fw-500 lh-16 text-1b1c17 mb-0">
                                                <?php echo e(__('Drag & drop files here')); ?></p>
                                        </div>
                                        <div class="upload-img-box">
                                            <img src="" alt="Flag preview" />
                                            <input type="file" name="flag" id="flag" accept="image/*"
                                                onchange="previewFile(this)" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 d-none">
                                <div class="form-group">
                                    <label for="attachmentFile" class="form-label"><?php echo e(__('Font File')); ?> <span
                                            class="text-muted small">(PDF)</span></label>
                                    <input type="file" class="form-control" id="attachmentFile" accept="application/pdf"
                                        name="font">
                                    <?php if($errors->has('font')): ?>
                                    <div class="text-danger mt-1">
                                        <i class="fas fa-exclamation-triangle"></i> <?php echo e($errors->first('font')); ?>

                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="rtl" class="form-label"><?php echo e(__('RTL Supported')); ?><span
                                            class="required">*</span></label>
                                    <select name="rtl" class="select form-control wide sf-select-without-search"
                                        id="rtl" required>
                                        <option value="0"><?php echo e(__("No")); ?></option>
                                        <option value="1"><?php echo e(__("Yes")); ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" value="1" name="default"
                                            role="switch" id="flexCheckChecked">
                                        <label class="form-check-label" for="flexCheckChecked">
                                            <?php echo e(__('Default Language')); ?>

                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="btn-list mt-4 pt-3 border-top">
                        <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
                        <button type="submit" class="primary-btn"><?php echo e(__('Save')); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Add Modal section end -->

<!-- Edit Modal section start -->
<div class="modal fade zModalTwo" id="edit-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            <form class="ajax reset" action="<?php echo e(route('super_admin.setting.languages.update', 0)); ?>" method="post"
                id="edit-language-form" data-handler="languageHandler" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="modal-body zModalTwo-body">
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e(__('Language Settings')); ?></h4>
                        <div class="mClose">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                
                            </button>
                        </div>
                    </div>

                    <div class="primary-form">
                        <div class="row gy-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="language-edit" class="form-label"><?php echo e(__('Language')); ?><span
                                            class="required">*</span></label>
                                    <input type="text" class="form-control" name="language" id="language-edit"
                                        placeholder="<?php echo e(__('Enter language name')); ?>" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="iso_code-edit" class="form-label"><?php echo e(__('ISO Code')); ?></label>
                                    <select name="iso_code" class="select form-control wide sf-select-without-search"
                                        id="iso_code-edit">
                                        <option value=""><?php echo e(__('Select ISO Code')); ?></option>
                                        <?php $__currentLoopData = languageIsoCode(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $isoCountryName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($code); ?>"><?php echo e($isoCountryName.'('.$code.')'); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="flag-edit" class="form-label"><?php echo e(__('Flag')); ?> <span
                                            class="text-muted small">(jpeg, png, jpg, svg, webp)</span><span
                                            class="required">*</span></label>
                                    <div class="zImage-upload-details mw-100">
                                        <div class="zImage-inside">
                                            <div class="d-flex pb-12">
                                                <img src="<?php echo e(asset('assets/images/icon/upload-img-1.svg')); ?>" alt="" />
                                            </div>
                                            <p class="fs-15 fw-500 lh-16 text-1b1c17 mb-0">
                                                <?php echo e(__('Drag & drop files here')); ?></p>
                                        </div>
                                        <div class="upload-img-box">
                                            <img src="" alt="Flag preview" id="flag-preview-edit" />
                                            <input type="file" name="flag" id="flag-edit" accept="image/*"
                                                onchange="previewFile(this)">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 d-none">
                                <div class="form-group">
                                    <label for="attachmentFile-edit" class="form-label"><?php echo e(__('Font File')); ?> <span
                                            class="text-muted small">(PDF)</span></label>
                                    <input type="file" class="form-control" id="attachmentFile-edit"
                                        accept="application/pdf" name="font">
                                    <?php if($errors->has('font')): ?>
                                    <div class="text-danger mt-1">
                                        <i class="fas fa-exclamation-triangle"></i> <?php echo e($errors->first('font')); ?>

                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="rtl-edit" class="form-label"><?php echo e(__('RTL Supported')); ?><span
                                            class="required">*</span></label>
                                    <select name="rtl" class="select form-control wide sf-select-without-search"
                                        id="rtl-edit" required>
                                        <option value="0"><?php echo e(__("No")); ?></option>
                                        <option value="1"><?php echo e(__("Yes")); ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" value="1" name="default"
                                            role="switch" id="flexCheckChecked-edit">
                                        <label class="form-check-label" for="flexCheckChecked-edit">
                                            <?php echo e(__('Default Language')); ?>

                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="btn-list mt-4 pt-3 border-top">
                        <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
                        <button type="submit" class="primary-btn"><?php echo e(__('Save')); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Edit Modal section end -->
<?php $__env->stopSection(); ?>
<?php $__env->startPush('style'); ?>
<link rel="stylesheet" href="<?php echo e(asset('super_admin/css/languages.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
<script src="<?php echo e(asset('admin/js/languages.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('auto_posts.super_admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\super_admin\setting\languages\index.blade.php ENDPATH**/ ?>