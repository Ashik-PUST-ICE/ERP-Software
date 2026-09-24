<?php $__env->startPush('title'); ?>
    <?php echo e($title); ?>

<?php $__env->stopPush(); ?>
<?php $__env->startPush('style'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('super_admin/css/codemirror.css')); ?>"/>
    <link rel="stylesheet" href="<?php echo e(asset('super_admin/css/monokai.css')); ?>"/>
    <link rel="stylesheet" href="<?php echo e(asset('super_admin/css/color-settings.css')); ?>"/>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
<div class="section-title">
    <h2 class="title"><?php echo e($title); ?></h2>
</div>
<div class="settings-page-area">
    <?php echo $__env->make('auto_posts.super_admin.setting.partials.general-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div class="settings-page-right">
        <!-- Color Settings Section -->
        <div class="section-wrap">
            <div class="section-inner-title">
                <h3 class="title"><?php echo e(__('Color Settings')); ?></h3>
            </div>
            <div class="primary-form">
                <form id="color-settings-form" class="ajax" action="<?php echo e(route('super_admin.setting.application-settings.update')); ?>"
                      method="POST"
                      enctype="multipart/form-data" data-handler="commonResponseForModal">
                    <?php echo csrf_field(); ?>
                    <div class="row gy-4">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="app_color_design_type" class="form-label"><?php echo e(__('System Color')); ?></label>
                                <select name="app_color_design_type" id="app_color_design_type"
                                        class="select form-control wide sf-select-without-search" required>
                                    <option value="<?php echo e(DEFAULT_COLOR); ?>"
                                        <?php echo e(getOption('app_color_design_type', DEFAULT_COLOR) == DEFAULT_COLOR ? 'selected' : ''); ?>>
                                        <?php echo e(__('Default')); ?></option>
                                    <option value="<?php echo e(CUSTOM_COLOR); ?>"
                                        <?php echo e(getOption('app_color_design_type', DEFAULT_COLOR) == CUSTOM_COLOR ? 'selected' : ''); ?>>
                                        <?php echo e(__('Custom')); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row gy-4 <?php echo e(getOption('app_color_design_type', DEFAULT_COLOR) == DEFAULT_COLOR ? 'd-none' : ''); ?>"
                         id="custom-color-block">
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Primary Color')); ?><span class="required">*</span></label>
                                <div class="color-picker-wrapper">
                                    <div class="color-preview" style="background-color: <?php echo e(getOption('app_primary_color', '#FF4F02')); ?>" id="app_primary_color_preview"></div>
                                    <div class="color-input-group">
                                        <input class="color5 form-control" type="color"
                                               name="app_primary_color"
                                               value="<?php echo e(getOption('app_primary_color', '#FF4F02')); ?>"
                                               id="app_primary_color">
                                        <input type="text" 
                                               class="color-value-display form-control" 
                                               id="app_primary_color_value"
                                               value="<?php echo e(strtoupper(getOption('app_primary_color', '#FF4F02'))); ?>"
                                               pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$"
                                               placeholder="#FF4F02"
                                               maxlength="7"
                                               title="Enter hex color code (e.g., #FF4F02 or #FFF)">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Hover Color')); ?><span class="required">*</span></label>
                                <div class="color-picker-wrapper">
                                    <div class="color-preview" style="background-color: <?php echo e(getOption('app_hover_color', '#d93900')); ?>" id="app_hover_color_preview"></div>
                                    <div class="color-input-group">
                                        <input class="color5 form-control" type="color"
                                               name="app_hover_color"
                                               value="<?php echo e(getOption('app_hover_color', '#d93900')); ?>"
                                               id="app_hover_color">
                                        <input type="text" 
                                               class="color-value-display form-control" 
                                               id="app_hover_color_value"
                                               value="<?php echo e(strtoupper(getOption('app_hover_color', '#d93900'))); ?>"
                                               pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$"
                                               placeholder="#d93900"
                                               maxlength="7"
                                               title="Enter hex color code (e.g., #d93900 or #FFF)">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Text Color')); ?><span class="required">*</span></label>
                                <div class="color-picker-wrapper">
                                    <div class="color-preview" style="background-color: <?php echo e(getOption('app_text_color', '#1b1c17')); ?>" id="app_text_color_preview"></div>
                                    <div class="color-input-group">
                                        <input class="color5 form-control" type="color"
                                               name="app_text_color"
                                               value="<?php echo e(getOption('app_text_color', '#1b1c17')); ?>"
                                               id="app_text_color">
                                        <input type="text" 
                                               class="color-value-display form-control" 
                                               id="app_text_color_value"
                                               value="<?php echo e(strtoupper(getOption('app_text_color', '#1b1c17'))); ?>"
                                               pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$"
                                               placeholder="#1b1c17"
                                               maxlength="7"
                                               title="Enter hex color code (e.g., #1b1c17 or #FFF)">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Text Secondary Color')); ?><span class="required">*</span></label>
                                <div class="color-picker-wrapper">
                                    <div class="color-preview" style="background-color: <?php echo e(getOption('app_text_secondary_color', '#707070')); ?>" id="app_text_secondary_color_preview"></div>
                                    <div class="color-input-group">
                                        <input class="color5 form-control" type="color"
                                               name="app_text_secondary_color"
                                               value="<?php echo e(getOption('app_text_secondary_color', '#707070')); ?>"
                                               id="app_text_secondary_color">
                                        <input type="text" 
                                               class="color-value-display form-control" 
                                               id="app_text_secondary_color_value"
                                               value="<?php echo e(strtoupper(getOption('app_text_secondary_color', '#707070'))); ?>"
                                               pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$"
                                               placeholder="#707070"
                                               maxlength="7"
                                               title="Enter hex color code (e.g., #707070 or #FFF)">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Sidebar BG Color')); ?><span class="required">*</span></label>
                                <div class="color-picker-wrapper">
                                    <div class="color-preview" style="background-color: <?php echo e(getOption('app_sidebar_bg_color', '#1b1c17')); ?>" id="app_sidebar_bg_color_preview"></div>
                                    <div class="color-input-group">
                                        <input class="color5 form-control" type="color"
                                               name="app_sidebar_bg_color"
                                               value="<?php echo e(getOption('app_sidebar_bg_color', '#1b1c17')); ?>"
                                               id="app_sidebar_bg_color">
                                        <input type="text" 
                                               class="color-value-display form-control" 
                                               id="app_sidebar_bg_color_value"
                                               value="<?php echo e(strtoupper(getOption('app_sidebar_bg_color', '#1b1c17'))); ?>"
                                               pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$"
                                               placeholder="#1b1c17"
                                               maxlength="7"
                                               title="Enter hex color code (e.g., #1b1c17 or #FFF)">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Sidebar Text Color')); ?><span class="required">*</span></label>
                                <div class="color-picker-wrapper">
                                    <div class="color-preview" style="background-color: <?php echo e(getOption('app_sidebar_text_color', '#f6f5f5')); ?>" id="app_sidebar_text_color_preview"></div>
                                    <div class="color-input-group">
                                        <input class="color5 form-control" type="color"
                                               name="app_sidebar_text_color"
                                               value="<?php echo e(getOption('app_sidebar_text_color', '#f6f5f5')); ?>"
                                               id="app_sidebar_text_color">
                                        <input type="text" 
                                               class="color-value-display form-control" 
                                               id="app_sidebar_text_color_value"
                                               value="<?php echo e(strtoupper(getOption('app_sidebar_text_color', '#f6f5f5'))); ?>"
                                               pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$"
                                               placeholder="#f6f5f5"
                                               maxlength="7"
                                               title="Enter hex color code (e.g., #f6f5f5 or #FFF)">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="btn-list">
                <button type="submit" form="color-settings-form" class="primary-btn"><?php echo e(__('Save')); ?></button>
            </div>
        </div>

        <!-- Custom CSS Section -->
        <div class="section-wrap">
            <div class="section-inner-title">
                <h3 class="title"><?php echo e(__('Custom CSS')); ?></h3>
            </div>
            <div class="primary-form">
                <form id="custom-css-form" class="ajax"
                      action="<?php echo e(route('super_admin.setting.application-settings.update')); ?>"
                      method="POST"
                      enctype="multipart/form-data" data-handler="commonResponseForModal">
                    <?php echo csrf_field(); ?>
                    <div class="row gy-4">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="card card-outline card-info">
                                    <div class="card-header">
                                        <?php echo e(__('Custom CSS')); ?>

                                    </div>
                                    <div class="card-body">
                                        <textarea name="custom_css" id="custom-css-editor" class="form-control"><?php echo e(getOption('custom_css', '/*css code here*/ ')); ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="btn-list">
                <button type="submit" form="custom-css-form" class="primary-btn"><?php echo e(__('Save')); ?></button>
            </div>
        </div>

        <!-- Custom JS Section -->
        <div class="section-wrap">
            <div class="section-inner-title">
                <h3 class="title"><?php echo e(__('Custom JS')); ?></h3>
            </div>
            <div class="primary-form">
                <form id="custom-js-form" class="ajax"
                      action="<?php echo e(route('super_admin.setting.application-settings.update')); ?>"
                      method="POST"
                      enctype="multipart/form-data" data-handler="commonResponseForModal">
                    <?php echo csrf_field(); ?>
                    <div class="row gy-4">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="card card-outline card-info">
                                    <div class="card-header">
                                        <?php echo e(__('Custom JS')); ?>

                                    </div>
                                    <div class="card-body">
                                        <textarea name="custom_js"
                                                  id="custom-js-editor" class="form-control"><?php echo e(getOption('custom_js', '//js code here')); ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="btn-list">
                <button type="submit" form="custom-js-form" class="primary-btn"><?php echo e(__('Save')); ?></button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
    <script src="<?php echo e(asset('super_admin/js/codemirror.js')); ?>"></script>
    <script src="<?php echo e(asset('super_admin/js/codemirror-mode.js')); ?>"></script>
    <script src="<?php echo e(asset('super_admin/js/codemirror-js-mode.js')); ?>"></script>
    <script src="<?php echo e(asset('super_admin/js/color-settings.js')); ?>"></script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('auto_posts.super_admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\super_admin\setting\general_settings\color-settings.blade.php ENDPATH**/ ?>