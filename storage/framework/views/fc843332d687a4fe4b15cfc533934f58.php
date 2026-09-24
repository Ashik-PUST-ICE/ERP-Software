<div class="modal-body zModalTwo-body">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e(__('Edit Currency')); ?></h4>
        <div class="mClose">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            </button>
        </div>
    </div>

    <form class="ajax reset" action="<?php echo e(route('super_admin.setting.currencies.update', $currency->id)); ?>" method="post" id="edit-currency-form"
          data-handler="commonResponseForModal">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PATCH'); ?>
        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-12">
                    <div class="form-group">
                        <label for="currency_code-edit" class="form-label"><?php echo e(__('Currency ISO Code')); ?><span class="required">*</span></label>
                        <select id="sf-select-currency-edit" class="select form-control wide sf-select-without-search" name="currency_code" required>
                            <option value=""><?php echo e(__('Select Currency')); ?></option>
                            <?php $__currentLoopData = getCurrency(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $currencyItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($code); ?>" <?php echo e($code == $currency->currency_code ? 'selected' : ''); ?>><?php echo e($currencyItem); ?> (<?php echo e($code); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="symbol-edit" class="form-label"><?php echo e(__('Symbol')); ?><span class="required">*</span></label>
                        <input type="text" class="form-control" name="symbol" id="symbol-edit" placeholder="<?php echo e(__('Enter currency symbol')); ?>" value="<?php echo e($currency->symbol); ?>" required>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="currency_placement-edit" class="form-label"><?php echo e(__('Currency Placement')); ?><span class="required">*</span></label>
                        <select class="select form-control wide sf-select-without-search" name="currency_placement" id="currency_placement-edit" required>
                            <option value=""><?php echo e(__('Select Placement')); ?></option>
                            <option value="before" <?php echo e($currency->currency_placement == 'before' ? 'selected' : ''); ?>><?php echo e(__('Before Amount')); ?></option>
                            <option value="after" <?php echo e($currency->currency_placement == 'after' ? 'selected' : ''); ?>><?php echo e(__('After Amount')); ?></option>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" value="1" name="current_currency"
                                role="switch" id="flexCheckChecked-edit-<?php echo e($currency->id); ?>" <?php echo e($currency->current_currency == STATUS_ACTIVE ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="flexCheckChecked-edit-<?php echo e($currency->id); ?>">
                                <?php echo e(__('Set as Default Currency')); ?>

                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Buttons -->
        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
            <button type="submit" class="primary-btn"><?php echo e(__('Update')); ?></button>
        </div>
    </form>
</div>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\super_admin\setting\currencies\edit-form.blade.php ENDPATH**/ ?>