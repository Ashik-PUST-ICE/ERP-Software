<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e(__('Pricing Section Configuration')); ?></h4>
    <div class="mClose">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
</div>

<div class="primary-form">
    <form class="ajax" action="<?php echo e(route('super_admin.setting.frontend.landing-page.update')); ?>" method="POST"
        enctype="multipart/form-data" data-handler="commonResponseForModal">
        <?php echo csrf_field(); ?>
        <div class="row gy-4">
            <div class="col-lg-12">
                <div class="form-group">
                    <label class="form-label"><?php echo e(__('Section Title')); ?></label>
                    <input type="text" name="landing_pricing_title" class="form-control"
                        value="<?php echo e(getOption('landing_pricing_title', __('Affordable License For Every Budget.'))); ?>"
                        placeholder="<?php echo e(__('Enter pricing section title')); ?>">
                </div>
            </div>
        </div>

        <!-- Footer Buttons -->
        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Close')); ?></button>
            <button type="submit" class="primary-btn"><?php echo e(__('Save')); ?></button>
        </div>
    </form>
</div><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\super_admin\setting\general_settings\frontend\pricing_configuration.blade.php ENDPATH**/ ?>