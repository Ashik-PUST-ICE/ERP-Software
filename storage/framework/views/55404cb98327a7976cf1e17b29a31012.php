<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e(__('SMS Configuration')); ?></h4>
    <div class="mClose">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
</div>

<form class="ajax reset" action="<?php echo e(route('super_admin.setting.sms-configuration')); ?>" method="POST" enctype="multipart/form-data"
      data-handler="commonResponseForModal">
    <?php echo csrf_field(); ?>
    
    <div class="primary-form">
        <div class="row gy-3">
            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                <div class="form-group">
                    <label class="form-label"><?php echo e(__('TWILIO ACCOUNT SID')); ?><span class="required">*</span></label>
                    <input type="text" name="TWILIO_ACCOUNT_SID" value="<?php echo e(getOption('TWILIO_ACCOUNT_SID')); ?>"
                           class="form-control" placeholder="<?php echo e(__('TWILIO ACCOUNT SID')); ?>">
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                <div class="form-group">
                    <label class="form-label"><?php echo e(__('TWILIO AUTH TOKEN')); ?><span class="required">*</span></label>
                    <input type="text" name="TWILIO_AUTH_TOKEN" value="<?php echo e(getOption('TWILIO_AUTH_TOKEN')); ?>"
                           class="form-control" placeholder="<?php echo e(__('TWILIO AUTH TOKEN')); ?>">
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                <div class="form-group">
                    <label class="form-label"><?php echo e(__('TWILIO PHONE NUMBER')); ?><span class="required">*</span></label>
                    <input type="text" name="TWILIO_PHONE_NUMBER" value="<?php echo e(getOption('TWILIO_PHONE_NUMBER')); ?>"
                           class="form-control" placeholder="<?php echo e(__('TWILIO PHONE NUMBER')); ?>">
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer Buttons -->
    <div class="btn-list mt-4 pt-3 border-top">
        <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Close')); ?></button>
        <button type="button" class="primary-btn" id="sendTestSMSBtn"><?php echo e(__('Send Test SMS')); ?></button>
        <button type="submit" class="primary-btn"><?php echo e(__('Update')); ?></button>
    </div>
</form>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\super_admin\setting\general_settings\configuration\form\sms_configuration.blade.php ENDPATH**/ ?>