<?php $__env->startPush('title'); ?>
<?php echo e($title); ?>

<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
<div class="section-title">
    <h2 class="title"><?php echo e(__($title)); ?></h2>
</div>
<div class="settings-page-area">
    <?php echo $__env->make('auto_posts.super_admin.setting.partials.general-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div class="settings-page-right">
        <div class="section-wrap">
            <div class="section-inner-title">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <h3 class="title"><?php echo e(__('Translate Language')); ?></h3>
                    <div class="d-flex flex-wrap gap-2">
                        <a class="primary-btn" href="<?php echo e(route('super_admin.setting.languages.download', $language->id)); ?>"
                            title="<?php echo e(__('Download File')); ?>">
                            <i class="fa fa-download me-2"></i><?php echo e(__('Download File')); ?>

                        </a>
                        <button type="button" class="primary-btn" data-bs-toggle="modal" data-bs-target="#importFile"
                            title="<?php echo e(__('Import File')); ?>">
                            <i class="fa fa-upload me-2"></i><?php echo e(__('Import File')); ?>

                        </button>
                        <button type="button" class="primary-btn" data-bs-toggle="modal" data-bs-target="#importModal"
                            title="<?php echo e(__('Import Keywords')); ?>">
                            <i class="fa fa-file-import me-2"></i><?php echo e(__('Import Keywords')); ?>

                        </button>
                        <button type="button" class="primary-btn addmore">
                            <i class="fa fa-plus me-2"></i><?php echo e(__('Add More')); ?>

                        </button>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <form id="search-form">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control"
                                placeholder="<?php echo e(__('Search Key or Value')); ?>">
                            <button class="primary-btn" type="submit"><?php echo e(__('Search')); ?></button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="translations-container">
                <?php echo $__env->make('auto_posts.super_admin.setting.languages.partials.translations_table', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="language-route" value="<?php echo e(route('super_admin.setting.languages.index')); ?>">
<input type="hidden" id="updateLangItemRoute"
    value="<?php echo e(route('super_admin.setting.languages.update.translate', [$language->id])); ?>">
<input type="hidden" id="language-translate-route"
    value="<?php echo e(route('super_admin.setting.languages.translate', [$language->id])); ?>">
<input type="hidden" id="update-text" value="<?php echo e(__('Update')); ?>">

<!-- Import Keywords Modal -->
<div class="modal fade zModalTwo" id="importModal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            <form class="ajax" action="<?php echo e(route('super_admin.setting.languages.import')); ?>" method="POST"
                data-handler="languageHandler">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="current" value="<?php echo e($language->iso_code); ?>">
                <div class="modal-body zModalTwo-body">
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e(__('Import Language')); ?></h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="alert alert-warning mb-4" role="alert">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0 me-3">
                                <i class="fa-solid fa-exclamation-triangle fa-lg mt-1"></i>
                            </div>
                            <div class="flex-grow-1">
                                <strong><?php echo e(__('Warning:')); ?></strong>
                                <?php echo e(__('If you import keywords, your current keywords will be deleted and replaced by the imported keywords.')); ?>

                            </div>
                        </div>
                    </div>

                    <div class="primary-form">
                        <div class="row gy-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="sf-select-modal-import" class="form-label"><?php echo e(__('Language')); ?><span
                                            class="required">*</span></label>
                                    <select name="import" class="select form-control wide sf-select-without-search"
                                        id="sf-select-modal-import" required>
                                        <option value=""><?php echo e(__('Select Language')); ?></option>
                                        <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($lang->iso_code); ?>"><?php echo e(__($lang->language)); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="btn-list mt-4 pt-3 border-top">
                        <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
                        <button type="submit" class="primary-btn"><?php echo e(__('Import')); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Import File Modal -->
<div class="modal fade zModalTwo" id="importFile" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            <form class="ajax" action="<?php echo e(route('super_admin.setting.languages.upload', $language->id)); ?>" method="POST"
                enctype="multipart/form-data" data-handler="languageHandler">
                <?php echo csrf_field(); ?>
                <div class="modal-body zModalTwo-body">
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e(__('Upload Translated File')); ?></h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="alert alert-info mb-4" role="alert">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0 me-3">
                                <i class="fa-solid fa-info-circle fa-lg mt-1"></i>
                            </div>
                            <div class="flex-grow-1">
                                <?php echo e(__('Upload a valid JSON translation file. Existing translations will be merged with the uploaded file. Keys in the uploaded file will overwrite existing keys.')); ?>

                            </div>
                        </div>
                    </div>

                    <div class="primary-form">
                        <div class="form-group">
                            <label for="file" class="form-label"><?php echo e(__('Select JSON File')); ?><span
                                    class="required">*</span></label>
                            <input type="file" name="file" class="form-control" accept=".json" required>
                            <small class="form-text text-muted"><?php echo e(__('Only JSON files are allowed')); ?></small>
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="btn-list mt-4 pt-3 border-top">
                        <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
                        <button type="submit" class="primary-btn"><?php echo e(__('Upload')); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('style'); ?>
<link rel="stylesheet" href="<?php echo e(asset('super_admin/css/languages.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
<script src="<?php echo e(asset('admin/js/languages.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('auto_posts.super_admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\super_admin\setting\languages\translate.blade.php ENDPATH**/ ?>