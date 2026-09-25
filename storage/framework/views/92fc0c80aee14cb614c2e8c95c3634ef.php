<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e(__('Our Tool Section Configuration')); ?></h4>
    <div class="mClose">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
</div>

<?php
$toolCount = (int) getOption('landing_tool_card_count', 3);
$toolCount = max(1, min(16, $toolCount));
?>

<div class="primary-form">
    <form class="ajax" action="<?php echo e(route('super_admin.setting.frontend.landing-page.update')); ?>" method="POST"
        enctype="multipart/form-data" data-handler="commonResponseForModal" id="tools-config-form">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="landing_tool_card_count" id="landing_tool_card_count" value="<?php echo e($toolCount); ?>">
        <div class="row gy-4">
            <div class="col-lg-12">
                <div class="form-group">
                    <label class="form-label"><?php echo e(__('Section Title')); ?></label>
                    <input type="text" name="landing_tools_title" class="form-control"
                        value="<?php echo e(getOption('landing_tools_title', __('We Have Some Amazing Features For Team.'))); ?>"
                        placeholder="<?php echo e(__('Enter tools section title')); ?>">
                </div>
            </div>

            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0"><?php echo e(__('Tool Cards')); ?></h5>
                    <button type="button" class="primary-btn" id="tools-add-more-btn" onclick="toolsAddMore()">
                        <?php echo e(__('Add More')); ?>

                    </button>
                </div>
            </div>

            <div class="col-12" id="tools-cards-wrapper">
                <?php for($n = 1; $n <= 16; $n++): ?> <div class="tool-card-block border rounded p-3 mb-3"
                    data-card-num="<?php echo e($n); ?>" style="<?php echo e($n > $toolCount ? 'display:none' : ''); ?>">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0"><?php echo e(__('Tool')); ?> #<?php echo e($n); ?></h6>
                        <?php if($n > 1): ?>
                        <button type="button" class="btn btn-sm btn-danger tools-remove-btn"
                            onclick="toolsRemoveCard(<?php echo e($n); ?>)">&times;</button>
                        <?php endif; ?>
                    </div>
                    <div class="row gy-2">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Tool Name')); ?> (<?php echo e(__('Tool')); ?> <?php echo e($n); ?>)</label>
                                <input type="text" name="landing_tool_card<?php echo e($n); ?>_name" class="form-control"
                                    value="<?php echo e(getOption('landing_tool_card' . $n . '_name')); ?>">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label d-block"><?php echo e(__('Tool Image')); ?> (<?php echo e(__('Tool')); ?> <?php echo e($n); ?>)</label>
                                <div class="zImage-upload-details mw-100">
                                    <div class="upload-img-box">
                                        <img
                                            src="<?php echo e(getOption('landing_tool_card' . $n . '_image') ? getSettingImage('landing_tool_card' . $n . '_image') : ''); ?>" />
                                        <input type="file" name="landing_tool_card<?php echo e($n); ?>_image" accept="image/*"
                                            onchange="previewFile(this)" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <?php endfor; ?>
        </div>
</div>

<!-- Footer Buttons -->
<div class="btn-list mt-4 pt-3 border-top">
    <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Close')); ?></button>
    <button type="submit" class="primary-btn"><?php echo e(__('Save')); ?></button>
</div>
</form>
</div>

<script src="<?php echo e(asset('super_admin/js/landing/tools-config.js')); ?>"></script><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\super_admin\setting\general_settings\frontend\tools_configuration.blade.php ENDPATH**/ ?>